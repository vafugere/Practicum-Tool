$(document).ready(function() {
    let inactivityTimer;

    function resetTimer() {
        clearTimeout(inactivityTimer);
        //inactivityTimer = setTimeout(showModal, 60000);
        inactivityTimer = setTimeout(showModal, 1800000);
    }

    function showModal() {
        $("body").addClass("modal-active");
        $("#timeoutModal").fadeIn();
    }

    $(document).on("mousemove keypress click scroll", resetTimer);
    resetTimer();

    $("#staySignedIn").click(function () {
        clearTimeout(inactivityTimer);
        $("#timeoutModal").css("display", "none");
        resetTimer();
    });

    $("#staySignedIn, #logout").on("click", function () {
        $("#timeoutModal").fadeOut();
        $("body").removeClass("modal-active");
    });
});
