let reloadTimeId = null;
let adminsTable;

$(document).ready(function () {
    const $adminTable = $('#admin-table'),
        dataUser = $adminTable.data('admins'),
        $search = $('#adminSearch');

    adminsTable = $adminTable.DataTable(
        {
            ordering:false,
            processing: true,
            serverSide: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            },
            ajax: {
                url: "/administrateur/get4gridadmin",
                type: "POST",
                data: function (d) {
                    d.pseudo = $search.val();
                }
            },
            deferLoading: dataUser['recordsFiltered'],
            data: dataUser['data'],
            paging: false,
            searching: false,
            columns: [
                { "data": "user_id" },
                { "data": "pseudo" },
                { "data": "email" },
                { "data": "actions" }
            ]
        }
    );
    $search.on('keyup', function () {
        // Annule le timer du précédent refresh de la dataTable
        // Pour cancel le refresh tant qu'on écrit
        clearTimeout(reloadTimeId);
        // Met un couldown pour le lancement du refresh de la dataTable
        reloadTimeId = setTimeout(function () {
            adminsTable.ajax.reload();
        }, 500); //0.5s
    });
});

function remove($btn) {
    $.ajax({
        url: "/administrateur/remove",
        method: 'POST',
        dataType: 'json',
        data: {
            id: $btn.data('id')
        },
        success: function () {
            // fix le tooltip qui reste au refresh
            $('.tooltip').remove();
            adminsTable.ajax.reload();
        }
    })
}
