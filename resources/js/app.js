/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// const { default: VueRouter } = require('vue-router');

require('./bootstrap');

window.Vue = require('vue');

const router = require('./routes');
window.vueRouter = router;

const { store } = require('./store/index');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component(
    'articles',
    require('./components/Articles.vue').default
);
Vue.component(
    'navbar',
    require('./components/Navbar.vue').default
);
Vue.component(
    'home',
    require('./components/Home.vue').default
);


router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if(!store.getters.loggedIn) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });
        }else { next(); }
    }else { next(); }
});

const app = new Vue({
    el: '#app',
    router,
    store
});
