import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        divisions: [],
        activeDivision: null,
    },
    getters: {
        DIVISIONS: (state) => {
            return state.divisions;
        },
        ACTIVE_DIVISION: (state) => {
            return state.activeDivision;
        },
    },
    mutations: {
        SET_DIVISIONS: (state, payload) => {
            state.divisions = payload || [];
        },
        SET_ACTIVE_DIVISION: (state, payload) => {
            state.activeDivision = payload || null;
        },
    },
    actions: {
        GET_DIVISIONS: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DIVISIONS + '/all-json' + queryParams);
                commit('SET_DIVISIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },

        GET_ACTIVE_DIVISION: async ({commit}, payload) => {
            try {
                let queryParam = prepareQueryParams(payload);
                let response = await axios.get(routes.DIVISIONS + '/details' + queryParam);
                commit('SET_ACTIVE_DIVISION', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
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
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DELETE_DIVISION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DIVISIONS + '?divisionId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    },
};
