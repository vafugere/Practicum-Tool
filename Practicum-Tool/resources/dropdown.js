$(document).ready(function () {
    var settingsBtn = $("#btn_settings");
    var dropdownMenu = $("#dropdown_menu");

    settingsBtn.on("click", function (e) {
        dropdownMenu.stop(true, true).fadeToggle(200);
        e.stopPropagation();
    });

    $(document).on("click", function (e) {
        if (!settingsBtn.is(e.target) && !dropdownMenu.is(e.target) && dropdownMenu.has(e.target).length === 0) {
            dropdownMenu.stop(true, true).fadeOut(200);
        }
    });

    dropdownMenu.hide();

    const bellBtn = $("#btn_bell");
    const notificationMenu = $("#notification_menu");

    bellBtn.on("click", function(e) {
        notificationMenu.stop(true, true).fadeToggle(200);
        e.stopPropagation();
    });

    $(document).on("click", function(e) {
        if (!bellBtn.is(e.target) && !notificationMenu.is(e.target) && notificationMenu.has(e.target).length === 0) {
            notificationMenu.stop(true, true).fadeOut(200);
        }
    });

    notificationMenu.hide();
});