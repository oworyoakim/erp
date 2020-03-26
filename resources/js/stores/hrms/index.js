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
        relatedPersons: [],
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
        getSalaryScales: (state) => {
            return state.salaryScales;
        },
        getRelatedPersons: (state) => {
            return state.relatedPersons;
        },
    },
    mutations: {
        setUser: (state, payload) => {
            state.user = payload;
        },
        setGeneralSettings: (state, payload) => {
            state.generalSettings = payload;
        },
        setFormSelections: (state, payload) => {
            state.formSelections = payload;
        },
        setSalaryScales: (state, payload) => {
            state.salaryScales = payload || [];
        },
        setRelatedPersons: (state, payload) => {
            state.relatedPersons = payload || [];
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

        getRelatedPersons: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.EMPLOYEES_RELATED_PERSONS + '?employee_id=' + payload.employee_id);
                commit('setRelatedPersons', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
        saveRelatedPerson: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES_RELATED_PERSONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES_RELATED_PERSONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },

        saveApplicationSetting: async ({commit}, payload) => {
            try {
                let response = await axios.post(routes.APPROVAL_SETTINGS, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
    }
});
