async function validateTempForm() {
    const currentField = $("#password");
    const currentValue = currentField.val().trim();
    const currentError = $("#error_password");
    if (currentValue === "" || currentValue.length > 8) {
        currentError.text(currentValue === "" ? "Enter your current temporary password" : "Temporary password must be 8 characters");
        currentField.addClass("error");
        currentField.focus();
        return false;
    }
    const newField = $("#new_password");
    const newValue = newField.val().trim();
    const newError = $("#error_new_password");
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!])[A-Za-z\d@#$%^&*!]+$/;
    if (newValue === "" || newValue.length > 60) {
        newError.text(newValue === "" ? "Enter your new password" : "Password cannot exceed 60 characters");
        newField.addClass("error");
        newField.focus();
        return false;
    }
    if (!passwordPattern.test(newValue)) {
        newError.text("Password must include atleast one uppercase, number, and special character");
        newField.addClass("error");
        newField.focus();
        return false;
    }
    const confirmField = $("#confirm_password");
    const confirmValue = confirmField.val().trim();
    const confirmError = $("#error_confirm_password");
    if (confirmValue != newValue) {
        confirmError.text("Please confirm your password");
        confirmField.addClass("error");
        confirmField.focus();
        return false;
    }
    return true;
}
// Confirm temporary password match
function matchTemp(temp) {
    const currentField = $("#password");
    const currentError = $("#error_password");

    $.ajax({
        url: "api/match_temp.php",
        type: "GET",
        data: { temp: temp },
        dataType: "json",
        success: function(data) {
            if (!data.available) {
                currentError.text("Temporary password does not match");
                currentField.addClass("error");
            } else {
                currentError.text("");
                currentField.removeClass("error");
            }
        }
    });
}

function initializeTempValidation() {
    $(document).on("input", "#password", function() {
        const temp = $(this).val().trim();
        if (temp.length >= 8) {
            matchTemp(temp);
        }
    });

    $(document).on("submit", "#password_form", async function(e) {
        e.preventDefault();
        const isValid = await validateTempForm();
        if ($("#error_password").text() !== "") {
            $("#password").focus();
            return;
        }
        if (isValid) {
            this.submit();
        }
    });

    $(document).on("input", "#password, #new_password, #confirm_password", function() {
        $(this).removeClass("error");
        $("#error_" + this.id).text("");
    });
}

$(document).ready(function() {
    initializeTempValidation();
});



