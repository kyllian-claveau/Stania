import $ from "jquery";

function initializeBurgerButton() {
    $('#burger-button').click(() => {
        $('#sidebar-menu').slideToggle();
        $('#burger-button svg').toggleClass('text-white');
    });
}

$(document).ready(() => {
    initializeBurgerButton();
});