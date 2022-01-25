require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
Vue.use(VueRouter);

const indexComponent = Vue.component('index-component',require('./components/IndexComponent.vue').default);
const productsComponent = Vue.component('products-component',require('./components/ProductsComponent.vue').default);
const importComponent = Vue.component('import-component',require('./components/ImportComponent.vue').default);

const router = new VueRouter({
    routes: [
        {path: '/',name: 'main.index',component: indexComponent},
        {path: '/products',name: 'main.products',component: productsComponent},
        {path: '/import',name: 'main.import',component: importComponent},
    ],
    mode: 'history',
});

const app = new Vue({
    el: '#vueApp',
});
