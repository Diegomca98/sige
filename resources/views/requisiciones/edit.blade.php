@extends('layouts.app')

@section('content')
    <div class="container">


        @if (Auth::check() &&
            (Auth::user()->role == 'admin' ||
                Auth::user()->role == 'cta' ||
                Auth::user()->role == 'auxiliar' ||
                Auth::user()->role == 'redes'))
            <div class="row">
                <div class="row g-3 align-items-center">
                    <div class="col-md-12">
                        <h2>Editar requisición</h2>
                    </div>
                </div>
            </div>

            <form action="{{ route('requisiciones.update', $requisicion->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label for="" class="form-label">Número de solicitud</label>
                        <input type="text" name="num_sol" id="num_sol" value="{{ $requisicion->num_sol }}"
                            class="form-control" tabindex="1">
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-label">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control"
                            value="{{ $requisicion->fecha }}" tabindex="2">
                    </div>
                    <div class="col-md-4">
                        <label for="" class="form-label">Usuario</label>
                        <input type="text" name="user" id="user" class="form-control"
                            value="{{ $requisicion->user }}" tabindex="3" readonly>
                    </div>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label for="" class="form-label">Proyecto</label>
                        <input type="text" name="proyecto" id="proyecto" class="form-control"
                            value="{{ $requisicion->proyecto }}" tabindex="4">
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Fondo</label>
                        <input type="text" name="fondo" id="fondo" class="form-control"
                            value="{{ $requisicion->fondo }}" tabindex="5">
                    </div>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label for="" class="form-label">Fecha recibido</label>
                        <input type="date" name="fecha_recibido" id="v" class="form-control" tabindex="6" value="{{$requisicion->fecha_recibido}}"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Nombre quien recibe</label>
                        <input type="text" name="quien_recibe" id="quien_recibe" class="form-control" tabindex="7" value="{{$requisicion->quien_recibe}}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Adjuntar requisición</label>
                        <input type="file" name="pdf" id="pdf" class="form-control" tabindex="8">
                    </div>
                    <div class="col-md-6">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select name="estatus" id="estatus" class="form-control">
                            <option {{($requisicion->estatus == 'En tramite')? 'selected':''}} value="En tramite">En tramite</option>
                            <option {{($requisicion->estatus == 'Entregado')? 'selected':''}} value="Entregado">Entregado</option>
                            <option {{($requisicion->estatus == 'Rechazado')? 'selected':''}} value="Rechazado">Rechazado</option>
                            <option {{($requisicion->estatus == 'Entrega Parcial')? 'selected':''}} value="Entrega Parcial">Entrega Parcial</option>
                        </select>
                    </div>
                </div><br><br>
                <div class="row justify-content-end">
                    <div class="col-sm-6 col-md-2">
                        <button type="submit" class="btn btn-primary" tabindex="9">Guardar </button>
                    </div>
                </div>
    </form>

    <br>
    <div class="row g-3 align-items-center">
        <br>
        <h5 class="mob-ops-h5">En caso de inconsistencias, favor de reportarlas.</h5>
        <hr>

    </div>
    </div>
    @endif
@endsection
