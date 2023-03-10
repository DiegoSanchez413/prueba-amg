
require('./bootstrap');
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import axios from 'axios'


window.Vue = require('vue').default;

Vue.component('client-table', require('./components/Clients.vue').default);
Vue.use(Vuetify)

Vue.prototype.$axios = axios

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
