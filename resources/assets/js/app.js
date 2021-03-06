
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import BootstrapVue from 'bootstrap-vue'


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('stats', require('./components/meter.vue'));
Vue.component('feed', require('./components/feed.vue'));
Vue.component('device', require('./components/devices.vue'));
Vue.component('tank', require('./components/tank.vue'));
Vue.component('devicetypes', require('./components/devicetypes.vue'));
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.use(BootstrapVue);

const app = new Vue({
    el: '#app'
});
