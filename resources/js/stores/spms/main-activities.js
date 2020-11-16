import axios from 'axios';
import routes from "../../routes";
import {deepClone, prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        mainActivities: [],
        activeMainActivity: null,
    },
    getters: {
        MAIN_ACTIVITIES(state) {
            return state.mainActivities;
        },
        ACTIVE_MAIN_ACTIVITY(state) {
            return state.activeMainActivity;
        },
    },
    mutations: {
        SET_MAIN_ACTIVITIES(state, payload) {
            state.mainActivities = payload;
        },
        SET_ACTIVE_MAIN_ACTIVITY(state, payload) {
            state.activeMainActivity = payload;
        },
    },
    actions: {
        async GET_MAIN_ACTIVITIES({commit}, payload = {}) {
            try {
                commit('SET_LOADER', true);
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.MAIN_ACTIVITIES + queryParams);
                commit('SET_MAIN_ACTIVITIES', response.data);
                commit('SET_LOADER', false);
                return Promise.resolve(response.data);
            } catch (error) {
                commit('SET_LOADER', false);
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_MAIN_ACTIVITY({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.MAIN_ACTIVITIES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.MAIN_ACTIVITIES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    },
}
