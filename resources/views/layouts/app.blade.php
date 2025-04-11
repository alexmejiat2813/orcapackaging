<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Orca Packaging')</title>

    {{-- Bootstrap desde CDN (opcional) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS personalizados --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    {{-- jqxWidgets CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/jqwidgets/styles/jqx.base.css') }}">
</head>
<body>
    @yield('content')

    {{-- jQuery y Bootstrap --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jqxWidgets --}}
    <script src="{{ asset('assets/jqwidgets/jqxcore.js') }}"></script>
    <script src="{{ asset('assets/jqwidgets/jqxdata.js') }}"></script>
    <script src="{{ asset('assets/jqwidgets/jqxgrid.js') }}"></script>
    <script src="{{ asset('assets/jqwidgets/jqxgrid.sort.js') }}"></script>
    <script src="{{ asset('assets/jqwidgets/jqxgrid.filter.js') }}"></script>
    <script src="{{ asset('assets/jqwidgets/jqxbuttons.js') }}"></script>
    <script src="{{ asset('assets/jqwidgets/jqxwindow.js') }}"></script>

    {{-- JS personalizados --}}
    <script src="{{ asset('assets/js/suppliesRequest.js') }}"></script>
</body>
</html>
