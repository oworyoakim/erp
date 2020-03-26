import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        sections: [],
    },
    getters: {
        GET_SECTIONS: (state) => {
            return state.sections;
        },
    },
    mutations: {
        SET_SECTIONS: (state, payload) => {
            state.sections = payload || [];
        },
    },
    actions: {
        GET_SECTIONS: async ({commit}, payload) => {
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
                if (!!payload.scope) {
                    params.push('scope=' + payload.scope);
                }
                let queryParam = params.join('&');
                let response = await axios.get(routes.SECTIONS_JSON + '?' + queryParam);
                commit('SET_SECTIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_SECTION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.SECTIONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.SECTIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_SECTION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.SECTIONS + '?section_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
