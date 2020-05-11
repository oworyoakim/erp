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
        employees: [],
        activeEmployee: null,
    },
    getters: {
        EMPLOYEES: (state) => {
            return state.employees;
        },
        ACTIVE_EMPLOYEE: (state) => {
            return state.activeEmployee;
        },
    },
    mutations: {
        SET_EMPLOYEES: (state, payload) => {
            state.employees = payload || [];
        },
        SET_ACTIVE_EMPLOYEE: (state, payload) => {
            state.activeEmployee = payload || null;
        },
    },
    actions: {
        GET_EMPLOYEES: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.EMPLOYEES + "/all-json" + queryParams);
                commit('SET_EMPLOYEES', response.data);
                return Promise.resolve('Ok');
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
        UPDATE_PROFILE: async ({commit}, payload) => {
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
