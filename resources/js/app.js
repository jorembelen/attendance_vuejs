
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import { values } from 'lodash';
import { Form, HasError, AlertError } from 'vform';

import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;

window.form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: '#bffaf3',
    failedColor: '#874b4b',
    height: '3px'
})

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue') },
    { path: '/users', component: require('./components/Pages/Users.vue') },
    { path: '/departments', component: require('./components/Pages/Department.vue') },
    { path: '/positions', component: require('./components/Pages/Position.vue') },
    { path: '/checkOuts', component: require('./components/Pages/CheckOut.vue') },
    { path: '/checkIns', component: require('./components/Pages/CheckIn.vue') },
    { path: '/employees', component: require('./components/Pages/Employees.vue') },
    { path: '/profile', component: require('./components/Pages/Profile.vue') }
  ]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function(created){
    return moment(created).format('MMMM Do YYYY');
});

Vue.filter('myTime', function(time){
    return moment(time).format('LT');
});


window.Fire = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    router
});
