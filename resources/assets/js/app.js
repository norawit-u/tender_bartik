
/**
  * first we will load all of this project's javascript dependencies which
  * includes vue and other libraries. it is a great starting point when
  * building robust, powerful web applications using vue and laravel.
  */

require('./bootstrap');

window.vue = require('vue');

    /**
      * next, we will create a fresh vue application instance and attach it to
      * the page. then, you may begin adding components to this application
      * or customize the javascript scaffolding to fit your unique needs.
      */

vue.component('examplecomponent', require('./components/examplecomponent.vue'));
vue.component(
        'passportauthorizedclients',
        require('./components/passport/authorizedclients.vue')
    );

vue.component(
        'passportclients',
        require('./components/passport/clients.vue')
    );

vue.component(
        'passportpersonalaccesstokens',
        require('./components/passport/personalaccesstokens.vue')
    );
const app = new vue({
        el: '#app'
});