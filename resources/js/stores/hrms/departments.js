import axios from "axios";
import routes from "../../routes";

export default {
    state:{
        departments: [],
    },
    getters:{
        GET_DEPARTMENTS: (state) => {
            return state.departments;
        },
    },
    mutations:{
        SET_DEPARTMENTS: (state, payload) => {
            state.departments = payload || [];
        },
    },
    actions:{
        GET_DEPARTMENTS: async ({commit}, payload) => {
            try {
                let params = [];
                if (!!payload.directorate_id) {
                    params.push('directorate_id=' + payload.directorate_id);
                }
                if (!!payload.scope) {
                    params.push('scope=' + payload.scope);
                }
                let queryParams = params.join('&');
                let response = await axios.get(routes.DEPARTMENTS_JSON + '?' + queryParams);
                commit('SET_DEPARTMENTS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_DEPARTMENT: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.DEPARTMENTS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.DEPARTMENTS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
        DELETE_DEPARTMENT: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DEPARTMENTS + '?department_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
    },
};
