let reloadTimeId = null;
let enigmasTable;

$(document).ready(function () {
    const $enigmaTable = $('#enigma-table'),
        data_enigmas = $enigmaTable.data('enigmas'),
        $search = $('#enigmaSearch');
    enigmasTable = $enigmaTable.DataTable(
        {
            ordering:false,
            processing: true,
            serverSide: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            },
            ajax: {
                url: "/enigma/get4gridEnigmas",
                type: "POST",
                data: function (d) {
                    d.name = $search.val();
                }
            },
            deferLoading: data_enigmas['recordsFiltered'],
            data: data_enigmas['data'],
            paging: false,
            searching: false,
            scrollY: '500px',
            scrollCollapse: true,
            columns: [
                {data: "trail_id"},
                {data: "name"},
                {data: "question"},
                {data: "answer"},
                {data: "difficulty"},
                {data: "estimatedTime"},
                {data: "hint"},
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
            enigmasTable.ajax.reload();
        }, 500); //0.5s
    });
});

function visible($btn) {
    $.ajax({
        url: "/enigma/visible",
        method: 'POST',
        dataType: 'json',
        data: {
            id: $btn.data('id'),
            visible: $btn.data('visible'),
        },
        success: function () {
            // fix le tooltip qui reste au refresh
            $('.tooltip').remove();
            enigmasTable.ajax.reload();
        }
    })
}

function remove($btn) {
    $.ajax({
        url: "/enigma/remove",
        method: 'POST',
        dataType: 'json',
        data: {
            id: $btn.data('id')
        },
        success: function (e) {
            // fix le tooltip qui reste au refresh
            $('.tooltip').remove();
            enigmasTable.ajax.reload();
        },
        error: function(e) {
            console.log("error", e)
        }

    })
}

