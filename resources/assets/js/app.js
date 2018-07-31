/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import './prototypes';
window.Vue = require('vue');

import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

import Vuex from 'vuex';
window.Vue.use(Vuex);

import store from './store.js';

/**
 * Finally, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Common components that are re-usable across all components
Vue.component('tab-item',          require('./components/_common/TabItem.vue'));
Vue.component('form-item',         require('./components/_common/FormItem.vue'));
Vue.component('loading-cover',     require('./components/_common/LoadingCover.vue'));
Vue.component('abbreviation-text', require('./components/_common/AbbreviationText.vue'));
// Dashboards
Vue.component('administration-dashboard', require('./components/dashboards/administration/Layout.vue'));

const router = new VueRouter({
    routes: [
        // People
        { path: '/people',          component: require('./components/people/index/Layout.vue')  },
        { path: '/people/create',   component: require('./components/people/create/Layout.vue') },
        { path: '/people/show/:id', component: require('./components/people/show/Layout.vue')   },
        // Companies
        { path: '/companies/create', component: require('./components/companies/create/Layout.vue') },
        // Extra
        { path: '/bar', component: { template: '<div>Test route</div>' } },
    ]
});

const app = new Vue({
    el: '#app',
    store: new Vuex.Store(store),
    router
});