/*
Template Name: Ubold - Responsive Bootstrap 5 Admin Dashboard
Author: CoderThemes
Website: https://coderthemes.com/
Contact: support@coderthemes.com
File: Authentication init js
*/

$("[data-password]").on('click', function () {
    if ($(this).attr('data-password') == "false") {
        $(this).siblings("input").attr("type", "text");
        $(this).attr('data-password', 'true');
        $(this).addClass("show-password");
    } else {
        $(this).siblings("input").attr("type", "password");
        $(this).attr('data-password', 'false');
        $(this).removeClass("show-password");
    }
});