<!DOCTYPE html>
<html lang="es">
    <head>

        <meta charset="UTF-8">
        <title>@yield('title', 'Orca Packaging')</title>

        <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
        <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
        <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">
        <link href="/assets/css/style.css" rel="stylesheet">

         <!-- ✅ jqWidgets CSS -->
        <link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.base.css" type="text/css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- ✅ Núcleo -->
        <script src="/assets/jqwidgets/jqxcore.js"></script>

        <!-- ✅ Funcionalidad básica -->
        <script src="/assets/jqwidgets/jqxbuttons.js"></script>
        <script src="/assets/jqwidgets/jqxscrollbar.js"></script>
        <script src="/assets/jqwidgets/jqxmenu.js"></script>
        <script src="/assets/jqwidgets/jqxdata.js"></script>

        <!-- ✅ jqxGrid y funcionalidades -->
        <script src="/assets/jqwidgets/jqxgrid.js"></script>
        <script src="/assets/jqwidgets/jqxgrid.selection.js"></script>
        <script src="/assets/jqwidgets/jqxgrid.columnsresize.js"></script>
        <script src="/assets/jqwidgets/jqxgrid.pager.js"></script>
        <script src="/assets/jqwidgets/jqxgrid.filter.js"></script>
        <script src="/assets/jqwidgets/jqxgrid.sort.js"></script>
        <script src="/assets/jqwidgets/jqxgrid.edit.js"></script>

        <!-- ✅ Dropdowns y listas para filtros -->
        <script src="/assets/jqwidgets/jqxlistbox.js"></script>
        <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
        <script src="/assets/jqwidgets/jqxcheckbox.js"></script>

        <!-- ✅ Fecha y hora -->
        <script src="/assets/jqwidgets/jqxdatetimeinput.js"></script>
        <script src="/assets/jqwidgets/jqxcalendar.js"></script>

        <!-- ✅ Popup window (formulario embebido) -->
        <script src="/assets/jqwidgets/jqxwindow.js"></script>
        <script src="/assets/jqwidgets/jqxdragdrop.js"></script>
        <script src="/assets/jqwidgets/jqxinput.js"></script>
    </head>
    <body class="d-flex flex-column min-vh-100">

        @include('layouts.header')  <!-- ← Inserta el header -->

        <main id="main" class="main flex-grow-1">
            @yield('content')       <!-- ← Aquí se "inyecta" el contenido de cada vista -->
        </main>

        @include('layouts.footer')  <!-- ← Inserta el footer -->

        @stack('scripts')

        <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
        <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/vendor/chart.js/chart.umd.js"></script>
        <script src="/assets/vendor/echarts/echarts.min.js"></script>
        <script src="/assets/vendor/quill/quill.min.js"></script>
        <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
        <script src="/assets/vendor/tinymce/tinymce.min.js"></script>
        <script src="/assets/vendor/php-email-form/validate.js"></script>
        <script src="/assets/js/main.js"></script>

    </body>
</html>
