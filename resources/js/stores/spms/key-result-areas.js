import axios from 'axios';
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        keyResultAreas: [],
        outcomeAchievements: [],
        keyResultArea: null,
    },
    getters: {
        KEY_RESULT_AREAS(state) {
            return state.keyResultAreas || [];
        },
        KEY_RESULT_AREA_DETAILS(state) {
            return state.keyResultArea;
        },
        OUTCOME_ACHIEVEMENTS(state) {
            return state.outcomeAchievements || [];
        }
    },
    mutations: {
        SET_KEY_RESULT_AREAS(state, payload) {
            state.keyResultAreas = payload;
        },
        SET_KEY_RESULT_AREA_DETAILS(state, payload) {
            state.keyResultArea = payload;
        },
        SET_OUTCOME_ACHIEVEMENTS(state, payload) {
            state.outcomeAchievements = payload;
        },
    },
    actions: {
        async GET_KEY_RESULT_AREAS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.KEY_RESULT_AREAS + queryParams);
                commit('SET_KEY_RESULT_AREAS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async GET_KEY_RESULT_AREA_DETAILS({commit}, payload) {
            try {
                let response = await axios.get(routes.KEY_RESULT_AREAS + '/details?keyResultAreaId=' + payload);
                commit('SET_KEY_RESULT_AREA_DETAILS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async GET_OUTCOME_ACHIEVEMENTS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.KEY_RESULT_AREAS + '/achievements' + queryParams);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async SAVE_KEY_RESULT_AREA({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.KEY_RESULT_AREAS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.KEY_RESULT_AREAS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async SAVE_OUTCOME({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OUTCOMES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OUTCOMES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async SAVE_OUTCOME_INDICATOR({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OUTCOME_INDICATORS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OUTCOME_INDICATORS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async SAVE_OUTCOME_INDICATOR_TARGET({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OUTCOME_TARGETS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OUTCOME_TARGETS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async SAVE_OUTCOME_ACHIEVEMENT({commit}, payload) {
            try {
                let response = await axios.post(routes.OUTCOME_ACHIEVEMENTS, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        SET_OUTCOME_ACHIEVEMENTS({commit}, payload) {
            commit("SET_OUTCOME_ACHIEVEMENTS", payload);
        }
    },
}
