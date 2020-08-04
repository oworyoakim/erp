import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        dirAndResActivityOutputs: [],
    },
    getters: {
        DIR_AND_RES_ACTIVITY_OUTPUTS(state) {
            return state.dirAndResActivityOutputs || [];
        },
    },
    mutations: {
        SET_DIR_AND_RES_ACTIVITY_OUTPUTS(state, payload) {
            state.dirAndResActivityOutputs = payload || [];
        },
    },
    actions: {
        async GET_DIR_AND_RES_ACTIVITY_OUTPUTS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITY_OUTPUTS + queryParams);
                commit("SET_DIR_AND_RES_ACTIVITY_OUTPUTS", response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_DIR_AND_RES_ACTIVITY_OUTPUT({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    response = await axios.put(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITY_OUTPUTS, payload);
                } else {
                    response = await axios.post(routes.DIRECTIVES_AND_RESOLUTIONS_ACTIVITY_OUTPUTS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    },
}
