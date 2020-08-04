import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        directivesAndResolutions: [],
        directivesAndResolutionsReport: null,
    },
    getters: {
        DIRECTIVES_AND_RESOLUTIONS(state) {
            return state.directivesAndResolutions;
        },
        DIR_AND_RES_REPORT_DATA(state) {
            return state.directivesAndResolutionsReport;
        },
    },
    mutations: {
        SET_DIRECTIVES_AND_RESOLUTIONS(state, payload) {
            state.directivesAndResolutions = payload || [];
        },
        SET_DIR_AND_RES_REPORT(state, payload) {
            state.directivesAndResolutionsReport = payload;
        },
    },
    actions: {
        async GET_DIRECTIVES_AND_RESOLUTIONS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DIRECTIVES_AND_RESOLUTIONS + queryParams);
                commit("SET_DIRECTIVES_AND_RESOLUTIONS", response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_DIRECTIVE_AND_RESOLUTION({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    response = await axios.put(routes.DIRECTIVES_AND_RESOLUTIONS, payload);
                } else {
                    response = await axios.post(routes.DIRECTIVES_AND_RESOLUTIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async GET_DIRECTIVE_AND_RESOLUTION_PARAMS({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DIRECTIVES_AND_RESOLUTIONS + '/selection-options' + queryParams);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async LOAD_DIR_AND_RES_REPORT({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.PLANS + '/monitor/directives-and-resolutions/report' + queryParams);
                commit("SET_DIR_AND_RES_REPORT", response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async GET_RESPONSIBILITY_CENTER({commit}, payload) {
            try {
                let response = await axios.get(routes.DIRECTORATES + '/details?directorateId=' + payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    },
}
