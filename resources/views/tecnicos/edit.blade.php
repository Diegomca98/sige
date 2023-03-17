@extends('layouts.app')
@section('content')


    <div class="container">
        @if (Auth::check())
            @if (session('message'))
                <div class="alert alert-success">
                    <h2>{{ session('message') }}</h2>

                </div>
            @endif
            <div class="row">
                <h2>Edición de Técnico {{ $tecnico->nombre }}</h2>
                
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#usuario').select2();

                    });
                    var dateControl = document.querySelector('input[type="date"]');
                    dateControl.value = '2017-06-01';
                </script>

            </div>
            <hr>
            <div class="row">
                <form action="{{ route('tecnicos.update', $tecnico->id) }}" method="post" class="col-sm-12">
                    @method('PUT')
                    <div class="row">
                        <div class="col">
                            {!! csrf_field() !!}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <br>
                            <br>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <label for="nombre">Nombre del Técnico</label>
                                    <input class="form-control" id="nombre" name="nombre" value="{{ $tecnico->nombre }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="ciclo_inicio">Ciclo de Inicio</label>
                                    <input class="form-control" id="ciclo_inicio" name="ciclo_inicio"
                                        value="{{ $tecnico->ciclo_inicio }}" />
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono">Teléfono de Contacto</label>
                                    <input class="form-control" id="telefono" name="telefono"
                                        value="{{ $tecnico->telefono }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono_emergencia">Teléfono de Emergencia</label>
                                    <input class="form-control" id="telefono_emergencia" name="telefono_emergencia"
                                        value="{{ $tecnico->telefono_emergencia }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="asistencia">Asistencia</label>
                                    <input class="form-control" id="asistencia" name="asistencia"
                                        value="{{ $tecnico->asistencia }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="carrera">Carrera</label>
                                    <input class="form-control" id="carrera" name="carrera" value="{{ $tecnico->carrera }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="institucion">Institución</label>
                                    <input class="form-control" id="institucion" name="institucion"
                                        value="{{ $tecnico->institucion }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="comentarios">Programa/Comentario</label>
                                    <input class="form-control" id="comentarios" name="comentarios"
                                        value="{{ $tecnico->comentarios }}">
                                </div>
                                <div class="form-group row">
                                    <label for="rol" class="col-md-4 col-form-label text-md-right">Seleccioan el usuario relacionado con el técnico</label>
        
                                    <div class="col-md-6">
                                        <select class="form-control" id="usuario" name="usuario">
                                            <option disabled selected>Selecciona un usuario</option>
                                            @foreach ( $users as $item)
                                            <option value="{{$item->id}}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    
    
                            </div>
    
                            <br>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <a href="{{ route('home') }}" class="btn btn-danger">Cancelar</a>
                                    <button type="submit" class="btn btn-success">Guardar datos</button>
                                </div>
                            </div>
                        </div>
                        <br>
    
                    </div>
                </form>
            </div>

            <br>
            <div class="row g-3 align-items-center">

                <br>
                <h5 class="mob-ops-h5">En caso de inconsistencias, favor de reportarlas.</h5>
                <hr>

            </div>
    </div>
@else
    Acceso No válido
    @endif
@endsection
