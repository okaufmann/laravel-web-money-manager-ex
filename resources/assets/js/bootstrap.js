/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Sett global authorization headers for js libraries
 */
window.axios.defaults.headers.common['Authorization'] = "Bearer " + Laravel.apiToken;
$.ajaxSetup({
    beforeSend: function (xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + Laravel.apiToken);
    }
});
kendo.jQuery.ajaxSetup({
    beforeSend: function (xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + Laravel.apiToken);
    }
});

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

// Material Buttons
$(document).ready(() => {

    $.material.init();

    autosize($('textarea'));

    $("[autofocus]").focus();

    $(".common-dateinput").each((index, elm) => {
        new kendo.ui.DateInput($(elm), {
            value: new Date()
        });
    });

    $(".common-datepicker").each((index, elm) => {
        new kendo.ui.DatePicker($(elm), {
            value: new Date()
        });
    });

    $(".common-dropdown-list").each((index, elm) => {
        new kendo.ui.DropDownList($(elm), {
            filter: "startswith",
        });
    });

    $('textarea, input').keyup(function (e) {
        if (e.which == 17) isCtrl = false;
    }).keydown(function (e) {
        if (e.which == 17) isCtrl = true;
        if (e.which == 13 && isCtrl === true) {
            $(this).closest('form').submit();
            return false;
        }
    });
});