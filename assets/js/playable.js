$(document).ready(function () {
    let counter = 0;
    let id;

    $(".item_container").on("click", function () {
        id = $(this).data('id');
        $.ajax({
            url: "/loggedhomepage/play",
            method: "POST",
            dataType: "html",
            data: {
                id: id,
                counter: counter
            },
            success: function (result) {
                $(".enigme_container").html(result);
            }
        });
    });

    $(document).on("click", ".button_validator", function () {
        const solution = $(".button_validator").data('reponse');
        const rep =  $(".reponse_container").val();
        if ( solution === rep ) {
            $.ajax({
                url: "/loggedhomepage/nextenigma",
                method: "POST",
                dataType: "html",
                data: {
                  id: id,
                    counter: counter
                },
            });
        } else {
            $(".erro-text").html("Mauvaise réponse !")
        }
    });
});