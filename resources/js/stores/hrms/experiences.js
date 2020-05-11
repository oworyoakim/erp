import axios from "axios";
import routes from "../../routes";
import {resolveError} from "../../utils/helpers";

export default {
    state: {
        experiences: [],
    },
    getters: {
        EXPERIENCES: (state) => {
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
                if (!payload.employeeId) {
                    return Promise.reject("Employee ID required!");
                }
                let response = await axios.get(routes.EMPLOYEES_EXPERIENCE + '?employeeId=' + payload.employeeId);
                commit('SET_EXPERIENCES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
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
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
};
