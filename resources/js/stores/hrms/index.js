import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import routes from "../../routes";
import generalSettingsModule from "../general-settings";
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
import contactsModule from './contacts';
import {resolveError} from "../../utils/helpers";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        generalSettings: generalSettingsModule,
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
        contacts: contactsModule,
    },
    state: {
        user: null,
        formSelections: {
            directorates: [],
            departments: [],
            divisions: [],
            sections: [],
            designations: [],
            roles: [],
            usernames: [],
            emails: [],
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
            salaryScales: [],
            nextEmployeeId: '0001',
        },
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
        dobConfig: {
            showDropdowns: true,
            singleDatePicker: true,
            minDate: moment().subtract(90, 'years'), // 90 years ago
            maxDate: moment().subtract(17, 'years'), // 17 years ago
            opens: "center",
            locale: {
                format: 'YYYY-MM-DD'
            }
        },
        joinDateConfig: {
            showDropdowns: true,
            singleDatePicker: true,
            maxDate: moment(), // today
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
        TINYMCE_API_KEY: (state) => {
            if(!state.user){
                return 'no-api-key';
            }
            return state.user.tinymceApiKey || 'no-api-key';
        },
        EDITOR_CONFIG(state){
            return state.editorConfig;
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
        HAS_ACCESS(state){
            return (permission = '') => {
                return !!permission && !!state.user && !!state.user.permissions[permission];
            }
        },
        HAS_ANY_ACCESS(state){
            return (permissions = []) => {
                return !!state.user && permissions.some((permission) => !!state.user.permissions[permission]);
            }
        },
    },
    mutations: {
        setUser: (state, payload) => {
            state.user = payload;
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
                let message = resolveError(error);
                return Promise.reject(message);
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
    }
});
