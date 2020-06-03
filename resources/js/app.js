import Vue from "vue";

require('./bootstrap');

let url = document.head.querySelector('meta[name="base-url"]');
// Create an Event Bus for communications
export const EventBus = new Vue();
// base URL
export const baseUrl = url.content;

import DateRangePicker from "./components/shared/DateRangePicker";
import Select2Input from "./components/shared/Select2Input";
import Spinner from "./components/shared/Spinner";
import Breadcrumb from "./components/shared/Breadcrumb";
import MainModal from "./components/shared/MainModal";


Vue.component("app-date-range-picker", DateRangePicker);
Vue.component("app-select-box", Select2Input);
Vue.component("app-breadcrumb", Breadcrumb);
Vue.component('app-spinner', Spinner);
Vue.component('app-main-modal', MainModal);

import Datepicker from "vuejs-datepicker";



Vue.use(Datepicker);
