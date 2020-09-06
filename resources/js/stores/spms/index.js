import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

import routes from "../../routes";
import usersModule from '../acl/users';
import rolesModule from '../acl/roles';
import plansModule from './plans';
import objectivesModule from "./objectives";
import keyResultAreasModule from "./key-result-areas";
import swotsModule from "./swots";
import activitiesModule from "./activities";
import outputsModule from "./outputs";
import workPlansModule from "./work-plans";
import directivesAndResolutionsModule from "./directives-and-resolutions";
import dirAndResActivitiesModule from "./directives-and-resolutions-activities";
import dirAndResActivityOutputsModule from "./directives-and-resolutions-outputs";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default new Vuex.Store({
    modules: {
        roles: rolesModule,
        users: usersModule,
        plans: plansModule,
        objectives: objectivesModule,
        keyResultAreas: keyResultAreasModule,
        swots: swotsModule,
        activities: activitiesModule,
        outputs: outputsModule,
        workPlans: workPlansModule,
        directivesAndResolutions: directivesAndResolutionsModule,
        dirAndResActivities: dirAndResActivitiesModule,
        dirAndResActivityOutputs: dirAndResActivityOutputsModule,
    },
    state: {
        user: null,
        generalSettings: [],
        formSelections: {
            roles: [],
            usernames: [],
            documentCategories: [],
            documentTypes: [],
        },
        directoratesOptions: [],
        departmentsOptions: [],
        editorConfig: {
            media_live_embeds: true,
            menubar: "insert edit format",
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks fullscreen',
                'insertdatetime media table paste hr code wordcount',
                'toc'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image video code|toc'
        },
        singleDateConfig: {
            showDropdowns: true,
            singleDatePicker: true,
            minDate: null,
            maxDate: null,
            opens: "center",
            locale: {
                format: 'YYYY-MM-DD'
            }
        },
        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    },
    getters: {
        getUser: (state) => {
            return state.user;
        },
        getGeneralSettings: (state) => {
            return state.generalSettings;
        },
        getRole: (state) => {
            return state.user ? state.user.role : null;
        },
        getMonths: (state) => {
            return state.months;
        },
        getFormSelections: (state) => {
            return state.formSelections;
        },
        TINYMCE_API_KEY: (state) => {
            if (!state.user) {
                return 'no-api-key';
            }
            return state.user.tinymceApiKey || 'no-api-key';
        },
        EDITOR_CONFIG(state) {
            return state.editorConfig;
        },
        DIRECTORATES_OPTIONS(state) {
            return state.directoratesOptions;
        },
        DEPARTMENTS_OPTIONS(state) {
            return state.departmentsOptions;
        },
        SINGLE_DATE_CONFIG(state) {
            return state.singleDateConfig;
        },
        HAS_ACCESS(state) {
            return (permission = '') => {
                return !!permission && !!state.user && !!state.user.permissions[permission];
            }
        },
        HAS_ANY_ACCESS(state) {
            return (permissions = []) => {
                return !!state.user && permissions.some((permission) => !!state.user.permissions[permission]);
            }
        },
    },
    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },
        setDashboardStatistics(state, payload) {
            state.dashboardStatistics = payload;
        },
        setGeneralSettings(state, payload) {
            state.generalSettings = payload;
        },
        SET_DIRECTORATES_OPTIONS(state, payload) {
            state.directoratesOptions = payload;
        },
        SET_DEPARTMENTS_OPTIONS(state, payload) {
            state.departmentsOptions = payload;
        },
    },
    actions: {
        getUser: async ({commit}) => {
            try {
                let response = await axios.get('/user-data');
                commit('setUser', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        getFormSelections: async ({commit}, payload) => {
            try {
                let params = [];
                if (!!payload.directorate_id) {
                    params.push('directorate_id=' + payload.directorate_id);
                }
                if (!!payload.department_id) {
                    params.push('department_id=' + payload.department_id);
                }
                if (!!payload.division_id) {
                    params.push('division_id=' + payload.division_id);
                }
                if (!!payload.section_id) {
                    params.push('section_id=' + payload.section_id);
                }
                if (!!payload.scope) {
                    params.push('scope=' + payload.scope);
                }
                let queryParams = params.join('&');
                let response = await axios.get(routes.EMPLOYEES_ATTRIBUTES + '?' + queryParams);
                commit('setFormSelections', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
        getGeneralSettings: async ({commit}) => {
            try {
                let response = await axios.get(routes.GENERAL_SETTINGS_JSON);
                commit('setGeneralSettings', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.log(error.response);

                return Promise.reject(error.response.data);
            }
        },
        async GET_DIRECTORATES_OPTIONS({commit}) {
            try {
                let response = await axios.get('/hrms/directorates/unscoped');
                commit('SET_DIRECTORATES_OPTIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async GET_DEPARTMENTS_OPTIONS({commit}) {
            try {
                let response = await axios.get('/hrms/departments/unscoped');
                commit('SET_DEPARTMENTS_OPTIONS', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async GET_EMPLOYEE_OPTIONS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get('/hrms/employees/unscoped' + queryParams);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    }
});
