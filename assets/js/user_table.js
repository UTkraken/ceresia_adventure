var table;

$(document).ready(function() {
    var data = $('#user_table').data('users');

    table = $('#user_table').DataTable(
        {
            serverSide  : true,
            ajax        : {
                "url" : "utilisateurs/users",
                "type": "POST",
            },
            data: data['data'],
            deferLoading: data['recordsFiltered'],
            paging      : true,
            pageLength  : 10,
            order       : [],
            info        : false,
            columns: [
                { "data": "user_id" },
                { "data": "pseudo" },
                { "data": "email" },
                { "data": "actions" }
            ]
        }
    );

    $('#user_table').on('click', '.delete', function () {
        var userId = $(this).data('userid');

        $.ajax({
           url : 'utilisateurs/delete',
            method:'POST',
            dataType: "json",
            data : {
               userId : userId,
            },
        }).always(function() {
            console.log('Utilisateur supprimé');
            table.ajax.reload( null, false );
        });
    })
} );
