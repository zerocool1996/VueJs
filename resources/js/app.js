/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import { routes }  from './router.js';
import store from './store';
Vue.use(VueRouter);

import izitoast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
window.izitoast = izitoast

const router = new VueRouter({
    mode: 'history',
    routes
})

router.beforeEach((to, from, next) => {
    const token = window.localStorage.getItem('access_token')
    if(to.matched.some(record => record.meta.requiresAuth)) {
       if (!token) {
            return next({name: 'login', query: { redirect: to.fullPath }})
       }
    } else {
        if (token && to.name === 'login') {
            return next({name: 'dashboard'})
        }
    }
    if(to.matched.some(record => record.meta.requiresAuthCustomer)) {
        if (!token) {
            return next({name: 'index'})
        }
    }
    next()
})

window.axios = require('axios')
window.axios.defaults.baseURL = 'http://commic.com/'
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
window.axios.defaults.headers.common['Accept'] = 'application/json'
window.axios.interceptors.request.use(config => {
   const token = window.localStorage.getItem('access_token')
   if (token) {
       config.headers['Authorization'] = `Bearer ${token}`
   }
   return config;
   }, error => Promise.reject(error)
);
Object.defineProperty(Vue.prototype, '$http', { value: window.axios })


const EventBus = new Vue() // khai báo event bus
Vue.prototype.$eventBus = EventBus // gán global event bus

Vue.component('master', require('./components/admin/layout/master.vue').default)

const app = new Vue({
    el: '#app',
    router,
    store,
});
