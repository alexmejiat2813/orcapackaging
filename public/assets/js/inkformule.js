// public/js/inkformula/manage.js
$(document).ready(function () {
    let selectedFormulaId = null;

    const formulaSource = {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'int' },
            { name: 'name', type: 'string' },
            { name: 'product_name', type: 'string' },
            { name: 'total_quantity', type: 'number' }
        ],
        url: '/ink-formula/list'
    };

    const formulaAdapter = new $.jqx.dataAdapter(formulaSource);

    $('#inkFormulaGrid').jqxGrid({
        width: '100%',
        autoheight: true,
        source: formulaAdapter,
        pageable: true,
        columnsresize: true,
        selectionmode: 'singlerow',
        columns: [
            { text: 'ID', datafield: 'id', width: '10%' },
            { text: 'Name', datafield: 'name', width: '30%' },
            { text: 'Base Product', datafield: 'product_name', width: '30%' },
            { text: 'Total Quantity (kg)', datafield: 'total_quantity', width: '30%' }
        ]
    });

    $('#inkFormulaGrid').on('rowselect', function (event) {
        selectedFormulaId = event.args.row.id;
        loadComponents(selectedFormulaId);
        $('#ink_formula_id').val(selectedFormulaId);
    });

    function loadComponents(formulaId) {
        const componentSource = {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int' },
                { name: 'product_name', type: 'string' },
                { name: 'percentage', type: 'number' }
            ],
            url: `/ink-formula/${formulaId}/components`
        };

        const componentAdapter = new $.jqx.dataAdapter(componentSource);

        $('#inkComponentGrid').jqxGrid({
            width: '100%',
            autoheight: true,
            source: componentAdapter,
            pageable: true,
            columnsresize: true,
            selectionmode: 'singlerow',
            columns: [
                { text: 'ID', datafield: 'id', width: '20%' },
                { text: 'Product', datafield: 'product_name', width: '50%' },
                { text: 'Percentage (%)', datafield: 'percentage', width: '30%' }
            ]
        });
    }

    // TODO: Add form open/edit logic and AJAX post for create/update/delete
});
