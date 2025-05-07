@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>Production Jobs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Production Jobs</li>
            </ol>
        </nav>
    </div>

    <section class="section mt-4">
        <div id="commandesGrid"></div>
    </section>
@endsection

@push('scripts')
    <!-- SweetAlert for alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global machine data -->
    <script>const isAdmin = @json(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif');</script>

    <!-- Import JS modules -->
    <script type="module" src="/assets/js/config.js"></script>
    <script type="module" src="/assets/js/commandes.js"></script>
@endpush
