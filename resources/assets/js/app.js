
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import './prototypes';
window.Vue = require('vue');

/**
 * Finally, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Common components that are re-usable across all components
Vue.component('tab-item', require('./components/TabItem.vue'));
Vue.component('form-item', require('./components/FormItem.vue'));
Vue.component('loading-cover', require('./components/LoadingCover.vue'));
Vue.component('abbreviation-text', require('./components/AbbreviationText.vue'));
// Person creation component and its child.
Vue.component('person-creation', require('./components/person-creation/Layout.vue'));
Vue.component('pc-personal-information', require('./components/person-creation/PersonalInformation.vue'));
Vue.component('pc-working-information', require('./components/person-creation/WorkingInformation.vue'));
Vue.component('pc-assign-vehicles', require('./components/person-creation/AssignVehicles.vue'));
Vue.component('pc-first-card', require('./components/person-creation/FirstCard.vue'));
// Other components
Vue.component('people-index', require('./components/PeopleIndex.vue'));
Vue.component('person-show', require('./components/PersonShow.vue'));

const app = new Vue({
    el: '#app'
});
