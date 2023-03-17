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
                <h2>Captura de Técnico</h2>
                <hr>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#js-example-basic-single').select2();
                        $('#js-example-basic-single2').select2();
                        $('#equipos').select2();
                    });
                </script>

            </div>
            <div class="row">
                <form action="{{ route('tecnicos.store') }}" method="post" class="col-sm-12">
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
                                    <input class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="ciclo_inicio">Ciclo de Inicio</label>
                                    <input class="form-control" id="ciclo_inicio" name="ciclo_inicio"
                                        value="{{ old('ciclo_inicio') }} " />
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono">Teléfono de Contacto</label>
                                    <input class="form-control" id="telefono" name="telefono"
                                        value="{{ old('telefono') }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono_emergencia">Teléfono de Emergencia</label>
                                    <input class="form-control" id="telefono_emergencia" name="telefono_emergencia"
                                        value="{{ old('telefono_emergencia') }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="asistencia">Asistencia</label>
                                    <input class="form-control" id="asistencia" name="asistencia"
                                        value="{{ old('asistencia') }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="carrera">Carrera</label>
                                    <input class="form-control" id="carrera" name="carrera" value="{{ old('carrera') }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="institucion">Institución</label>
                                    <input class="form-control" id="institucion" name="institucion"
                                        value="{{ old('institucion') }} ">
                                </div>
                                <div class="col-md-6">
                                    <label for="comentarios">Programa/Comentario</label>
                                    <input class="form-control" id="comentarios" name="comentarios"
                                        value="{{ old('comentarios') }} ">
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
