const validateLogin = () => {
    const email = $("#email");
    const emailValue = email.val().trim();
    const emailError = $("#error_email");
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (emailValue === "") {
        emailError.text("Please enter your email to login");
        email.addClass("error");
        email.focus();
        return false;
    } else if (!emailPattern.test(emailValue)) {
        emailError.text("Email format required: example@domain.com");
        email.addClass("error");
        email.focus();
        return false;
    } else if (emailValue.length > 250) {
        emailError.text("Email cannot exceed 250 characters");
        email.addClass("error");
        email.focus();
        return false;
    }
    const password = $("#password");
    const passwordValue = password.val().trim();
    const passwordError = $("#error_password");
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!])[A-Za-z\d@#$%^&*!]+$/;
    if (!passwordPattern.test(passwordValue)) {
        passwordError.text("Password must include atleast one uppercase, number, and special character");
        password.addClass("error");
        password.focus();
        return false;
    } else if (passwordValue.length > 60) {
        passwordError.text("Password cannot exceed 60 characters");
        password.addClass("error");
        password.focus();
        return false;
    }
    return true;
}

$(document).ready( () => {
    $("#login_form").on("submit", validateLogin);

    $("#email, #password").on("input", function() {
        $(this).removeClass("error");
        $("#error_" + this.id).text("");
    });
});