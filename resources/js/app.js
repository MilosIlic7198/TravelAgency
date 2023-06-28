require("./bootstrap");

import Vue from 'vue';

Vue.component("app-component", require("./components/app.vue").default);

import VueRouter from "vue-router";
import routes from "./routes";
import Vue2Editor from "vue2-editor";
import VModal from 'vue-js-modal'

Vue.use(VueRouter);
Vue.use(Vue2Editor);
Vue.use(VModal, {
    dialog: true,
    dynamicDefaults: {
        draggable: true,
        resizable: true,
        height: 'auto'
    }
});

const app = new Vue({
    router: new VueRouter(routes),
}).$mount('#app');
