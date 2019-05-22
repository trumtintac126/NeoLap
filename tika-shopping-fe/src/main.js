import Vue from "vue"
import App from "./App.vue"
import router from "./router"
import store from "./store"
import VueResource from "vue-resource"
import BootstrapVue from 'bootstrap-vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faAngleDown } from '@fortawesome/free-solid-svg-icons'
import { faDollarSign } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Echo from "laravel-echo"
//add
// import socketio from 'socket.io-client'
// import VueSocketIO from 'vue-socket.io'
//

library.add(faAngleDown)
library.add(faDollarSign)

Vue.component('font-awesome-icon', FontAwesomeIcon)

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false;
Vue.use(VueResource);
// Vue.http.options.root = 'https://project-dac-laravel.herokuapp.com/api/';
Vue.http.options.root = 'http://127.0.0.1:8000/api/';

//add
// let socket = socketio('http://127.0.0.1:6001');
// Vue.use(VueSocketIO, socket)
//
//add

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});
//


Vue.use(BootstrapVue)

import helpers from './helpers';

const plugin = {
  install(Vue, options) {
    Vue.prototype.$helpers = helpers; // we use $ because it's the Vue convention
  }
};

Vue.use(plugin);

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
