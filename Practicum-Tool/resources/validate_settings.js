function validateSettings(e) {
    const password = $("#password");
    const passwordValue = password.val().trim();
    const passwordError = $("#error_password");
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!])[A-Za-z\d@#$%^&*!]+$/;
    const confirm = $("#confirm_password");
    const confirmValue = confirm.val().trim();
    const confirmError = $("#error_confirm_password");

    if (passwordValue !== "") {
        if (!passwordPattern.test(passwordValue)) {
            passwordError.text("Password must include at least one uppercase letter, number, and special character");
            password.addClass("error");
            password.focus();
            return false;
        }
        if (passwordValue.length > 60) {
            passwordError.text("Password cannot exceed 60 characters");
            password.addClass("error");
            password.focus();
            return false;
        }
        if (passwordValue !== confirmValue) {
            confirmError.text("Please confirm your password");
            confirm.addClass("error");
            confirm.focus();
            return false;
        }
    }

    return true;
}

$(document).ready(() => {
    $("#settings_form").on("submit", function(e) {
        if (!validateSettings(e)) {
            e.preventDefault();
        }
    });

    $("#password, #confirm_password").on("input", function() {
        $(this).removeClass("error");
        $("#error_" + this.id).text("");
    });
});

