@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>Request</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active"><a href="/purchasing/index">Purchasing</a></li>
                <li class="breadcrumb-item active">Request</li>
            </ol>
        </nav>
        <button id="openForm" class="btn btn-primary mb-3">Nueva Solicitud</button>
        <button id="syncJotformBtn" class="btn btn-outline-primary mb-3">🔄 Sync with JotForm</button>
    <div id="syncStatus" style="font-weight:bold;"></div>

    </div>
        <!-- Popup Formulario oculto inicialmente -->
        <div id="popupForm" style="display: none;">
        <iframe
            id="JotFormIFrame-250704767667064"
            title="Demande de fournitures"
            width="100%" 
            height="700px"
            allowtransparency="true"
            allow="geolocation; microphone; camera; fullscreen"
            src="https://form.jotform.com/250704767667064"
            frameborder="0"
            scrolling="yes"
        >
        </iframe>
    </div>

    <!-- Grid con solicitudes de JotForm -->
    <section class="section mt-5">
        <h4>JotForm Submissions</h4>
        <div id="gridJotform"></div>
    </section>

@endsection

@push('scripts')
    <script> const supplyData = @json($requests); </script>
    <script> const jotformListUrl = "{{ url('/purchasing/requests/list') }}"; </script>
    <script src="/assets/js/suppliesRequest.js?v={{ time() }}"></script>

@endpush
