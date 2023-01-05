
require('./bootstrap');
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import axios from 'axios'

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

window.Vue = require('vue').default;

Vue.component('client-table', require('./components/Clients.vue').default);
Vue.use(Vuetify)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

Vue.prototype.$axios = axios

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
