import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        employeeDocuments: [],
    },
    getters: {
        GET_EMPLOYEE_DOCUMENTS: (state) => {
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
                let params = [];
                if (!!payload.employee_id) {
                    params.push('employee_id=' + payload.employee_id);
                }
                let queryParams = params.join('&');
                let response = await axios.get(routes.EMPLOYEE_DOCUMENTS + '/all-json?' + queryParams);
                commit('SET_EMPLOYEE_DOCUMENTS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_EMPLOYEE_DOCUMENT: async ({commit}, payload) => {
            try {
                let response = await axios.post(routes.EMPLOYEE_DOCUMENTS, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
