import axios from "axios";
import routes from "../../routes";
import {resolveError} from "../../utils/helpers";

export default {
    state: {
        directorates: [],
        activeDirectorate: null,
    },
    getters: {
        DIRECTORATES: (state) => {
            return state.directorates;
        },

        ACTIVE_DIRECTORATE: (state) => {
            return state.activeDirectorate;
        },
    },
    mutations: {
        SET_DIRECTORATES: (state, payload) => {
            state.directorates = payload || [];
        },

        SET_ACTIVE_DIRECTORATE: (state, payload) => {
            state.activeDirectorate = payload || null;
        },
    },
    actions: {
        GET_DIRECTORATES: async ({commit}) => {
            try {
                let response = await axios.get(routes.DIRECTORATES + '/all-json');
                commit('SET_DIRECTORATES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },

        GET_ACTIVE_DIRECTORATE: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.DIRECTORATES + '/details?directorateId=' + payload);
                commit('SET_ACTIVE_DIRECTORATE', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        SAVE_DIRECTORATE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.DIRECTORATES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.DIRECTORATES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DELETE_DIRECTORATE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DIRECTORATES + '?directorateId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    },
};
