/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from "vue";

require("./app");
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

import Dashboard from "./components/spms/dashboard/Dashboard";
import StrategicPlans from "./components/spms/plans/StrategicPlans";
import StrategicPlanForm from "./components/spms/plans/StrategicPlanForm";
import StrategicObjectives from "./components/spms/objectives/Objectives";
import SwotsAnalysis from "./components/spms/swots/Swots";

Vue.component("app-admin-dashboard", Dashboard);
Vue.component('app-plans', StrategicPlans);
Vue.component('app-plan-form', StrategicPlanForm);
Vue.component('app-strategic-objectives', StrategicObjectives);
Vue.component('app-swots-analysis', SwotsAnalysis);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import spmsStore from "./stores/spms";

const app = new Vue({
    el: "#main-app",
    store: spmsStore
});

spmsStore.dispatch("getUser").then(() => {
    if (!window.intervalId) {
        window.intervalId = setInterval(async () => {
            await spmsStore.dispatch("getUser");
        }, 60000);
    }
}).catch(error => {
    console.error(error);
});
