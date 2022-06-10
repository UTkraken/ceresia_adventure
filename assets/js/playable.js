$(document).ready(function () {
    $(".item_container").on("click", function () {
        const id = $(this).data('id');
        console.log("Coucou je suis " + id);
    });
});