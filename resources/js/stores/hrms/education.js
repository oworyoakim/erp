import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        educations: [],
    },
    getters: {
        EDUCATIONS: (state) => {
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
                if (!payload.employeeId) {
                    return Promise.reject("Employee ID required!");
                }
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.EMPLOYEES + "/education" + queryParams);
                commit('SET_EDUCATIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        SAVE_EDUCATION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES + "/education", payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES + "/education", payload);
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
