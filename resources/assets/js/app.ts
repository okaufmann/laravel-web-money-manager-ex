// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

require('./bootstrap');

Vue.component('transaction-table', require('./components/TransactionTable.vue'));

const app = new Vue({
    el: '#app'
});