<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Empleado;
use App\Models\Equipo;
use App\Models\Tecnico;
use App\Models\Ticket;
use App\Models\ticket_historial;
use App\Models\VsEquiposPorTicket;
use App\Models\VsTicket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\EquipoTicket;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Detection\MobileDetect as MobileDetect;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //este es el que tengo que modificar DZ-- inicia la modificacion 1
    public function index()
    {
        $vstickets = VsTicket::where('activo', '=', 1)
            ->where('estatus', '=', 'Abierto')->get();

        $tecnicos = Tecnico::where('activo', '=', 1)->get();
        $tickets = $this->cargarDT($vstickets);
        $id_tecnico = Tecnico::where('activo', 1)->where('user_id', Auth::user()->id)->first();
        return view('ticket.index', compact('id_tecnico'))->with('tickets', $tickets)->with('tecnicos', $tecnicos);
    }
    //este el que se modificó fin de la modificación 1
    public function cargarDT($consulta)
    {

        $tickets = [];
        $tecnico = Tecnico::where('activo', 1)->where('user_id', Auth::user()->id)->first();
        foreach ($consulta as $key => $value) {

            $actualizar =  route('tickets.edit', $value['id']);
            $recibo = route('recepcionEquipo',  $value['id']);
            $tomar = route('tomar-ticket', $value['id']);
            $registro_historial = ticket_historial::where('id_ticket', $value['id'])->orderBy('created_at')->get();


            if (Auth::user()->id == 161 || Auth::user()->role  == 'admin') {
                $tomar = '<button type="button" onclick="tomar_ticket(' . $value['id'] . ')" class="btn btn-outline-warning" data-toggle="modal" data-target="#tomar-ticket">
                <i class="far fa-hand-paper"></i>
              </button>
              ';
            } elseif (isset($tecnico)) {
                if ($tecnico->id != $value['tecnico_id']) {
                    $tomar = '<a href="' . $tomar . '" class="btn btn-warning" title="Tomar ticket">
                    <i class="far fa-hand-paper"></i>
               </a>';
                } else {
                    $tomar = '';
                }
                if ($tecnico->id == $value['tecnico_id']) {
                    $tomar = '<button type="button" onclick="soltar_ticket(' . $value['id'] . ')"  class="btn btn-outline-dark" data-toggle="modal" data-target="#soltar-ticket">
                    <i class="far fa-hand-paper"></i>
                  </button>';
                }
            } else {
                $tomar = '';
                $historial = '';
            }
            if (count($registro_historial) > 0) {
                foreach ($registro_historial as $item) {
                    $tecnico_nombre = Tecnico::where('user_id', $item->id_user)->first();
                    $item->nombre = $tecnico_nombre->nombre;
                }
                $historial = '<button type="button" onclick=' . "'" . 'historial(' . $registro_historial . ')' . "'" . ' class="btn btn-outline-dark" data-toggle="modal" data-target="#historial-ticket">
                <i class="far fa-list-alt"></i>
                </button>';
            } else {
                $historial = '';
            }

            $acciones = '
                <div class="btn-acciones mobile-ticket-cont">
                    <div class="btn-circle">
                        <a href="' . $actualizar . '" class="btn btn-success" title="Actualizar">
                            <i class="far fa-edit"></i>
                        </a>
			            <a href="' . $recibo . '" class="btn btn-primary" title="Recibo de Equipo">
                            <i class="far fa-file"></i>
                        </a>'
                . $tomar . $historial .
                '
                    </div>
                </div>
                </div>
              </div>
            ';
            $area = $value['area'];
            if (str_contains($area, 'Belenes')) {
                $area = str_replace('- Belenes ', "", $area);
                $area = '<b>Belenes</b> - ' . $area;
            } else {
                $area = str_replace('- La Normal ', "", $area);
                $area = '<b>La Normal</b> - ' . $area;
            }

            $tickets[$key] = array(
                '',
                $value['id'],
                $value['fecha_reporte'] = \Carbon\Carbon::parse($value->fecha_reporte)->format('d/m/Y H:i'),
                $area,
                $value['solicitante'],
                $value['contacto'],
                $value['tecnico'],
                $value['categoria'] . ". Prioridad: " . $value['prioridad'],
                $value['datos_reporte'],
                $acciones,
            );
        }

        return $tickets;
    }
    public function revisionTickets()
    {
        $vstickets = VsTicket::where('activo', '=', 1)->get();
        $tecnicos = Tecnico::where('activo', '=', 1)->get();
        $tickets = $this->cargarDT($vstickets);
        return view('ticket.revisionTickets')->with('tickets', $tickets)->with('tecnicos', $tecnicos);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $equipos = Equipo::all();
        //$areas = Area::pluck('id','area')->prepend('seleciona');
        $areas = Area::where('activo',1)->get();
        $tecnicos = Tecnico::where('activo', '=', 1)->get();
        return view('ticket.create')->with('equipos', $equipos)->with('areas', $areas)->with('tecnicos', $tecnicos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $this->validate($request, [
            'area_id' => 'required',
            'solicitante' => 'required',
            'contacto' => 'required',
            'tecnico_id' => 'required',
            'categoria' => 'required',
            'prioridad' => 'required',
            'estatus' => 'required',
            'datos_reporte' => 'required',
        ]);
        $ticket = new Ticket();
        $ticket->area_id = $request->input('area_id');
        $ticket->solicitante = $request->input('solicitante');
        $ticket->contacto = $request->input('contacto');
        $ticket->tecnico_id = $request->input('tecnico_id');
        $ticket->categoria = $request->input('categoria');
        $ticket->prioridad = $request->input('prioridad');
        $ticket->estatus = $request->input('estatus');
        $ticket->datos_reporte = $request->input('datos_reporte');
        $ticket->fecha_reporte = date('Y/m/d');
        $ticket->fecha_inicio  = $request->input('fecha_inicio ');
        $ticket->fecha_termino = $request->input('fecha_termino');
        $ticket->problema = $request->input('problema');
        $ticket->solucion = $request->input('solucion');
        $ticket->save();
        //
        $log = new Log();
        $log->tablas = 'tickets';
        $log->movimimiento = "�rea id: " . $ticket->area_id . "Solicitante: " . $ticket->solicitante . "Contacto: " . $ticket->contacto . "T�cnico: " . $ticket->tecnico_id . "Categoria: " . $ticket->categoria . "Prioridad: " . $ticket->prioridad . "Estatus: " . $ticket->estatus . "Datos de reporte: " . $ticket->datos_reporte . "Fecha de reporte: " . $ticket->fecha_reporte . "Fecha de inicio: " . $ticket->fecha_inicio . "Fecha de termino: " . $ticket->fecha_termino . "Problema: " . $ticket->problema . "Soluci�n: " . $ticket->solucion;
        $log->usuario_id = Auth::user()->id;
        $log->acciones = 'Insertar';
        $ticket->save();
        //
        return redirect('tickets')->with(array(
            'message' => 'El Ticket se guardo Correctamente'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipos = Equipo::all();
        //$areas = Area::pluck('id','area')->prepend('seleciona');
        $areas = Area::all();
        $ticket = VsTicket::find($id);
        $tecnicos = Tecnico::where('activo', '=', 1)->get();
        return view('ticket.edit')->with('ticket', $ticket)->with('equipos', $equipos)->with('areas', $areas)->with('tecnicos', $tecnicos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validateData = $this->validate($request, [
            'area_id' => 'required',
            'solicitante' => 'required',
            'contacto' => 'required',
            'tecnico_id' => 'required',
            'categoria' => 'required',
            'prioridad' => 'required',
            'estatus' => 'required',
            'datos_reporte' => 'required',
            'fecha_reporte' => 'required'
        ]);

        $ticket = Ticket::find($id);
        $ticket->area_id = $request->input('area_id');
        $ticket->solicitante = $request->input('solicitante');
        $ticket->contacto = $request->input('contacto');
        $ticket->tecnico_id = $request->input('tecnico_id');
        $ticket->categoria = $request->input('categoria');
        $ticket->prioridad = $request->input('prioridad');
        $ticket->estatus = $request->input('estatus');
        $ticket->datos_reporte = $request->input('datos_reporte');
        //$ticket->fecha_reporte = $request->input('fecha_reporte');
        $ticket->fecha_inicio  = $request->input('fecha_inicio ');

        $ticket->fecha_termino = $request->input('fecha_termino');
        if (!is_null($ticket->fecha_termino) && isset($ticket->fecha_termino)) {
            $ticket->estatus = 'Cerrado';
        }
        $ticket->problema = $request->input('problema');
        $ticket->solucion = $request->input('solucion');
        $ticket->update();
        //
        $log = new Log();
        $log->tabla = "tickets";
        $mov = "";
        $mov = $mov . " area_id:" . $ticket->area_id . " solicitante:" . $ticket->solicitante . " contacto" . $ticket->contacto;
        $mov = $mov . " tecnico_id:" . $ticket->tecnico_id . " categoria:" . $ticket->categoria . " prioridad" . $ticket->prioridad;
        if (!is_null($ticket->fecha_termino) && isset($ticket->fecha_termino)) {
            $mov = $mov . " estatus: Cerrado";
        } else {
            $mov = $mov . " estatus:" . $ticket->estatus;
        }
        $mov = $mov . " datos_reporte:" . $ticket->datos_reporte . " fecha_reporte" . $ticket->fecha_reporte;
        $mov = $mov . " fecha_inicio:" . $ticket->fecha_inicio . " datos_reporte:" . $ticket->datos_reporte . " fecha_termino" . $ticket->fecha_termino;
        $mov = $mov . " problema:" . $ticket->problema . " solucion:" . $ticket->solucion . ".";
        $log->movimiento = $mov;
        $log->usuario_id = Auth::user()->id;
        $log->acciones = "Edicion";
        $log->save();
        //
        return redirect('tickets')->with(array(
            'message' => 'El Ticket se guardo Correctamente'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filtroTickets(Request $request)
    {
        $tecnicos = Tecnico::where('activo', '=', 1)->get();
        $tecnico = $request->input('tecnico_id');
        $estatus = $request->input('estatus');
        $sede = $request->sede;
        //return $sede;
        $tecnicoElegido = Tecnico::find($tecnico);

        if ((isset($tecnico) && !is_null($tecnico)) && (isset($estatus) && !is_null($estatus))) {
            if (isset($sede)) {
                $vstickets = VsTicket::where('tecnico_id', '=', $tecnico)
                    ->Where('activo', '=', 1)
                    ->Where('estatus', '=', $estatus)
                    ->where('sede', $sede)
                    ->get();
            } else {
                $vstickets = VsTicket::where('tecnico_id', '=', $tecnico)
                    ->Where('activo', '=', 1)
                    ->Where('estatus', '=', $estatus)
                    ->get();
            }
        } elseif ((isset($tecnico) && !is_null($tecnico)) && (!isset($estatus) && is_null($estatus))) {
            if (isset($sede)) {
                $vstickets = VsTicket::where('tecnico_id', '=', $tecnico)
                    ->Where('activo', '=', 1)->where('sede', $sede)
                    ->get();
            } else {
                $vstickets = VsTicket::where('tecnico_id', '=', $tecnico)
                    ->Where('activo', '=', 1)
                    ->get();
            }
        } elseif ((!isset($tecnico) && is_null($tecnico)) && (isset($estatus) && !is_null($estatus))) {
            if (isset($sede)) {
                $vstickets = VsTicket::where('estatus', '=', $estatus)
                    ->Where('activo', '=', 1)->where('sede', $sede)
                    ->get();
            } else {

                $vstickets = VsTicket::where('estatus', '=', $estatus)
                    ->Where('activo', '=', 1)
                    ->get();
            }
        } else {
            if (isset($sede)) {
                $vstickets = VsTicket::where('activo', '=', 1)->where('sede', $sede)->get();
            } else {
                $vstickets = VsTicket::where('activo', '=', 1)->get();
            }
        }
        $tickets = $this->cargarDT($vstickets);

        $id_tecnico = Tecnico::where('activo', 1)->where('user_id', Auth::user()->id)->first();
        return view('ticket.index', compact('id_tecnico'))->with('tickets', $tickets)->with('tecnicos', $tecnicos)
            ->with('tecnicoElegido', $tecnicoElegido)->with('estatus', $estatus);
    }
    
    public function recepcionEquipo($ticket_id)
    {
        $ticket = VsTicket::find($ticket_id);
        $equipoPorTickets = VsEquiposPorTicket::where('ticket_id', '=', $ticket_id)->get();
        //$cuentaEquipoPorTickets = VsEquiposPorTicket::where('ticket_id', '=', $ticket_id)->count();

        return view('ticket.agregarEquiposTicket')->with('ticket', $ticket)->with('ticket_id', $ticket_id)->with('equipoPorTickets', $equipoPorTickets);
    }

    public function registrarEquipoTicket($equipo_id, $ticket_id)
    {
        $equipoTicket = new EquipoTicket();
        $equipoTicket->ticket_id = $ticket_id;
        $equipoTicket->equipo_id = $equipo_id;
        $equipoTicket->save();
        return redirect('recepcionEquipo/' . $ticket_id)->with(array(
            'message' => 'El Equipo se agregó Correctamente al Ticket'
        ));
    }
    public function eliminarEquipoTicket($equipo_id, $ticket_id)
    {
        EquipoTicket::where('ticket_id', '=', $ticket_id)->where('equipo_id', '=', $equipo_id)->delete();
        return redirect('recepcionEquipo/' . $ticket_id)->with(array(
            'message' => 'El Equipo se agregó Correctamente al Ticket'
        ));
    }
    public function delete_ticket($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if ($ticket) {
            $ticket->activo = 0;
            $ticket->update();
            //
            $log = new Log();
            $log->tabla = "tickets";
            $mov = "";
            $mov = $mov . " area_id:" . $ticket->area_id . " solicitante:" . $ticket->solicitante . " contacto" . $ticket->contacto;
            $mov = $mov . " tecnico_id:" . $ticket->tecnico_id . " categoria:" . $ticket->categoria . " prioridad" . $ticket->prioridad;
            if (!is_null($ticket->fecha_termino) && isset($ticket->fecha_termino)) {
                $mov = $mov . " estatus: Cerrado";
            } else {
                $mov = $mov . " estatus:" . $ticket->estatus;
            }
            $mov = $mov . " datos_reporte:" . $ticket->datos_reporte . " fecha_reporte" . $ticket->fecha_reporte;
            $mov = $mov . " fecha_inicio:" . $ticket->fecha_inicio . " datos_reporte:" . $ticket->datos_reporte . " fecha_termino" . $ticket->fecha_termino;
            $mov = $mov . " problema:" . $ticket->problema . " solucion:" . $ticket->solucion . ".";
            $log->movimiento = $mov;
            $log->usuario_id = Auth::user()->id;
            $log->acciones = "Borrrado";
            $log->save();
            //
            return redirect()->route('tickets.index')->with(array(
                "message" => "El ticket se ha eliminado correctamente"
            ));
        } else {
            return redirect()->route('home')->with(array(
                "message" => "El ticket que trata de eliminar no existe"
            ));
        }
    }
    public function agregarComentario(Request $request)
    {
        $ticket_equipo = EquipoTicket::where('ticket_id', '=', $request->input('ticket_id'))->where('equipo_id', '=', $request->input('equipo_id'))->first();
        $ticket_equipo->comentarios = $request->input('comentarios');
        $ticket_equipo->update();
        return redirect('recepcionEquipo/' . $request->input('ticket_id'))->with(array(
            'message' => 'El Equipo se agregó Correctamente al Ticket'
        ));
    }
    public function historial($id)
    {
        $tickets = VsTicket::where('activo', '=', 1)->where('area_id', '=', $id)->get();
        $tecnicos = Tecnico::where('activo', '=', 1)->get();
        $tickets = $this->cargarDT($tickets);
        return view('ticket.index')->with('tickets', $tickets)->with('tecnicos', $tecnicos);
    }
    public function tomar_ticket($id, Request $request)
    {


        if (isset($request->tecnico)) {
            $tecnico =  Tecnico::select('id')->where('activo', '=', 1)->where('id', $request->tecnico)->first();
        } else {
            //return Auth::user()->id;
            $tecnico =  Tecnico::select('id')->where('activo', '=', 1)->where('user_id', Auth::user()->id)->first();
        }
        //return $tecnico;
        $total = VsTicket::where('activo', '=', 1)->where('tecnico_id', '=', $tecnico->id)->where('estatus', 'Abierto')->get();

        //return count($total);
        if (count($total) < 3 || $tecnico->id == 161) {
            $ticket = Ticket::find($id);
            $ticket->tecnico_id = $tecnico->id;
            $ticket->save();
            return redirect()->route('tickets.index')->with(array(
                'message' => 'Se asigno correctamente el ticket'
            ));
        } else {
            return redirect()->route('tickets.index')->with(array(
                "error" => "No puedes tomar tantos tickets, antes de tomar más completa los que ya tienes asignados."
            ));
        }
    }
    public function soltar_ticket($id, Request $request)
    {
        $validateData = $this->validate($request, [
            'detalle' => 'required',
            'motivo' => 'required',
        ]);
        date_default_timezone_set('America/Mexico_City');
        $historial = new ticket_historial();
        $historial->id_user = Auth::user()->id;
        $historial->id_ticket = $id;
        $historial->motivo = $request->motivo;
        $historial->detalles = $request->detalle;
        $historial->save();
        $ticket = Ticket::find($id);
        $ticket->tecnico_id  = 137;
        $ticket->save();
        return redirect()->route('tickets.index')->with(array(
            'message' => 'Se libero el ticket correctamente'
        ));
    }
}
