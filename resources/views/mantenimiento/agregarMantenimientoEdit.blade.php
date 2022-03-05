
@extends('layouts.app')
@section('content')


    <div class="container">
        @if(Auth::check())
            @if (session('message'))
                <div class="alert alert-success">
                    <h4>{{ session('message') }}</h4>

                </div>
            @endif
            
            <div class="row">
                <h2>Edición de Mantenimiento. Folio: {{$vsmantenimiento->id}}</h2>
                <hr>
               <script type="text/javascript">

                    $(document).ready(function() {
                        $('#js-example-basic-single').select2();

                    });

                </script>

            </div>
        <div class="row">
            <table class="table table-success" style="width:100%">
                    <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Solicitante</th>
                        <th>Contacto</th>
                        <th>&Aacuterea</th>
                        <th>Fecha de Mantenimiento</th>
        
                    </tr>
                    </thead>
                    <tbody>
                    <tr>

                        <td>{{$vsmantenimiento->id}}</td>
                        <td>{{$vsmantenimiento->nombre}}</td>
                        <td>{{$vsmantenimiento->telefono}}</td>
                        <td>{{$vsmantenimiento->sede. ' - ' . $vsmantenimiento->edificio. ' - '. $vsmantenimiento->piso. ' - '. $vsmantenimiento->division. ' - '. $vsmantenimiento->coordinacion. ' - '. $vsmantenimiento->area}}</td>
                        <td>{{$vsmantenimiento->fecha_mantenimiento}}</td>

                    </tr>
                    </tbody>
                </table>
            

                        <h5><p align="center">Mantenimiento ya Registrado</p></h5>
                        
                <table class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>ID Equipo</th>
                        <th>Id UdeG</th>
                        <th>Tipo Equipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Núm. Serie</th>
                        <th>Detalles</th>
                        <th>ID Área</th>
                       <th>Terminado</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($equipos_en_este_mantenimiento as $equipoactual)
                        <tr>
                            <td>{{$equipoactual->id_equipo}}</td>
                            <td>{{$equipoactual->udg_id}}</td>
                            <td>{{$equipoactual->tipo_equipo}}</td>
                            <td>{{$equipoactual->marca}}</td>
                            <td>{{$equipoactual->modelo}}</td>
                            <td>{{$equipoactual->numero_serie}}</td>
                            <td>{{$equipoactual->detalles}}</td>
                            <td>{{$equipoactual->area_id}}</td>

                            <td>
                                @if ($equipoactual->terminado)
                                    <a href="{{route('estadoMantenimiento', [$vsmantenimiento->id, $equipoactual->id_equipo])}}"  class="btn btn-outline-success">Terminado</a>
                                @else
                                    <a href="{{route('estadoMantenimiento', [$vsmantenimiento->id, $equipoactual->id_equipo])}}"  class="btn btn-outline-danger">Sin terminar </a>
                                @endif
                                <br>
                                <a href="{{route('eliminarequipomantenimiento', [$vsmantenimiento->id, $equipoactual->id_equipo])}}" class="btn btn-outline-danger">Quitar</a>
                                
                            </td>
                        </tr>
                        
                    @endforeach 

                    </tbody>
                </table>
        </div>
        <div class=row>
            <form action="{{route('busquedaEquiposMantenimiento')}}" method="POST" enctype="multipart/form-data" class="col-12">
                {!! csrf_field() !!}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            Debe de escribir un criterio de búsqueda
                        </ul>
                    </div>
                @endif
                <br>
                <div class="row g-3 align-items-center">
                    <div class="col-md-2">
                        <label>Búsqueda</label>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" id="busqueda" name="busqueda" >
                        <input type="hidden" class="form-control" id="id" name="id" value="{{$vsmantenimiento->id}}" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success">Buscar</button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('mantenimiento.create') }}" class="btn btn-outline-success">Capturar Mantenimiento</a>
                    </div>
                </div>
                <br>
            </form>
        </div>
        

                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>Id UdeG</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Núm. Serie</th>
                        <th>Detalles</th>
                        <th>Área</th>
                        <th>Tipo Equipo</th>
                        <th>Acciones</th>

                    </tr>
                    </thead>
                    <tbody>

                    @if(isset($equipos))
                        @foreach($equipos as $equipo)
                            <tr>
                                <td>
                                    {{$equipo->udg_id}}
                                </td>
                                <td>{{$equipo->marca}}</td>
                                <td>{{$equipo->modelo}}</td>
                                <td>{{$equipo->numero_serie}}</td>
                                <td>{{$equipo->detalles}}</td>
                                <td>{{$equipo->area}}</td>
                                <td>{{$equipo->tipo_equipo}}</td>
                                <td><p><a href="{{route('agregarequipomantenimiento', [$vsmantenimiento->id, $equipo->id])}}" class="btn btn-outline-success">Agregar</a></p></td>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>

                </table>

        </div>
        <div class="row">
                <br>
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <a href="{{ route('home') }}" class="btn btn-danger">Cancelar</a>
                           
                        </div>
                    </div>
                </div>
            
            <br>
            <div class="row g-3 align-items-center">

                <br>
                <h5>En caso de inconsistencias, favor de reportarlas a victor.ramirez@academicos.udg.mx</h5>
                <hr>

            </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>


    <script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>

    <script>


        $(document).ready(function() {
            $('#example').DataTable( {
                "pageLength": 100,
                "order": [[ 0, "asc" ]],
                "language": {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel',
                    {
                        extend:'pdfHtml5',
                        orientation: 'landscape',
                        pageSize:'LETTER',
                    }

                ]
            } );
        } );
    </script>
    @else
        El periodo de Registro de Proyectos a terminado
    @endif

   
@endsection
