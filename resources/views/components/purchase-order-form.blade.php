<form id="purchaseOrderForm">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Purchase Order Form</h5>
            <div class="mt-4 d-flex justify-content-end gap-2 mb-3">
                <button type="button" id="btnSave" class="btn btn-primary btn-sm rounded shadow-sm">Save</button>
                <button type="button" id="btnCancel" class="btn btn-secondary btn-sm rounded shadow-sm">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
            </div>
        </div>

        <div class="row gx-3">
            <!-- Columna 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="bg-light p-2 border rounded small">
                    @include('components.purchase-order-columns.column1')
                </div>
            </div>

            <!-- Columna 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="bg-light p-2 border rounded small">
                    @include('components.purchase-order-columns.column2')
                </div>
            </div>

            <!-- Columna 3 -->
            <div class="col-lg-4 col-md-12">
                <div class="bg-light p-2 border rounded small">
                    @include('components.purchase-order-columns.column3')
                </div>
            </div>
        </div>

        <div class="mt-4">
            <label class="form-label fw-bold">Note</label>
            <textarea class="form-control" rows="4" style="white-space: pre-wrap;"></textarea>
        </div>

        <div class="mt-4">
            <div id="productDetailGrid"></div>
        </div>
    </div>
</form>
