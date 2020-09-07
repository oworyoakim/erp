import axios from 'axios';
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        objectives: [],
        objective: null,
    },
    getters: {
        OBJECTIVES: (state) => {
            return state.objectives || [];
        },
        OBJECTIVE_DETAILS: (state) => {
            return state.objective;
        },
    },
    mutations: {
        SET_OBJECTIVES: (state, payload) => {
            state.objectives = payload;
        },
        SET_OBJECTIVE_DETAILS: (state, payload) => {
            state.objective = payload;
        },
    },
    actions: {
        GET_OBJECTIVES: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.OBJECTIVES + queryParams);
                commit('SET_OBJECTIVES', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        SAVE_OBJECTIVE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OBJECTIVES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OBJECTIVES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        GET_OBJECTIVE_DETAILS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.OBJECTIVES + '/details?objectiveId=' + payload);
                commit('SET_OBJECTIVE_DETAILS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        SAVE_INTERVENTION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.INTERVENTIONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.INTERVENTIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    },
}
