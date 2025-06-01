$(document).ready(function () {
    const homeLink = $("#home_link");
    const homeImg = $("#home_img");
    homeLink.on("mouseenter", function () {
        console.log("Hover ON");
        homeImg.attr("src", "../images/hover/home.png");
    });
    homeLink.on("mouseleave", function () {
        console.log("Hover OFF");
        homeImg.attr("src", "../images/icons/home.png");
    });

    const settingsBtn = $("#btn_settings");
    settingsBtn.on("mouseenter", function () {
        settingsBtn.attr("src", "../images/hover/settings.png");
    });
    settingsBtn.on("mouseleave", function () {
        settingsBtn.attr("src", "../images/icons/settings.png");
    });

    const bellBtn = $("#btn_bell");
    bellBtn.on("mouseenter", function () {
        bellBtn.attr("src", "../images/hover/bell.png");
    });
    bellBtn.on("mouseleave", function () {
        bellBtn.attr("src", "../images/icons/bell.png");
    });
});