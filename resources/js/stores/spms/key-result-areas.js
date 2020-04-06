import axios from 'axios';
import routes from "../../routes";

export default {
    state: {
        keyResultAreas: [],
        keyResultArea: null,
    },
    getters: {
        KEY_RESULT_AREAS: (state) => {
            return state.keyResultAreas || [];
        },
        KEY_RESULT_AREA_DETAILS: (state) => {
            return state.keyResultArea;
        }
    },
    mutations: {
        SET_KEY_RESULT_AREAS: (state, payload) => {
            state.keyResultAreas = payload;
        },
        SET_KEY_RESULT_AREA_DETAILS: (state, payload) => {
            state.keyResultArea = payload;
        },
    },
    actions: {
        GET_KEY_RESULT_AREAS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.KEY_RESULT_AREAS + '?planId=' + payload);
                commit('SET_KEY_RESULT_AREAS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        SAVE_KEY_RESULT_AREA: async ({commit}, payload) => {
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
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        GET_KEY_RESULT_AREA_DETAILS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.KEY_RESULT_AREAS + '/details?keyResultAreaId=' + payload);
                commit('SET_KEY_RESULT_AREA_DETAILS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        SAVE_OUTCOME: async ({commit}, payload) => {
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
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_OUTCOME_INDICATOR: async ({commit}, payload) => {
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
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_OUTCOME_INDICATOR_TARGET: async ({commit}, payload) => {
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
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_OUTCOME_ACHIEVEMENT: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OUTCOME_ACHIEVEMENTS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OUTCOME_ACHIEVEMENTS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
    },
}
