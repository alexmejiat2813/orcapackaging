@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Ink Formulation</h2>

    <!-- Grid for Ink Formulas -->
 
    

    <form id="formulationForm" class="card p-4 shadow-sm">
        <div class="row mb-4">
    <div class="col-md-8">
        <label for="finalColor" class="form-label">Final Color</label>
        <input type="text" class="form-control" id="finalColor" placeholder="ex. Rouge 485 avec Encre Sun Chemical">
    </div>
    <div class="col-md-4">
        <label for="totalKg" class="form-label">Total Quantity Needed (kg)</label>
        <input type="number" class="form-control" id="totalKg" value="0" step="0.01">
    </div>
</div>

        <div class="table-responsive">
            <table class="table align-middle" id="componentsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40%">Component</th>
                        <th style="width: 25%">Amount (kg)</th>
                        <th style="width: 25%">Calculated Percentage (%)</th>
                        <th style="width: 10%"></th>
                    </tr>
                </thead>
                <tbody>
                   @for ($i = 0; $i < 2; $i++)
                        <tr>
                            <td>
                                <select class="form-select component-name">
                                    <option value="">Select a product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->Product_ID }}">{{ $product->PrNumber . ' | ' . $product->PrDescription1 }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" class="form-control component-kg" step="0.01" value="0"></td>
                            <td class="result-percent">-</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">×</button></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

       <div class="d-flex justify-content-between mt-3">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary btn-sm  shadow-sm" id="addRow" title="Add Component">
            <i class="bi bi-plus-square"></i>
        </button>
        <button type="button" class="btn btn-outline-primary btn-sm  shadow-sm" id="calculate" title="Calculate Percentages">
            <i class="bi bi-calculator"></i>
        </button>
    </div>

    <button type="submit" class="btn btn-outline-success btn-sm rounded shadow-sm" id="saveFormula" title="Save Formula">
        <i class="bi bi-save2"></i>
    </button>
</div>

    </form>

    <div id="dataTable" class="mb-4"></div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="/assets/jqwidgets/jqxdatatable.js"></script>
<script>

document.getElementById('addRow').addEventListener('click', () => {
    const table = document.getElementById('componentsTable').getElementsByTagName('tbody')[0];
    const row = table.insertRow();

    const productsOptions = `
        <option value="">Select a product</option>
        @foreach($products as $product)
            <option value="{{ $product->Product_ID }}">{{ $product->PrNumber . ' | ' . $product->PrDescription1 }}</option>
        @endforeach
    `;

    row.innerHTML = `
        <td><select class="form-select component-name">${productsOptions}</select></td>
        <td><input type="number" class="form-control component-kg" step="0.01" value="0"></td>
        <td class="result-percent">-</td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">×</button></td>
    `;
});

document.getElementById('componentsTable').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});

document.getElementById('calculate').addEventListener('click', () => {
    const totalKg = parseFloat(document.getElementById('totalKg').value);
    const rows = document.querySelectorAll('#componentsTable tbody tr');

    rows.forEach(row => {
        const kg = parseFloat(row.querySelector('.component-kg').value);
        const resultCell = row.querySelector('.result-percent');
        if (!isNaN(kg) && totalKg > 0) {
            const percent = (kg / totalKg * 100).toFixed(2);
            resultCell.textContent = percent + ' %';
        } else {
            resultCell.textContent = '-';
        }
    });
});

function showTooltip(input, message) {
    input.classList.add('is-invalid');
    input.setAttribute('data-bs-toggle', 'tooltip');
    input.setAttribute('data-bs-placement', 'top');
    input.setAttribute('title', message);
    bootstrap.Tooltip.getOrCreateInstance(input).show();
}

function clearTooltips() {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        el.classList.remove('is-invalid');
        bootstrap.Tooltip.getInstance(el)?.dispose();
        el.removeAttribute('title');
    });
}

document.getElementById('formulationForm').addEventListener('submit', function (e) {
    e.preventDefault();
    clearTooltips();

    const finalColor = document.getElementById('finalColor');
    const totalKgInput = document.getElementById('totalKg');
    const totalKg = parseFloat(totalKgInput.value);
    const rows = document.querySelectorAll('#componentsTable tbody tr');

    let validComponents = 0;
    let sumPercent = 0;
    let hasError = false;
    let components = [];

    if (!finalColor.value.trim()) {
        showTooltip(finalColor, 'Final color is required.');
        hasError = true;
    }

    if (totalKg <= 0) {
        showTooltip(totalKgInput, 'Quantity must be greater than 0.');
        hasError = true;
    }

    rows.forEach(row => {
        const select = row.querySelector('.component-name');
        const productId = select.value;
        const kgInput = row.querySelector('.component-kg');
        const kg = parseFloat(kgInput.value);
        const percentText = row.querySelector('.result-percent').textContent.replace('%', '').trim();
        const percent = parseFloat(percentText);

        if (productId && !isNaN(kg) && kg > 0 && !isNaN(percent)) {
            validComponents++;
            sumPercent += percent;
            components.push({ product_id: productId, kg: kg, percent: percent });
        } else if (kg > 0) {
            showTooltip(kgInput, 'Click Calculate to compute %');
            hasError = true;
        }
    });

    if (validComponents < 2) {
        rows.forEach(row => {
            const kgInput = row.querySelector('.component-kg');
            if (parseFloat(kgInput.value) <= 0) {
                showTooltip(kgInput, 'At least 2 valid components required.');
            }
        });
        hasError = true;
    }

    if (Math.round(sumPercent) !== 100) {
        rows.forEach(row => {
            const percentCell = row.querySelector('.result-percent');
            if (percentCell.textContent !== '-') {
                percentCell.classList.add('text-danger');
            }
        });
        //alert('⚠️ Total percentage must equal 100%.');
        hasError = true;
    } else {
        rows.forEach(row => row.querySelector('.result-percent').classList.remove('text-danger'));
    }

    if (!hasError) {
    fetch('/inventory/formule-inks/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            final_color: finalColor.value.trim(),
            total_kg: totalKg,
            components: components
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log('🔎 Server response:', data); // 👈 Agrega esto
        if (data.success) {
            alert('✅ Formula saved successfully!');
            document.getElementById('formulationForm').reset();
            // Limpiar los porcentajes calculados
    document.querySelectorAll('.result-percent').forEach(cell => {
        cell.textContent = '-';
    });
    // 🔄 Recargar la tabla de fórmulas
            $("#dataTable").jqxDataTable('updateBoundData');
        } else {
            alert('⚠️ Error saving formula.');
        }
    })
    .catch(() => alert('⚠️ Server error.'));
}

});

    $(document).ready(function () {
        const source = {
            dataType: "json",
            dataFields: [
                { name: 'FinalColor', type: 'string' },
                { name: 'TotalKg', type: 'number' },
                { name: 'ProductCode', type: 'string' },
                { name: 'ProductName', type: 'string' },
                { name: 'Kg', type: 'number' },
                { name: 'Percent', type: 'number' }
            ],
            url: "/inventory/formule-inks/list"
        };

        const dataAdapter = new $.jqx.dataAdapter(source);

        $("#dataTable").jqxDataTable({
            source: dataAdapter,
            height : 275,
            //pageable: true,
            sortable: true,
            filterMode: "simple", filterable: true,
            width: '100%',
            altRows: true,
            //columnsResize: true,
            groups: ['FinalColor'],
            columns: [
                { text: 'Final Color', dataField: 'FinalColor', align: 'center', hidden: true },
                { text: 'Product Code', dataField: 'ProductCode', align: 'center',width: 300 },
                { text: 'Product Name', dataField: 'ProductName', align: 'center', width: 600 },
                { text: 'Amount (kg)', dataField: 'Kg', width: 125, cellsAlign: 'right', align: 'center' },
                { text: 'Percentage (%)', dataField: 'Percent', width: 125, cellsAlign: 'right', align: 'center' },
                { text: 'Total Formula (kg)', dataField: 'TotalKg', width: 126, cellsAlign: 'right', align: 'center' }
            ]
        });
    });
</script>

@endpush

