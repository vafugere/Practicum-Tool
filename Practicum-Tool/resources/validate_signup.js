async function validateSignup(e) {
    const fname = $("#fname");
    const fnameValue = fname.val().trim();
    const fnameError = $("#error_fname");
    if (fnameValue === "" || fnameValue.length > 50) {
        fnameError.text(fnameValue === "" ? "First Name is required" : "First Name cannot exceed 50 characters");
        fname.addClass("error");
        fname.focus();
        return false;
    }
    const lname = $("#lname");
    const lnameValue = lname.val().trim();
    const lnameError = $("#error_lname");
    if (lnameValue === "" || lnameValue.length > 50) {
        lnameError.text(lnameValue === "" ? "Last Name is required" : "Last Name cannot exceed 50 characters");
        lname.addClass("error");
        lname.focus();
        return false;
    }
    const email = $("#email");
    const emailValue = email.val().trim();
    const emailError = $("#error_email");
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (emailValue === "" || emailValue.length > 250) {
        emailError.text(emailValue === "" ? "Email is required" : "Email cannot exceed 250 characters");
        email.addClass("error");
        email.focus();
        return false;
    } else if (!emailPattern.test(emailValue)) {
        emailError.text("Email format required: example@domain.com");
        email.addClass("error");
        email.focus();
        return false;
    }
    const confirmEmail = $("#confirm_email");
    const confirmEmailValue = confirmEmail.val().trim();
    const confirmEmailError = $("#error_confirm_email");
    if (confirmEmailValue !== emailValue) {
        confirmEmailError.text("Please confirm your email");
        confirmEmail.addClass("error");
        confirmEmail.focus();
        return false;
    }
    const orgId = $("#org_id");
    const orgIdValue = orgId.val().trim();
    const orgIdError = $("#error_org_id");
    const orgIdPattern = /^\d{7}$/;
    if (!orgIdPattern.test(orgIdValue)) {
        orgIdError.text("Please enter your 7 digit Student Id number");
        orgId.addClass("error");
        orgId.focus();
        return false;
    }
    const password = $("#password");
    const passwordValue = password.val().trim();
    const passwordError = $("#error_password");
    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!])[A-Za-z\d@#$%^&*!]+$/;
    if (passwordValue === "" || passwordValue.length > 60) {
        passwordError.text(passwordValue === "" ? "Password is required" : "Password cannot exceed 60 characters");
        password.addClass("error");
        password.focus();
        return false;
    } else if (!passwordPattern.test(passwordValue)) {
        passwordError.text("Password must include atleast one uppercase, number, and special character");
        password.addClass("error");
        password.focus();
        return false;
    }
    const confirmPassword = $("#confirm_password");
    const confirmPasswordValue = confirmPassword.val().trim();
    const confirmPasswordError = $("#error_confirm_password");
    if (confirmPasswordValue !== passwordValue) {
        confirmPasswordError.text("Please confirm your password");
        confirmPassword.addClass("error");
        confirmPassword.focus();
        return false;
    }
    return true;
}

// Match orgId
async function matchStudentId(id) {
    const orgId = $("#org_id");
    const orgIdError = $("#error_org_id");

    if (id.length > 7) {
        orgIdError.text("Student ID must match instructor's record");
        orgId.addClass("error");
        return;
    } else {
        orgIdError.text("");
        orgId.removeClass("error");
    }

    try {
        const response = await fetch("api/match_orgId.php?id=" + encodeURIComponent(id));
        const data = await response.json();
        if (!data.available) {
            orgIdError.text("Student ID must match instructor's record");
            orgId.addClass("error");
        }
    } catch (error) {
        console.log("Error: ", error);
    }
}

$(document).ready( () => {
    $("#signup_form").on("submit", async function(e) {
        e.preventDefault();
        let isValid = await validateSignup(e);
        if ($("#error_email").text() !== "") {
            $("#email").focus();
            return;
        }
        if ($("#error_org_id").text() !== "") {
            $("#org_id").focus();
            return;
        }
        if (isValid) {
            this.submit();
        }
    });

    // Clear input
    $("#fname, #lname, #confirm_email, #password, #confirm_password").on("input", function() {
        $(this).removeClass("error");
        $("#error_" + this.id).text("");
    });

    // Match orgId input
    $("#org_id").on("input", function() {
        let id = $(this).val().trim();
        if (id.length >= 7) {
            matchStudentId(id);
        } else {
            $("#error_org_id").text("");
            $(this).removeClass("error");
        }
    });

});