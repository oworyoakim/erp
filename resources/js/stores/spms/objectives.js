import axios from 'axios';
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        objectives: [],
        outputAchievements: [],
        objective: null,
    },
    getters: {
        OBJECTIVES: (state) => {
            return state.objectives || [];
        },
        OBJECTIVE_DETAILS: (state) => {
            return state.objective;
        },
        OUTPUT_ACHIEVEMENTS: (state) => {
            return state.outputAchievements || [];
        }
    },
    mutations: {
        SET_OBJECTIVES: (state, payload) => {
            state.objectives = payload;
        },
        SET_OBJECTIVE_DETAILS: (state, payload) => {
            state.objective = payload;
        },
        SET_OUTPUT_ACHIEVEMENTS: (state, payload) => {
            state.outputAchievements = payload;
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

        GET_OUTPUT_ACHIEVEMENTS: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.OBJECTIVES + '/achievements' + queryParams);
                return Promise.resolve(response.data);
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
        SAVE_OUTPUT: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OUTPUTS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OUTPUTS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        SAVE_OUTPUT_INDICATOR: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OUTPUT_INDICATORS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OUTPUT_INDICATORS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        SAVE_OUTPUT_INDICATOR_TARGET: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OUTPUT_TARGETS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OUTPUT_TARGETS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        SAVE_OUTPUT_ACHIEVEMENT: async ({commit}, payload) => {
            try {
                let response = await axios.post(routes.OUTPUT_ACHIEVEMENTS, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        SET_OUTPUT_ACHIEVEMENTS({commit}, payload) {
            commit("SET_OUTPUT_ACHIEVEMENTS", payload);
        }
    },
}
