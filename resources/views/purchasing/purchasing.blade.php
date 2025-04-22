@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')

    <div class="pagetitle">
        <h1>Purchasing</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active"><a href="/supply_purchasing/index">Supply Purchasing</a></li>
                <li class="breadcrumb-item active">Purchasing</li>
            </ol>
        </nav>
    </div>

    
    <!-- Grid con compras activas -->
    <section class="section">
        <h4>Active Purchase Orders</h4>
        <div id="grid"></div>
    </section>

@endsection
