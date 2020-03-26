import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        delegations: [],
    },
    getters: {
        GET_DELEGATIONS: (state) => {
            return state.delegations;
        },
    },
    mutations: {
        SET_DELEGATIONS: (state, payload) => {
            state.delegations = payload || [];
        },
    },
    actions: {
        GET_DELEGATIONS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.DELEGATIONS);
                commit('SET_DELEGATIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_DELEGATION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.DELEGATIONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.DELEGATIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
