import routes from "../../routes";
import {resolveError} from "../../utils/helpers";

export default {
    state: {
        users: [],
    },
    getters: {
        USERS: (state) => {
            return state.users;
        },
    },
    mutations: {
        SET_USERS: (state, payload) => {
            state.users = payload;
        },
    },
    actions: {
        GET_USERS: async ({commit}) => {
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

        SAVE_USER: async ({commit}, payload) => {
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
    },
}
