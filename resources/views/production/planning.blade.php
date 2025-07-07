@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')



<!-- Page Title and Breadcrumb -->
<div class="pagetitle">
    <h1>Production Planning</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Production Planning</li>
        </ol>
    </nav>
</div>

<!-- Follow-Up Grid Section -->
<div id="followUpGridSection">
    <div id="messageBox" style="display: none; color: red; font-weight: bold; margin-top: 10px;"></div>
    <div class="d-flex flex-column mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0 ms-3">Schedule orders</h4>
            <x-permission-users :allowed-roles="['Thomas Admin']">
                <div class="d-flex gap-2 align-items-center">
                    <button id="abrirModal" type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Orders">
                        <i class="bi bi-tag"></i>
                    </button>
                </div>
            </x-permission-users>
        </div>
    </div>
            <!-- Scheduler Section -->
        <div id="scheduler"></div>
        <div id="messageBox"></div>
</div>

<!-- Hidden Follow-Up Form Section -->
<div id="followUpFormSection" style="display: none;">
    <div class="d-flex flex-column mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0 ms-3">Schedule orders</h4>
            <x-permission-users :allowed-roles="['Thomas Admin']">
                <div class="d-flex gap-2 align-items-center">
                    <!-- Sync button -->
                    <button type="button" id="syncButton" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Synchronize information">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </x-permission-users>
        </div>
    </div>
    <div id="jqxTable"></div>
</div>
@endsection

@push('styles')
<style>
  .modal-extra-large {
    max-width: 90vw;
    max-height: 90vw;
  }
</style>
@endpush

@push('scripts')

<!-- Global machine data -->
<script>const machineData = @json($machines);</script>

<!-- Import JS modules -->
<script type="module" src="/assets/js/config.js"></script>
<script type="module" src="/assets/js/scheduler.js"></script>
@endpush
