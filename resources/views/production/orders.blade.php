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

        <!-- Grid Section -->
    <div id="followUpGridSection">
        <!-- Alerta de selección -->
        <div id="messageBox" style="display: none; color: red; font-weight: bold; margin-top: 10px;"></div>
        <!-- Toolbar -->
        <div class="d-flex flex-column mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- Título del listado -->
                <h4 class="mb-0 ms-3">List of orders</h4>
                <!-- Filtros -->
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmittedFirst" id="is_transmittedFirst" data-field="Commande_Transmit_First" value="transmittedFirst" checked>
                        <label class="form-check-label" for="is_transmittedFirst">Order preparation</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_transmitted" id="is_transmitted" data-field="Transmit" value="transmitted" checked>
                        <label class="form-check-label" for="is_transmitted">Order approval</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_complete" id="is_complete" data-field="Credit_Autorise" value="completed" checked>
                        <label class="form-check-label" for="is_complete">Authorized credit</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_canceled" id="is_canceled" data-field="isReady_Production" value="cancelled" checked>
                        <label class="form-check-label" for="is_canceled">Ready to produce</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_blocked" id="is_blocked" data-field="IsCompletedLogic" value="barred">
                        <label class="form-check-label" for="is_blocked">Complet</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-filter" type="checkbox" name="is_blocked" id="is_blocked" data-field="IsCanceledLogic" value="barred">
                        <label class="form-check-label" for="is_blocked">Cancel</label>
                    </div>
                </div>
            <!-- Botones -->
                <x-permission-users :allowed-roles="['Thomas Admin']">
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" id="btnNew" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Nouvelle commande (New Order)">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                        <button type="button" id="btnEdit" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Modifier commande (Edit Order)">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" id="btnDuplicate" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Dupliquer commande (Duplicate)">
                            <i class="bi bi-files"></i>
                        </button>
                        <button type="button" id="btnRefrash" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Actualiser la liste (Refresh)">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button type="button" id="btnFollowUps" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Consulter les suivis (Check Follow-Ups)">
                            <i class="bi bi-search"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded shadow-sm" data-bs-toggle="tooltip" title="Etiquetas" onclick="openLabelsModal()">
                            <i class="bi bi-tag"></i>
                        </button>
                    </div>
                </x-permission-users>                
            </div>
        </div>
        <!-- Grid principal -->
        <div id="commandesGrid"></div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert for alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global machine data -->
    <script>const isAdmin = @json(Auth::user()?->fonction?->Fonction_Desc === 'Thomas Admin');</script>

    <!-- Import JS modules -->
    <script type="module" src="/assets/js/config.js"></script>
    <script type="module" src="/assets/js/commandes.js"></script>
@endpush
