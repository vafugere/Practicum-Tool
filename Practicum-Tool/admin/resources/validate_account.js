// Validation for Admin Create Account
async function validateCreateAccount(e) {
    let fname = $("#fname");
    let fnameValue = fname.val().trim();
    let fnameError = $("#error_fname");
    if (fnameValue === "" || fnameValue.length > 50) {
        fname.addClass("error");
        fnameError.text(fnameValue === "" ? "First Name is required" : "First Name cannot exceed 50 characters");
        fname.focus();
        return false;
    }
    let lname = $("#lname");
    let lnameValue = lname.val().trim();
    let lnameError = $("#error_lname");
    if (lnameValue === "" || lnameValue.length > 50) {
        lname.addClass("error");
        lnameError.text(lnameValue === "" ? "Last Name is required" : "Last Name cannot exceed 50 characters");
        lname.focus();
        return false;
    }
    let email = $("#email");
    let emailValue = email.val().trim();
    let emailError = $("#error_email");
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (emailValue === "" || emailValue.length > 250) {
        email.addClass("error");
        emailError.text(emailValue === "" ? "Email is required" : "Email cannot exceed 250 characters");
        email.focus();
        return false;
    }
    if (!emailPattern.test(emailValue)) {
        email.addClass("error");
        emailError.text("Email format required: example@domain.com");
        email.focus();
        return false;
    } 
    let confirmEmail = $("#confirm_email");
    let confirmEmailValue = confirmEmail.val().trim();
    let confirmEmailError = $("#error_confirm_email");
    if (confirmEmailValue !== emailValue) {
        confirmEmail.addClass("error");
        confirmEmailError.text("Please confirm email");
        confirmEmail.focus();
        return false;
    }
    return true;
}

$(document).ready(() => { 
    $("#create_account").on("submit", async function(e) {
        e.preventDefault();
        let isValid = await validateCreateAccount(e);
        if ($("#error_email").text() !== "") {
            $("#email").focus();
            return;
        }
        if (isValid) {
            this.submit();
        }
    });
// Clear error message on click    
    $("#fname, #lname, #email, #confirm_email").on("input", function() {
        $(this).removeClass("error");
        $("#error_" + this.id).text("");
    });

// Remove additional campus checkboxes when Admin is selected
    $("#account").change(function() {
        $("#checkbox_menu").toggle($(this).val() != 3);
    });

});

