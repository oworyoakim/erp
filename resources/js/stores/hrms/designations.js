import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

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
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DESIGNATIONS + '/all-json' + queryParams);
                commit('SET_DESIGNATIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
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
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DELETE_DESIGNATION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DESIGNATIONS + '?designationId=' + payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
};
