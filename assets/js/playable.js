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
        const solution = $(".button_validator").data('reponse').toString();
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

    $(document).on("click", ".button_rating_validator", function () {
        const rating = $("#rating").val();
        $.ajax({
            url: "/LoggedHomepage/addRating",
            method: "POST",
            dataType: "text",
            data: {
                id: id,
                rating: rating
            },
            success: function () {
               window.location.href = "/loggedhomepage";
            }
        });
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