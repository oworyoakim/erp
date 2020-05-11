import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        banks: [],
    },
    getters: {
        BANKS: (state) => {
            return state.banks || [];
        },
    },
    mutations: {
        SET_BANKS: (state, payload) => {
            state.banks = payload;
        },
    },
    actions: {
        async GET_BANK_INFO({commit}, payload) {
            try {
                if (!payload.employeeId) {
                    return Promise.reject("Employee ID required!");
                }
                let queryParam = prepareQueryParams(payload);
                let response = await axios.get(routes.EMPLOYEES + '/bank' + queryParam);
                commit('SET_BANKS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async SAVE_BANK_INFO({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES + "/bank", payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES + "/bank", payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    },
    modules: {},
}
