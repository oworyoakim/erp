import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        designations: [],
    },
    getters: {
        GET_DESIGNATIONS: (state) => {
            return state.designations;
        },
    },
    mutations: {
        SET_DESIGNATIONS: (state, payload) => {
            state.designations = payload || [];
        },
    },
    actions: {
        GET_DESIGNATIONS: async ({commit}, payload) => {
            try {
                let params = [];
                if (!!payload.salary_scale_id) {
                    params.push('salary_scale_id=' + payload.salary_scale_id);
                }
                if (!!payload.section_id) {
                    params.push('section_id=' + payload.section_id);
                }
                if (!!payload.department_id) {
                    params.push('department_id=' + payload.department_id);
                }
                if (!!payload.directorate_id) {
                    params.push('directorate_id=' + payload.directorate_id);
                }
                if (!!payload.scope) {
                    params.push('scope=' + payload.scope);
                }
                let queryParam = params.join('&');
                let response = await axios.get(routes.DESIGNATIONS_JSON + '?' + queryParam);
                commit('SET_DESIGNATIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
        SAVE_DESIGNATION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.DESIGNATIONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.DESIGNATIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_DESIGNATION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DESIGNATIONS + '?designation_id=' + payload);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
