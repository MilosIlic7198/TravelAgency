require("./bootstrap");

window.Vue = require("vue").default;

Vue.component("app-component", require("./components/app.vue").default);

import VueRouter from "vue-router";
import routes from "./routes";
import Vue2Editor from "vue2-editor";

Vue.use(VueRouter);
Vue.use(Vue2Editor);

const app = new Vue({
    el: "#app",
    router: new VueRouter(routes),
});
