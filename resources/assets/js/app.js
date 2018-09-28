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

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/es'
Vue.use(ElementUI, {locale});

/**
 * Finally, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Common components that are re-usable across all components
Vue.component('hr-label' ,          require('./components/_common/HrLabel.vue'));
Vue.component('date-picker' ,          require('./components/_common/DatePicker.vue'));
Vue.component('select2'           , require('./components/_common/Select2.vue'));
Vue.component('tab-item'          , require('./components/_common/TabItem.vue'));
Vue.component('cuil-cuit'         , require('./components/_common/CuilCuit.vue'));
Vue.component('form-item'         , require('./components/_common/FormItem.vue'));
Vue.component('web-camera'        , require('./components/_common/WebCamera.vue'));
Vue.component('switch-box'        , require('./components/_common/SwitchBox.vue'));
Vue.component('access-card'       , require('./components/_common/AccessCard.vue'));
Vue.component('custom-table'      , require('./components/_common/CustomTable.vue'));
Vue.component('loading-cover'     , require('./components/_common/LoadingCover.vue'));
Vue.component('modal-wrapper'     , require('./components/_common/ModalWrapper.vue'));
Vue.component('residency-input'   , require('./components/_common/ResidencyInput.vue'));
Vue.component('pictures-gallery'  , require('./components/_common/PicturesGallery.vue'));
Vue.component('abbreviation-text' , require('./components/_common/AbbreviationText.vue'));
Vue.component('confirmable-button', require('./components/_common/ConfirmableButton.vue'));
// Wrappers
Vue.component('show-wrapper'  , require('./components/_common/wrappers/ShowWrapper.vue'));
Vue.component('index-wrapper'  , require('./components/_common/wrappers/IndexWrapper.vue'));
Vue.component('creation-wrapper'  , require('./components/_common/wrappers/CreationWrapper.vue'));
// Dashboards
Vue.component('administration-dashboard', require('./components/dashboards/administration/Layout.vue'));
Vue.component('security-dashboard', require('./components/dashboards/security/Layout.vue'));

const router = new VueRouter({
    routes: [
        // People
        { path: '/people',              component: require('./components/people/index/Layout.vue')  },
        { path: '/people/create',       component: require('./components/people/create/Layout.vue') },
        { path: '/people/show/:id',     component: require('./components/people/show/Layout.vue')   },
        // Companies
        { path: '/companies',           component: require('./components/companies/index/Layout.vue') },
        { path: '/companies/create',    component: require('./components/companies/create/Layout.vue') },
        { path: '/companies/show/:id',  component: require('./components/companies/show/Layout.vue') },
        // Vehicles
        { path: '/vehicles',            component: require('./components/vehicles/index/Layout.vue') },
        { path: '/vehicles/create',     component: require('./components/vehicles/create/Layout.vue') },
        { path: '/vehicles/show/:id',   component: require('./components/vehicles/show/Layout.vue') },
        // Containers
        { path: '/containers',            component: require('./components/containers/index/Layout.vue') },
        { path: '/containers/create',     component: require('./components/containers/create/Layout.vue') },
        { path: '/containers/show/:id',   component: require('./components/containers/show/Layout.vue') },
        // Extra
        { path: '/bar', component: { template: '<div>Test route</div>' } },
        // Settings
        { path: '/settings', component: require('./components/dashboards/administration/partials/settings/Layout.vue')}
    ]
});

Vue.mixin({
    methods: {
        clone: function(object) {
            return JSON.parse(JSON.stringify(object));
        },
        fileToDataURI: function(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }
    }
});

const app = new Vue({
    el: '#app',
    store: new Vuex.Store(store),
    router,
});