import routes from "../../routes";
import axios from 'axios';

export default {
    state: {
        roles: [],
        permissions: [],
    },
    getters: {
        ROLES: (state) => {
            return state.roles;
        },
        PERMISSIONS: (state) => {
            return state.permissions;
        },
    },
    mutations: {
        SET_ROLES: (state, payload) => {
            state.roles = payload;
        },
        SET_PERMISSIONS: (state, payload) => {
            state.permissions = payload;
        },
    },
    actions: {
        GET_ROLES: async ({commit}) => {
            try {
                let response = await axios.get(routes.ROLES + '/all-json');
                commit("SET_ROLES", response.data.roles);
                commit("SET_PERMISSIONS", response.data.permissions);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        SAVE_ROLE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    response = await axios.put(routes.ROLES, payload);
                } else {
                    response = await axios.post(routes.ROLES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        UPDATE_PERMISSIONS: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.ROLES, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        DELETE_ROLE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.ROLES + '?role_id=' + payload);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
    },
}
