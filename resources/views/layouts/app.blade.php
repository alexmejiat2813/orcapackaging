<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Orca Packaging')</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" type="text/css" />
    <link rel="stylesheet" href="/assets/vendor/boxicons/css/boxicons.min.css" type="text/css" />
    <link rel="stylesheet" href="/assets/vendor/quill/quill.snow.css" type="text/css" />
    <link rel="stylesheet" href="/assets/vendor/quill/quill.bubble.css" type="text/css" />
    <link rel="stylesheet" href="/assets/vendor/remixicon/remixicon.css" type="text/css" />
    <link rel="stylesheet" href="/assets/vendor/simple-datatables/style.css" type="text/css" />
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.base.css" type="text/css" />

    <!-- jqWidgets CSS -->
    <link rel="stylesheet" href="/assets/jqwidgets/styles/jqx.base.css" type="text/css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jqWidgets Core & Basic -->
    <script src="/assets/jqwidgets/jqxcore.js"></script>
    <script src="/assets/jqwidgets/jqxbuttons.js"></script>
    <script src="/assets/jqwidgets/jqxscrollbar.js"></script>
    <script src="/assets/jqwidgets/jqxmenu.js"></script>
    <script src="/assets/jqwidgets/jqxdata.js"></script>

    <!-- jqxGrid and Features -->
    <script src="/assets/jqwidgets/jqxgrid.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.selection.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.columnsresize.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.pager.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.filter.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.sort.js"></script>
    <script src="/assets/jqwidgets/jqxgrid.edit.js"></script>

    <!-- Dropdowns and Lists -->
    <script src="/assets/jqwidgets/jqxlistbox.js"></script>
    <script src="/assets/jqwidgets/jqxdropdownlist.js"></script>
    <script src="/assets/jqwidgets/jqxcheckbox.js"></script>

    <!-- Date and Time -->
    <script src="/assets/jqwidgets/jqxdatetimeinput.js"></script>
    <script src="/assets/jqwidgets/jqxcalendar.js"></script>

    <!-- Popup Windows -->
    <script src="/assets/jqwidgets/jqxwindow.js"></script>
    <script src="/assets/jqwidgets/jqxdragdrop.js"></script>
    <script src="/assets/jqwidgets/jqxinput.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">

    @include('layouts.header') <!-- Global header -->

    <main id="main" class="main flex-grow-1">
        @yield('content') <!-- Page-specific content -->
    </main>

    @include('layouts.footer') <!-- Global footer -->

    @stack('scripts')

    <!-- Vendor Scripts -->
    <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="/assets/vendor/echarts/echarts.min.js"></script>
    <script src="/assets/vendor/quill/quill.min.js"></script>
    <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="/assets/vendor/php-email-form/validate.js"></script>
    <script src="/assets/js/main.js"></script>

    <!-- Prevent browser back navigation and cache after logout -->
    <script>
        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, location.href);
            window.onpopstate = function () {
                window.history.pushState(null, null, location.href);
            };
        }

        window.addEventListener("pageshow", function (event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                location.reload();
            }
        });
    </script>

</body>
</html>
