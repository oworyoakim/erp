import axios from 'axios';
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        outputs: [],
        outputIndicators: [],
        outputAchievements: [],
    },
    getters: {
        OUTPUTS(state) {
            return state.outputs;
        },
        OUTPUT_INDICATORS(state) {
            return state.outputIndicators || [];
        },
        OUTPUT_ACHIEVEMENTS(state) {
            return state.outputAchievements || [];
        }
    },
    mutations: {
        SET_OUTPUTS(state, payload) {
            state.outputs = payload;
        },
        SET_OUTPUT_INDICATORS(state, payload) {
            state.outputIndicators = payload;
        },
        SET_OUTPUT_ACHIEVEMENTS(state, payload) {
            state.outputAchievements = payload;
        },
    },
    actions: {
        async GET_OUTPUTS({commit}, payload = {}) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.OUTPUTS + queryParams);
                commit('SET_OUTPUTS', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_OUTPUT({commit}, payload) {
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

        async SAVE_OUTPUT_INDICATOR({commit}, payload) {
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

        async SAVE_OUTPUT_INDICATOR_TARGET({commit}, payload) {
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

        async GET_OUTPUT_INDICATORS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.OUTPUT_INDICATORS + queryParams);
                commit("SET_OUTPUT_INDICATORS",response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async GET_OUTPUT_ACHIEVEMENTS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.OBJECTIVES + '/achievements' + queryParams);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_OUTPUT_ACHIEVEMENT({commit}, payload) {
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
