import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import routes from "../../routes";
import dashboardModule from './dashboard';
import departmentsModule from './departments';
import directoratesModule from './directorates';
import divisionsModule from './divisions';
import sectionsModule from './sections';
import designationsModule from './designations';
import employeesModule from './employees';
import leaveApplicationsModule from './leave-applications';
import leavesModule from './leaves';
import documentsModule from './documents';
import delegationsModule from './delegations';
import salaryScalesModule from './salary-scales';
import {resolveError} from "../../utils/helpers";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        dashboard: dashboardModule,
        departments: departmentsModule,
        directorates: directoratesModule,
        divisions: divisionsModule,
        sections: sectionsModule,
        designations: designationsModule,
        employees: employeesModule,
        leaveApplications: leaveApplicationsModule,
        leaves: leavesModule,
        documents: documentsModule,
        delegations: delegationsModule,
        salaryScales: salaryScalesModule,
    },
    state: {
        user: null,
        generalSettings: [],
        formSelections: {
            directorates: [],
            departments: [],
            divisions: [],
            sections: [],
            designations: [],
            roles: [],
            usernames: [],
            titles: [],
            genders: [],
            religions: [],
            relationships: [],
            maritalStatuses: [],
            employeeStatuses: [],
            employmentTypes: [],
            employmentTerms: [],
            employmentStatuses: [],
            documentCategories: [],
            documentTypes: [],
            leaveTypes: [],
            nextEmployeeId: '0001',
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
        MONTHS: (state) => {
            return state.months;
        },
        FORM_SELECTIONS_OPTIONS: (state) => {
            return state.formSelections;
        },
        getSalaryScales: (state) => {
            return state.salaryScales;
        },
    },
    mutations: {
        setUser: (state, payload) => {
            state.user = payload;
        },
        setGeneralSettings: (state, payload) => {
            state.generalSettings = payload;
        },
        SET_FORM_SELECTIONS_OPTIONS: (state, payload) => {
            state.formSelections = payload;
        },
        setSalaryScales: (state, payload) => {
            state.salaryScales = payload || [];
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
        GET_FORM_SELECTIONS_OPTIONS: async ({commit}, payload) => {
            try {
                let params = [];
                for (let filter in payload) {
                    if (!!payload[filter]) {
                        params.push(`${filter}=${payload[filter]}`);
                    }
                }
                let queryParams = params.join('&');
                let response = await axios.get(routes.EMPLOYEES_ATTRIBUTES + '?' + queryParams);
                commit('SET_FORM_SELECTIONS_OPTIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        getGeneralSettings: async ({commit}) => {
            try {
                let response = await axios.get(routes.GENERAL_SETTINGS_JSON);
                commit('setGeneralSettings', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },

        saveApplicationSetting: async ({commit}, payload) => {
            try {
                let response = await axios.post(routes.APPROVAL_SETTINGS, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
});
