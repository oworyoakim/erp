import routes from "../../routes";
import {deepClone, resolveError} from "../../utils/helpers";
import axios from 'axios';

export default {
    state: {
        users: [],
        modules: [],
        modulesAccessSettings: null,
    },
    getters: {
        USERS(state) {
            return state.users;
        },
        MODULES(state) {
            return state.modules;
        },
        MODULES_ACCESS_SETTINGS(state) {
            return state.modulesAccessSettings;
        },
    },
    mutations: {
        SET_USERS(state, payload) {
            state.users = payload;
        },
        SET_MODULES_ACCESS_SETTINGS(state, payload) {
            state.modulesAccessSettings = payload;
        },
    },
    actions: {
        async GET_USERS({commit})  {
            try {
                let response = await axios.get(routes.USERS + '/all-json');
                commit("SET_USERS", response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async SAVE_USER({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    response = await axios.put(routes.USERS, payload);
                } else {
                    response = await axios.post(routes.USERS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async ACTIVATE_USER ({commit}, payload) {
            try {
                let response = await axios.patch(routes.USERS + '/activate', payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async DEACTIVATE_USER({commit}, payload){
            try {
                let response = await axios.patch(routes.USERS + '/deactivate', payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async GET_MODULES_ACCESS_SETTINGS ({commit}) {
            try {
                let response = await axios.get(routes.MODULES_SETTINGS + '/list');
                commit("SET_MODULES_ACCESS_SETTINGS", deepClone(response.data));
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async UPDATE_MODULES_ACCESS_SETTINGS({commit}, payload){
            try {
                let response = await axios.patch(routes.MODULES_SETTINGS, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    },
}
