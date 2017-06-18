window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

require('bootstrap-sass');
require('bootstrap-material-design');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

/**
 * Setup moment.js
 */
window.moment = require('moment');
require('moment/locale/de-ch.js');

/**
 * Kendo UI
 */
require('kendo-ui-core');
require('kendo-ui-core/js/messages/kendo.messages.de-DE');
require('kendo-ui-core/js/cultures/kendo.culture.de');

/**
 * Autosize
 */
window.autosize = require('autosize');

window.Vue = require('vue');

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');