let reloadTimeId = null;
let usersTable;

$(document).ready(function () {
    const $userTable = $('#user-table'),
        dataUser = $userTable.data('users'),
        $search = $('#userSearch');

    usersTable = $userTable.DataTable(
        {
            ordering:false,
            processing: true,
            serverSide: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
            },
            ajax: {
                url: "/utilisateurs/get4gridusers",
                type: "POST",
                data: function (d) {
                    d.pseudo = $search.val();
                }
            },
            deferLoading: dataUser['recordsFiltered'],
            data: dataUser['data'],
            paging: false,
            searching: false,
            scrollY: '500px',
            scrollCollapse: true,
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
            usersTable.ajax.reload();
        }, 500); //0.5s
    });
});

function remove($btn) {
    $.ajax({
        url: "/utilisateurs/remove",
        method: 'POST',
        dataType: 'json',
        data: {
            id: $btn.data('id')
        },
        success: function () {
            // fix le tooltip qui reste au refresh
            $('.tooltip').remove();
            usersTable.ajax.reload();
        }
    })
}
