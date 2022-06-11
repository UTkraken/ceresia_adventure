let reloadTimeId = null;
let trailsTable;

$(document).ready(function () {
    const $trailTable = $('#trail-table'),
        data_trail = $trailTable.data('trails'),
        $search = $('#trailSearch');

    trailsTable = $trailTable.DataTable(
        {
            ordering:false,
            processing: true,
            serverSide: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            },
            ajax: {
                url: "/ParcoursCreateur/get4gridTrails",
                type: "POST",
                data: function (d) {
                    d.name = $search.val();
                }
            },
            deferLoading: data_trail['recordsFiltered'],
            data: data_trail['data'],
            paging: false,
            scrollY: '500px',
            scrollCollapse: true,
            searching: false,
            columns: [
                {data: "name"},
                {data: "dateEnd"},
                {data: "estimatedTime"},
                {data: "level"},
                {data: "nbEnigmas"},
                {data: "rating"},
                {data: "test"},
                {data: "actions"}
            ]
        }
    );
    $search.on('keyup', function () {
        // Annule le timer du précédent refresh de la dataTable
        // Pour cancel le refresh tant qu'on écrit
        clearTimeout(reloadTimeId);
        // Met un couldown pour le lancement du refresh de la dataTable
        reloadTimeId = setTimeout(function () {
            trailsTable.ajax.reload();
        }, 500); //0.5s
    });
});

function visible($btn) {
    $.ajax({
        url: "/ParcoursCreateur/visible",
        method: 'POST',
        dataType: 'json',
        data: {
            id: $btn.data('id'),
            visible: $btn.data('visible'),
        },
        success: function () {
            // fix le tooltip qui reste au refresh
            $('.tooltip').remove();
            trailsTable.ajax.reload();
        }
    })
}

function remove($btn) {
    $.ajax({
        url: "/ParcoursCreateur/remove",
        method: 'POST',
        dataType: 'json',
        data: {
            id: $btn.data('id')
        },
        success: function () {
            // fix le tooltip qui reste au refresh
            $('.tooltip').remove();
            trailsTable.ajax.reload();
        }
    })
}
