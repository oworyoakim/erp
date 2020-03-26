import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        experiences: [],
    },
    getters: {
        GET_EXPERIENCES: (state) => {
            return state.experiences;
        },
    },
    mutations: {
        SET_EXPERIENCES: (state, payload) => {
            state.experiences = payload || [];
        },
    },
    actions: {
        GET_EXPERIENCES: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.EMPLOYEES_EXPERIENCE + '?employee_id=' + payload.employee_id);
                commit('SET_EXPERIENCES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_EXPERIENCE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES_EXPERIENCE, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES_EXPERIENCE, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
