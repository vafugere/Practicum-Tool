async function emailAvailability(email) {
    const emailField = $("#email");
    const emailError = $("#error_email");
    try {
        const response = await fetch("api/available_email.php?email=" + encodeURIComponent(email));
        const data = await response.json();
        if (!data.available) {
            emailError.text("This email is already in use");
            emailField.addClass("error");
        }
    } catch (error) {
        console.log("Error: ", error);
    }
}

$(document).ready( () => {
    $("#email").on("input", function() {
        let email = $(this).val().trim();
        if (email.length >= 5) {
            emailAvailability(email);
        } else {
            $("#error_email").text("");
            $(this).removeClass("error");
        }
    });
});