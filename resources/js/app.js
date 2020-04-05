import Vue from "vue";

require('./bootstrap');

let url = document.head.querySelector('meta[name="base-url"]');
// Create an Event Bus for communications
export const EventBus = new Vue();
// base URL
export const baseUrl = url.content;

import DateRangePicker from "./components/shared/DateRangePicker";
import Select2Input from "./components/shared/Select2Input";
import DashboardWidgets from "./components/shared/DashboardWidgets";
import DashboardWidget from "./components/shared/DashboardWidget";
import MorrisBarChart from "./components/charts/MorrisBarChart";
import Spinner from "./components/shared/Spinner";
import Roles from "./components/users/Roles";
import Breadcrumb from "./components/shared/Breadcrumb";
import GeneralSettings from "./components/settings/GeneralSettings";
import LeaveSettings from "./components/settings/LeaveSettings";
import ApprovalsSettings from "./components/settings/ApprovalsSettings";
import ProfileItemHeader from "./components/shared/ProfileItemHeader";
import UserWidget from "./components/shared/UserWidget";
import Users from "./components/users/Users";
import UserForm from "./components/users/UserForm";
import MainModal from "./components/shared/MainModal";

Vue.component("app-profile-item-header", ProfileItemHeader);
Vue.component("app-date-range-picker", DateRangePicker);
Vue.component("app-leave-settings", LeaveSettings);
Vue.component("app-users", Users);
Vue.component("app-user-form", UserForm);
Vue.component("app-roles", Roles);
Vue.component("app-users-widget", UserWidget);
Vue.component("app-select-box", Select2Input);
Vue.component("app-breadcrumb", Breadcrumb);
Vue.component("app-dashboard-widgets", DashboardWidgets);
Vue.component("app-dashboard-widget", DashboardWidget);
Vue.component("app-general-settings", GeneralSettings);
Vue.component("app-approvals-settings", ApprovalsSettings);
Vue.component("app-morris-bar-chart", MorrisBarChart);
Vue.component('app-spinner', Spinner);
Vue.component('app-main-modal', MainModal);

import Datepicker from "vuejs-datepicker";



Vue.use(Datepicker);
