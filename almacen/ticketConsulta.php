<!DOCTYPE html>
<?php
 include './loginSecurity.php';
    if ($_SESSION['privilegios'] != 'Administrador' and $_SESSION['privilegios'] != 'Sistemas') {
    header('location: index.php');
}
date_default_timezone_set("America/Mexico_City");
include 'ticket.php';
$obj = new ticket();

?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Tickets. SIGE. BPEJ</title>
        <link rel="shortcut icon" href="favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Equipo de Desarrollo BPEJ">
        <!--bootstrap-->
        <link rel="stylesheet" href="css/bootstrap.css"><!-- Editado para el menu -->
        <!--jquery-->
        <script src="js/jquery-3.2.1.min.js"></script>
        <!--bootstrap-js-->
        <script src="js/bootstrap.min.js"></script>
        <!--Datatables-->
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"/><!-- Editado y menu -->
        <script type="text/javascript" src="js/jquery.dataTables.js"></script><!-- Editado -->
        <!--Datatables responsive-->
        <link rel="stylesheet" type="text/css" href="css/responsive.dataTables.min.css"/>
        <script type="text/javascript" src="js/dataTables.responsive.min.js"></script>
        <!--Datatables Buttons-->
        <link rel="stylesheet" type="text/css" href="css/buttons.dataTables.css"/><!-- Editado y menu -->
        <script type="text/javascript" src="js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="js/pdfmake.min.js"></script><!--Imprimir PDF-->
        <script type="text/javascript" src="js/vfs_fonts.js"></script><!-- Imprimir PDF-->
        <script type="text/javascript" src="js/jszip.min.js"></script><!--Imprimir Excel-->
        <script type="text/javascript" src="js/buttons.print.min.js"></script><!--Imprimir pantalla-->
        <script>
            var arregloDT = <?php echo json_encode($obj->ticketConsulta()); ?>;
            $(document).ready(function(){
                 var t = 'Listado de Cuentas';
                $('#dtPlantilla').DataTable( {
                    "data": arregloDT,
                    "order": [[ 2, "asc" ],[ 0, "desc" ]],
                    responsive: true,
                    dom: '<"col-sm-5"l><"col-sm-3"B><"col-sm-4"f>rtip',
            //                    dom: es el orden de las funciones de la tabla
            //                    l: es la lista de numero de registros que se muestran
            //                    B: son los botones para imprimir
            //                    f: es el filtro de busqueda
            //                    rt: son los registros de la tabla
            //                    i: es la información de registros
            //                    p: es la barra de paginación
                    buttons: [
                        {
                            extend: 'print',
                            title: t
                        },
                        {
                            extend: 'pdf',
                            title: t
                        },

                        {
                            extend: 'excel',
                            title: t
                        }
                    ]
                });
            });
        </script>
    </head>
    <body>
        <?php
        include 'barraMenu.php';
        $menu = new menu();
        $menu ->barraMenu();
        ?>

        <div class="container-fluid">
            <div class="page-header">
                <h3 style="text-align: center">Listado de Tickets</h3>
          </div>
             <!--tabla de consulta-->
            <div id="respuestaFiltro">
                <table id="dtPlantilla" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>IdTicket</th>
                            <th>Modificar</th>
                            <th>Estatus</th>
                            <th>Prioridad</th>
                            <th>Tiempo Acumulado</th>
                            <th>Reporte</th>
                            <th>IdEquipo</th>
                            <th>Descripción</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Núm. Serie</th>
                            <th>Fecha Reporte</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Termino</th>
                            <th>Área</th>
                            <th>Piso</th>
                            <th>Edificio</th>
                            <th>Solicitante</th>
                            <th>Solución</th>
                            <th>Técnico</th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
             <!--tabla de consulta-->
             <a href="index.php" class="btn btn-default">Salir</a>
        </div>

        <br>
    </body>
</html>
