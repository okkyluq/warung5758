/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
 import VueSlideoutPanel from 'vue2-slideout-panel';
// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


Vue.component('index-item', require('./components/item/Index.vue').default);
Vue.component('create-item', require('./components/item/Create.vue').default);
Vue.component('edit-item', require('./components/item/Edit.vue').default);

Vue.component('index-item-masuk', require('./components/item_masuk/Index.vue').default);
Vue.component('createitemmasuk', require('./components/item_masuk/Create.vue').default);


Vue.component('index-item-keluar', require('./components/item_keluar/Index.vue').default);
Vue.component('createitemkeluar', require('./components/item_keluar/Create.vue').default);

Vue.component('index-menu', require('./components/menu/Index.vue').default);
Vue.component('createmenu', require('./components/menu/Create.vue').default);


Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('create-barang', require('./components/CreateBarang.vue').default);
Vue.component('edit-barang', require('./components/EditBarang.vue').default);
Vue.component('transaksi-produk-masuk', require('./components/produk_masuk/Index.vue').default);
Vue.component('transaksi-produk-keluar', require('./components/produk_keluar/Index.vue').default);
Vue.component('createkeluar', require('./components/produk_keluar/Create.vue').default);



Vue.use(VueSlideoutPanel);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
