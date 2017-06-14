// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});