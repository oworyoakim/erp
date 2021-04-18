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
import Interventions from "./components/spms/interventions/Interventions";
import InterventionForm from "./components/spms/interventions/InterventionForm";
import Outputs from "./components/spms/outputs/Outputs";
import OutputForm from "./components/spms/outputs/OutputForm";
import OutputIndicators from "./components/spms/indicators/OutputIndicators";
import OutputIndicatorForm from "./components/spms/indicators/OutputIndicatorForm";
import OutputIndicatorTargets from "./components/spms/targets/OutputIndicatorTargets";
import OutputIndicatorTargetForm from "./components/spms/targets/OutputIndicatorTargetForm";

import KeyResultAreas from "./components/spms/key-result-areas/KeyResultAreas";
import KeyResultAreaDetails from "./components/spms/key-result-areas/KeyResultAreaDetails";
import KeyResultAreaForm from "./components/spms/key-result-areas/KeyResultAreaForm";
import Outcomes from "./components/spms/outcomes/Outcomes";
import OutcomeForm from "./components/spms/outcomes/OutcomeForm";
import OutcomeIndicators from "./components/spms/indicators/OutcomeIndicators";
import OutcomeIndicatorForm from "./components/spms/indicators/OutcomeIndicatorForm";
import OutcomeIndicatorTargets from "./components/spms/targets/OutcomeIndicatorTargets";
import OutcomeIndicatorTargetForm from "./components/spms/targets/OutcomeIndicatorTargetForm";

import ExecuteWorkPlan from "./components/spms/plans/ExecuteWorkPlan";
import ExecuteResolutionsAndDirectives from "./components/spms/plans/ExecuteResolutionsAndDirectives";
import ExecutePerformanceCapture from "./components/spms/plans/ExecutePerformanceCapture";

import Activities from "./components/spms/activities/Activities";
import ActivityForm from "./components/spms/activities/ActivityForm";
import Stages from "./components/spms/stages/Stages";
import StageForm from "./components/spms/stages/StageForm";
import Tasks from "./components/spms/tasks/Tasks";
import TaskForm from "./components/spms/tasks/TaskForm";

import MonitorStrategicPlanDetailed from "./components/spms/plans/MonitorStrategicPlanDetailed";
import MonitorStrategicPlanSummary from "./components/spms/plans/MonitorStrategicPlanSummary";
import MonitorActivity from "./components/spms/plans/MonitorActivity";
import MonitorDirectivesAndResolutions from "./components/spms/plans/MonitorDirectivesAndResolutions";

Vue.component("app-admin-dashboard", Dashboard);
Vue.component('app-plans', StrategicPlans);
Vue.component('app-plan-form', StrategicPlanForm);
Vue.component('app-swots-analysis', SwotsAnalysis);
Vue.component('app-swots-analysis-form', SwotsAnalysisForm);
Vue.component('app-swots-category-form', SwotCategoryForm);

// output-based
Vue.component('app-objective-form', StrategicObjectiveForm);
Vue.component('app-strategic-objectives', StrategicObjectives);
Vue.component('app-strategic-objective-details', StrategicObjectiveDetails);
Vue.component('app-interventions', Interventions);
Vue.component('app-intervention-form', InterventionForm);
Vue.component('app-outputs', Outputs);
Vue.component('app-output-form', OutputForm);
Vue.component('app-output-indicators', OutputIndicators);
Vue.component('app-output-indicator-form', OutputIndicatorForm);
Vue.component('app-output-indicator-targets', OutputIndicatorTargets);
Vue.component('app-output-indicator-target-form', OutputIndicatorTargetForm);

// outcome-base
Vue.component('app-key-result-areas', KeyResultAreas);
Vue.component('app-key-result-area-form', KeyResultAreaForm);
Vue.component('app-key-result-area-details', KeyResultAreaDetails);
Vue.component('app-outcomes', Outcomes);
Vue.component('app-outcome-form', OutcomeForm);
Vue.component('app-outcome-indicators', OutcomeIndicators);
Vue.component('app-outcome-indicator-form', OutcomeIndicatorForm);
Vue.component('app-outcome-indicator-targets', OutcomeIndicatorTargets);
Vue.component('app-outcome-indicator-target-form', OutcomeIndicatorTargetForm);

Vue.component('app-plan-execute-performance-capture', ExecutePerformanceCapture);
Vue.component('app-plan-execute-work-plan', ExecuteWorkPlan);
Vue.component('app-plan-execute-resolutions-and-directives', ExecuteResolutionsAndDirectives);

Vue.component('app-activities', Activities);
Vue.component('app-activity-form', ActivityForm);

Vue.component('app-stages', Stages);
Vue.component('app-stage-form', StageForm);

Vue.component('app-tasks', Tasks);
Vue.component('app-task-form', TaskForm);

Vue.component('app-plan-monitor-strategy-summary', MonitorStrategicPlanSummary);
Vue.component('app-plan-monitor-strategy-detailed', MonitorStrategicPlanDetailed);
Vue.component('app-monitor-activity', MonitorActivity);
Vue.component('app-plan-monitor-directives-and-resolutions', MonitorDirectivesAndResolutions);

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

spmsStore.dispatch("getUser").then((data) => {
    return spmsStore.dispatch("GET_GENERAL_SETTINGS");
}).then((data) => {
    // if (!window.GET_USER_INTERVAL) {
    //     window.GET_USER_INTERVAL = setInterval(async () => {
    //         await spmsStore.dispatch("getUser");
    //         await spmsStore.dispatch("GET_GENERAL_SETTINGS");
    //     }, 60000);
    // }
}).catch(error => {
    console.error(error);
    //clearInterval(window.GET_USER_INTERVAL);
});
