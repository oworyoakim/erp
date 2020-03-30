import Vue from 'vue';
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    // Vue js
    window.Vue = Vue;
    // jQuery
    window.$ = window.jQuery = Vue.prototype.$jquery = require('jquery');
    // Popper
    //window.Popper = window.popper = require('popper.js').default;
    window.Popper = window.popper = require('../../public/smarthr/maroon/js/popper.min');
    // Bootstrap
    //require('bootstrap');
    require('../../public/smarthr/maroon/js/bootstrap.min') || require('bootstrap');
    // Slimscroll JS
    require('../../public/smarthr/maroon/js/jquery.slimscroll.min.js');
    // lodash
    window._ = require('lodash');
    // Datatable JS
    require('../../public/smarthr/maroon/js/jquery.dataTables.min');
    require('../../public/smarthr/maroon/js/dataTables.bootstrap4.min');
    // bootstrap-daterangepicker
    require('../../public/plugins/bootstrap-daterangepicker/daterangepicker');
    // bootstrap-datepicker
    require('../../public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min');
    // Toastr
    window.toastr = require('toastr');
    // Select2
    require('select2');
    // jquery.PrintArea.js
    require('../../public/plugins/jquery.PrintArea.js');
    // SweetAlert
    window.swal = require('sweetalert');
    // Axios
    const axios = require('axios').default;
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['Content-Type'] = 'application/json';
    // Register the CSRF Token
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = null;
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    // Response Interceptor

    Vue.prototype.$stringLimit = function (str,length=20) {
        if (str.length > length){
            return str.substring(0,length) + " ...";
        }
        return str;
    };

    window.axios = Vue.prototype.$http = axios;
    // Moment
    window.moment = Vue.prototype.$moment = require('moment');
    // Numeral
    window.numeral = Vue.prototype.$numeral = require('numeral');
    // Morris Chart
    require('morris.js/morris.min');
    // Morris Requires Raphael
    window.Raphael = require('raphael');
    // Custom JS
    require('../../public/smarthr/maroon/js/app');
} catch (error) {
    console.error(error);
}


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
