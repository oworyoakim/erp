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
import Login from "./components/acl/Login";
import Users from "./components/acl/users/Users";
import UserForm from "./components/acl/users/UserForm";
import Roles from "./components/acl/roles/Roles";
import RoleForm from "./components/acl/roles/RoleForm";
import UserWidget from "./components/acl/users/UserWidget";
import GeneralSettings from "./components/acl/settings/GeneralSettings";
import ModulesSettings from "./components/acl/settings/ModulesSettings";


Vue.component("app-login", Login);
Vue.component("app-users", Users);
Vue.component("app-user-form", UserForm);
Vue.component("app-roles", Roles);
Vue.component("app-role-form", RoleForm);
Vue.component("app-users-widget", UserWidget);
Vue.component("app-general-settings", GeneralSettings);
Vue.component("app-modules-settings", ModulesSettings);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import aclStore from "./stores/acl";
import hrmsStore from "./stores/hrms";


const app = new Vue({
    el: "#main-app",
    store: aclStore
});

let openPaths = [
    '/login',
    '/test',
    '/reset-password',
    '/service',
];

if (!openPaths.includes(location.pathname)) {
    aclStore.dispatch("getUser").then((data) => {
        return hrmsStore.dispatch("GET_GENERAL_SETTINGS");
    }).then((data) => {
        // if (!window.GET_USER_INTERVAL) {
        //     window.GET_USER_INTERVAL = setInterval(async () => {
        //         await aclStore.dispatch("getUser");
        //         await aclStore.dispatch("GET_GENERAL_SETTINGS");
        //     }, 60000);
        // }
    }).catch(error => {
        console.error(error);
    });
}
