import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        divisions: [],
    },
    getters: {
        GET_DIVISIONS: (state) => {
            return state.divisions;
        },
    },
    mutations: {
        SET_DIVISIONS: (state, payload) => {
            state.divisions = payload || [];
        },
    },
    actions: {
        GET_DIVISIONS: async ({commit}, payload) => {
            try {
                let params = [];
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
                let response = await axios.get(routes.DIVISIONS_JSON + '?' + queryParam);
                commit('SET_DIVISIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_DIVISION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.DIVISIONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.DIVISIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_DIVISION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DIVISIONS + '?division_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    },
};
