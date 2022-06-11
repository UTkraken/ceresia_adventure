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
        const count = parseInt($(".game-count").data('count'));
        const rep =  $(".reponse_container").val();
        counter = counter +1;
        if ( solution.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase() === rep.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase() ) {
            if ( counter !== count ) {
                $.ajax({
                    url: "/loggedhomepage/nextenigma",
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
            } else {
                $.ajax({
                    url: "/loggedhomepage/victory",
                    method: "POST",
                    dataType: "html",
                    data: {
                        id: id
                    },
                    success: function (result) {
                        $(".enigme_container").html(result);
                    }
                });
            }
        } else {
            $(".erro-text").html("Mauvaise réponse !")
        }
    });

    $(document).on("click", ".indice-button", function () {
        $(".form-reponse").css("display", "none");
        $(".modal-indice").css("display", "block");
    });

    $(document).on("click", ".close-indice", function () {
        $(".form-reponse").css("display", "block");
        $(".modal-indice").css("display", "none");
    });
});