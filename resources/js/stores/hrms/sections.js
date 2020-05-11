import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        sections: [],
    },
    getters: {
        SECTIONS: (state) => {
            return state.sections;
        },
    },
    mutations: {
        SET_SECTIONS: (state, payload) => {
            state.sections = payload || [];
        },
    },
    actions: {
        GET_SECTIONS: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.SECTIONS + '/all-json' +  queryParams);
                commit('SET_SECTIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        SAVE_SECTION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.SECTIONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.SECTIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DELETE_SECTION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.SECTIONS + '?sectionId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
};
