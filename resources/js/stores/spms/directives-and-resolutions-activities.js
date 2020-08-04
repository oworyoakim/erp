import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        dirAndResActivities: [],
    },
    getters: {
        DIR_AND_RES_ACTIVITIES(state) {
            return state.dirAndResActivities || [];
        },
    },
    mutations: {
        SET_DIR_AND_RES_ACTIVITIES(state, payload) {
            state.dirAndResActivities = payload || [];
        },
    },
    actions: {
        async GET_DIR_AND_RES_ACTIVITIES({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITIES + queryParams);
                commit("SET_DIR_AND_RES_ACTIVITIES", response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_DIR_AND_RES_ACTIVITY({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    response = await axios.put(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITIES, payload);
                } else {
                    response = await axios.post(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITIES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async UPDATE_DIR_AND_RES_ACTIVITY({commit,dispatch}, payload) {
            try {
                let response = await axios.patch(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITIES, payload);
                dispatch('GET_DIR_AND_RES_ACTIVITIES',{directiveAndResolutionId: payload.directiveAndResolutionId});
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async COMPLETE_DIR_AND_RES_ACTIVITY({commit,dispatch}, payload) {
            try {
                let response = await axios.patch(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITIES + '/complete', payload);
                dispatch('GET_DIR_AND_RES_ACTIVITIES',{directiveAndResolutionId: payload.directiveAndResolutionId});
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    },
}
