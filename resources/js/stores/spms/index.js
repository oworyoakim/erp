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
import workPlansModule from "./work-plans";

export default new Vuex.Store({
    modules: {
        roles: rolesModule,
        users: usersModule,
        plans: plansModule,
        objectives: objectivesModule,
        keyResultAreas: keyResultAreasModule,
        swots: swotsModule,
        workPlans: workPlansModule,
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
    },
    mutations: {
        setUser: (state, payload) => {
            state.user = payload;
        },
        setDashboardStatistics: (state, payload) => {
            state.dashboardStatistics = payload;
        },
        setGeneralSettings: (state, payload) => {
            state.generalSettings = payload;
        },
    },
    actions: {
        getUser: async ({commit}) => {
            try {
                let response = await axios.get('/user-data');
                commit('setUser', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.log(error.response);
                if (error.response && error.response.status === 401) {
                    //toastr.error('Session Expired!');
                    toastr.error(error.response.data);
                    location.reload();
                }
                return Promise.reject(error.response.data);
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

    }
});
