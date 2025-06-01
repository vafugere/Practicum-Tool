$(document).ready(function () {
    function togglePasswordVisibility(passwordFieldId, eyeIconId) {
        const passwordInput = $("#" + passwordFieldId);
        const eyePath = $("#" + eyeIconId);

        const eyeOutline = "M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zm0 14c-3.87 0-7.16-2.52-8.5-6 1.34-3.48 4.63-6 8.5-6s7.16 2.52 8.5 6c-1.34 3.48-4.63 6-8.5 6zm0-10c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z";
        const eyeFilled = "M12 4C7.03 4 2.73 7.5.68 12c2.05 4.5 6.35 8 11.32 8s9.27-3.5 11.32-8c-2.05-4.5-6.35-8-11.32-8zM12 8c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4z";

        const isPasswordVisible = passwordInput.attr("type") === "text";
        passwordInput.attr("type", isPasswordVisible ? "password" : "text");

        eyePath.attr("d", isPasswordVisible ? eyeOutline : eyeFilled);
    }

    $(document).on("click", "#toggle_password", function() {
        togglePasswordVisibility("password", "eye_1");
    });
    
    $(document).on("click", "#toggle_new_password", function() {
        togglePasswordVisibility("new_password", "eye_3");
    });
    
    $(document).on("click", "#toggle_confirm_password", function() {
        togglePasswordVisibility("confirm_password", "eye_2");
    });
});

