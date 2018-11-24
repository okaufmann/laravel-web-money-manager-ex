require('./bootstrap');

Vue.component('transaction-table', require('./components/TransactionTable.vue'));

const app = new Vue({
    el: '#app'
});