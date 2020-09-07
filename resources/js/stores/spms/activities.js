import axios from 'axios';
import routes from "../../routes";
import {deepClone, prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        activities: [],
        activityPerformance: [],
    },
    getters: {
        ACTIVITIES(state) {
            return state.activities;
        },
        ACTIVITY_PERFORMANCE(state) {
            return state.activityPerformance;
        },
    },
    mutations: {
        SET_ACTIVITIES(state, payload) {
            state.activities = payload;
        },
        SET_ACTIVITY_PERFORMANCE(state, payload) {
            state.activityPerformance = payload;
        },
    },
    actions: {
        async GET_ACTIVITIES({commit}, payload = {}) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.ACTIVITIES + queryParams);
                commit('SET_ACTIVITIES', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_ACTIVITY({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.ACTIVITIES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.ACTIVITIES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_STAGE({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.STAGES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.STAGES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_TASK({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.TASKS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.TASKS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async GET_ACTIVITY_PERFORMANCE({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.ACTIVITIES + '/performance' + queryParams);
                let data = response.data;
                commit("SET_ACTIVITY_PERFORMANCE", data);
                return Promise.resolve(deepClone(data));
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_ACTIVITY_PERFORMANCE({commit}, payload) {
            try {
                let response = await axios.post(routes.ACTIVITIES + '/performance', payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        }
    },
}
