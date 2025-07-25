<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Full Business Manager</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('material') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('material') }}/img/favicon.png">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    {!! Html::style('css/sweet-alert.css') !!}
    <link href="{{ asset('material') }}/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{ asset('material') }}/demo/demo.css" rel="stylesheet" />
    <!--JS BASE JQUERY-->
    <script src="{{ asset('material') }}/js/core/jquery.min.js"></script>
    {!! Html::script('js/sweet-alert.min.js') !!}
    {!! Html::script('js/general.js') !!}
    <script type="text/javascript">
    const formatter = new Intl.NumberFormat('en-NZ', {
      style: 'currency',
      currency: 'NZD',
      minimumFractionDigits: 2,
      setCurrencySymbol: ''
    });
    </script>
    </head>
    <body class="{{ $class ?? '' }} sidebar-mini">


        @if ( @$boolSinLayout != null)
          @yield('content')
        @else
          @auth()
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
              @include('layouts.page_templates.auth')
          @endauth
          @guest()
              @include('layouts.page_templates.guest')
          @endguest
        @endif


        <!-- MODAL -->
        <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"></h4>
              </div>
              <div id="myModalBody" class="modal-body">
              </div>
            </div>
          </div>
        </div>
        <!--MODAL END-->

        <!--div class="fixed-plugin">
          <div class="dropdown show-dropdown">
            <a href="#" data-toggle="dropdown">
              <i class="fa fa-cog fa-2x"> </i>
            </a>
            <ul class="dropdown-menu">
              <li class="header-title"> Color de Menú</li>
              <li class="adjustments-line">
                <a href="javascript:void(0)" class="switch-trigger active-color">
                  <div class="badge-colors ml-auto mr-auto">
                    <span class="badge filter badge-purple" data-color="purple"></span>
                    <span class="badge filter badge-azure" data-color="azure"></span>
                    <span class="badge filter badge-green" data-color="green"></span>
                    <span class="badge filter badge-warning active" data-color="orange"></span>
                    <span class="badge filter badge-danger" data-color="danger"></span>
                    <span class="badge filter badge-rose" data-color="rose"></span>
                  </div>
                  <div class="clearfix"></div>
                </a>
              </li>
              <li class="header-title">Imagen de Menú</li>
              <li class="active">
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                  <img src="{{ asset('material') }}/img/sidebar-1.jpg" alt="">
                </a>
              </li>
              <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                  <img src="{{ asset('material') }}/img/sidebar-2.jpg" alt="">
                </a>
              </li>
              <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                  <img src="{{ asset('material') }}/img/sidebar-3.jpg" alt="">
                </a>
              </li>
              <li>
                <a class="img-holder switch-trigger" href="javascript:void(0)">
                  <img src="{{ asset('material') }}/img/sidebar-4.jpg" alt="">
                </a>
              </li>
            </ul>
          </div>
        </div-->
        <!--   Core JS Files   -->


        <script src="{{ asset('material') }}/js/core/popper.min.js"></script>
        <script src="{{ asset('material') }}/js/core/bootstrap-material-design.min.js"></script>
        <script src="{{ asset('material') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <!-- Plugin for the momentJs  -->
        <script src="{{ asset('material') }}/js/plugins/moment.min.js"></script>
        <!--  Plugin for Sweet Alert -->
        <script src="{{ asset('material') }}/js/plugins/sweetalert2.js"></script>
        <!-- Forms Validations Plugin -->
        <script src="{{ asset('material') }}/js/plugins/jquery.validate.min.js"></script>
        <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
        <script src="{{ asset('material') }}/js/plugins/jquery.bootstrap-wizard.js"></script>
        <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
        <script src="{{ asset('material') }}/js/plugins/bootstrap-selectpicker.js"></script>
        <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
        <script src="{{ asset('material') }}/js/plugins/bootstrap-datetimepicker.min.js"></script>
        <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
        <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
        <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
        <script src="{{ asset('material') }}/js/plugins/bootstrap-tagsinput.js"></script>
        <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
        <script src="{{ asset('material') }}/js/plugins/jasny-bootstrap.min.js"></script>
        <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
        <script src="{{ asset('material') }}/js/plugins/fullcalendar.min.js"></script>
        <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
        <script src="{{ asset('material') }}/js/plugins/jquery-jvectormap.js"></script>
        <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
        <script src="{{ asset('material') }}/js/plugins/nouislider.min.js"></script>
        <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <!-- Library for adding dinamically elements -->
        <script src="{{ asset('material') }}/js/plugins/arrive.min.js"></script>
        <!-- Chartist JS -->
        <script src="{{ asset('material') }}/js/plugins/chartist.min.js"></script>
        <!--  Notifications Plugin    -->
        <script src="{{ asset('material') }}/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ asset('material') }}/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
        <!-- Material Dashboard DEMO methods, don't include it in your project! -->
        <script src="{{ asset('material') }}/demo/demo.js"></script>
        <script src="{{ asset('material') }}/js/settings.js"></script>

        <script type="text/javascript">

        $.validator.messages = {
          required: "El campo es necesario.",
          remote: "Ingresa correctamente el campo.",
          email: "Ingresa un correo electrónico válido.",
          url: "Ingresa una URL válida.",
          date: "Ingresa una fecha válida.",
          //dateISO: "Please enter a valid date (ISO).",
          number: "Ingresa un número válido.",
          digits: "Solo ingresa dígitos.",
          //creditcard: "Please enter a valid credit card number.",
          equalTo: "Ingresa el mismo valor.",
          //accept: "Please enter a value with a valid extension.",
          maxlength: jQuery.validator.format("Ingresa no más de {0} caracteres."),
          minlength: jQuery.validator.format("Ingresa no menos de {0} caracteres."),
          rangelength: jQuery.validator.format("Ingresa un valor entre {0} y {1} caracteres."),
          range: jQuery.validator.format("Ingresa un valor entre {0} y {1}."),
          max: jQuery.validator.format("Ingresa un valor menor o igual a {0}."),
          min: jQuery.validator.format("Ingresa un valor igual o mayor a {0}.")
        };

        //Deshabilitar acciones para consultores
        @if ( session('login_consultor') == true )
          $(".acciones").remove();
          //$("#form :input").prop("disabled", true);
        @endif

        $(document).ready(function () {
          generate_tables();
        });

        function generate_tables(){
          $(".dataTable").DataTable({
              aaSorting: [],
              autoWidth: !1,
              responsive: !0,
              lengthMenu: [[10, 30, 45, 60, -1],["10 registros", "30 registros", "45 registros", "60 registros", "Todo"]],
              language: {
                sSearch: "",
                searchPlaceholder: "Buscar en la tabla...",
                lengthMenu: "_MENU_ registros por página",
                zeroRecords: "Ningún registro encontrado",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "Sin registros",
                infoFiltered: "(Filtrados de _MAX_ total registros)",
                oPaginate: {
                  sFirst: "Primero",
                  sLast: "Último",
                  sNext: "Siguiente",
                  sPrevious: "Anterior"
                }
              },
              /*
              "language": {
              "sProcessing":    "Procesando...",
              "sLengthMenu":    "Mostrar _MENU_ registros",
              "sZeroRecords":   "No se encontraron resultados",
              "sEmptyTable":    "Ningún dato disponible en esta tabla",
              "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":   "",
              "sSearch":        "Buscar:",
              "sUrl":           "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
              "sFirst":    "Primero",
              "sLast":    "Último",
              "sNext":    "Siguiente",
              "sPrevious": "Anterior"
            },
            "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
            */

              /*dom: "Blfrtip",
              buttons: [{extend: "excelHtml5", title: "Exportar datos"}, {extend: "csvHtml5", title: "Exportar datos"}, {extend: "print", title: 'Archivo' }],
              initComplete: function (a, b)
              {
                $(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions">\n\
                <span class="actions__item zmdi zmdi-print" data-table-action="print" />\n\
                <span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" />\n\
                <div class="dropdown actions__item">\n\
                <i data-toggle="dropdown" class="zmdi zmdi-download" />\n\
                <ul class="dropdown-menu dropdown-menu-right">\n\
                <a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a>\n\
                <a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')
              }*/
            });
        }
        function imprimir()
        {
          window.load = function (){

            window.print();

          }();

        }
        </script>
        @stack('js')
    </body>
    @include('common.mensajes')
</html>
