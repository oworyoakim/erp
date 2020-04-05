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
import StrategicObjectiveDetails from "./components/spms/objectives/StrategicObjectiveDetails";
import StrategicObjectiveForm from "./components/spms/objectives/StrategicObjectiveForm";
import SwotsAnalysis from "./components/spms/swots/Swots";
import SwotsAnalysisForm from "./components/spms/swots/SwotForm";
import SwotCategoryForm from "./components/spms/swots/SwotCategoryForm";
import StrageticObjectivePageView from "./components/spms/objectives/StrageticObjectivePageView";
import Interventions from "./components/spms/interventions/Interventions";
import InterventionForm from "./components/spms/interventions/InterventionForm";
import Outputs from "./components/spms/outputs/Outputs";
import OutputForm from "./components/spms/outputs/OutputForm";
import OutputIndicators from "./components/spms/indicators/OutputIndicators";
import OutputIndicatorForm from "./components/spms/indicators/OutputIndicatorForm";
import OutputIndicatorTargets from "./components/spms/targets/OutputIndicatorTargets";
import OutputIndicatorTargetForm from "./components/spms/targets/OutputIndicatorTargetForm";

Vue.component("app-admin-dashboard", Dashboard);
Vue.component('app-plans', StrategicPlans);
Vue.component('app-plan-form', StrategicPlanForm);
Vue.component('app-objective-form', StrategicObjectiveForm);
Vue.component('app-strategic-objectives', StrategicObjectives);
Vue.component('app-strategic-objective-details', StrategicObjectiveDetails);
Vue.component('app-strategic-objective-details-page', StrageticObjectivePageView);
Vue.component('app-swots-analysis', SwotsAnalysis);
Vue.component('app-swots-analysis-form', SwotsAnalysisForm);
Vue.component('app-swots-category-form', SwotCategoryForm);
Vue.component('app-interventions', Interventions);
Vue.component('app-intervention-form', InterventionForm);
Vue.component('app-outputs', Outputs);
Vue.component('app-output-form', OutputForm);
Vue.component('app-output-indicators', OutputIndicators);
Vue.component('app-output-indicator-form', OutputIndicatorForm);
Vue.component('app-output-indicator-targets', OutputIndicatorTargets);
Vue.component('app-output-indicator-target-form', OutputIndicatorTargetForm);

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
