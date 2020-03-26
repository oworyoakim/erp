import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        educations: [],
    },
    getters: {
        GET_EDUCATIONS: (state) => {
            return state.educations;
        },
    },
    mutations: {
        SET_EDUCATIONS: (state, payload) => {
            state.educations = payload || [];
        },
    },
    actions: {
        GET_EDUCATIONS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.EMPLOYEES_EDUCATION + '?employee_id=' + payload.employee_id);
                commit('SET_EDUCATIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_EDUCATION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES_EDUCATION, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES_EDUCATION, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
