import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        employeeDocuments: [],
    },
    getters: {
        EMPLOYEE_DOCUMENTS: (state) => {
            return state.employeeDocuments;
        },
    },
    mutations: {
        SET_EMPLOYEE_DOCUMENTS: (state, payload) => {
            state.employeeDocuments = payload || [];
        },
    },
    actions: {
        GET_EMPLOYEE_DOCUMENTS: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.EMPLOYEE_DOCUMENTS + '/all-json' + queryParams);
                commit('SET_EMPLOYEE_DOCUMENTS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        SAVE_EMPLOYEE_DOCUMENT: async ({commit}, payload) => {
            try {
                let response = await axios.post(routes.EMPLOYEE_DOCUMENTS, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
};
