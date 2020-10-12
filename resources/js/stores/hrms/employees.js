import axios from "axios";
import routes from "../../routes";
import educationsModule from './education';
import experiencesModule from './experiences';
import bankModule from './bank';
import relatedPersonsModule from './related-persons';
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    modules: {
        educations: educationsModule,
        experiences: experiencesModule,
        bank: bankModule,
        relatedPersons: relatedPersonsModule,
    },
    state: {
        employees: {
            currentPage: 1,
            nextPage: null,
            previousPage: null,
            firstPage: 1,
            lastPage: null,
            from: null,
            to: null,
            perPage: null,
            hasPages: null,
            hasMorePages: null,
            total: 0,
            data: []
        },
        activeEmployee: null,
    },
    getters: {
        EMPLOYEES(state) {
            return state.employees;
        },
        ACTIVE_EMPLOYEE(state) {
            return state.activeEmployee;
        },
    },
    mutations: {
        SET_EMPLOYEES(state, payload) {
            state.employees = payload || [];
        },
        SET_ACTIVE_EMPLOYEE(state, payload) {
            state.activeEmployee = payload || null;
        },
    },
    actions: {
        async GET_EMPLOYEES({commit, state}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                // if (!!state.employees.nextPage) {
                //     if (!!queryParams) {
                //         queryParams += '&page' + state.employees.nextPage;
                //     } else {
                //         queryParams += '?page' + state.employees.nextPage;
                //     }
                // }
                let response = await axios.get(routes.EMPLOYEES + "/all-json" + queryParams);
                commit('SET_EMPLOYEES', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        GET_ACTIVE_EMPLOYEE: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.EMPLOYEES + '/details' + queryParams);
                commit('SET_ACTIVE_EMPLOYEE', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        SAVE_EMPLOYEE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DELETE_EMPLOYEE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.EMPLOYEES + '?employeeId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        GET_EMPLOYEE_PROFILE: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.EMPLOYEES + '/profile-data' + queryParams);
                commit('SET_ACTIVE_EMPLOYEE', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async UPDATE_PROFILE({commit}, payload) {
            try {
                let response = await axios.patch(routes.EMPLOYEES + '/profile/' + payload.username, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        UPLOAD_PROFILE_PICTURE: async ({commit}, payload) => {
            try {
                let response = await axios.post(routes.EMPLOYEES + '/upload-profile-picture', payload, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                return Promise.resolve(response.data);
            } catch (error) {
                axios.defaults.headers.common['Content-Type'] = 'application/json';
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
};
