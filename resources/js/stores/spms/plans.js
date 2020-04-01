import axios from 'axios';
import routes from "../../routes";

export default {
    state: {
        plans: [],
        activePlan: null,
    },
    getters: {
        PLANS: (state) => {
            return state.plans;
        },
        ACTIVE_PLAN: (state) => {
            return state.activePlan;
        }
    },
    mutations: {
        SET_PLANS: (state, payload) => {
            state.plans = payload;
        },
        SET_ACTIVE_PLAN: (state, payload) => {
            state.activePlan = payload;
        },
    },
    actions: {
        GET_PLANS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.PLANS + '/all-json');
                commit('SET_PLANS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_PLAN: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.PLANS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.PLANS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        SET_ACTIVE_PLAN: async ({commit}, payload) => {
            commit("SET_ACTIVE_PLAN", payload);
        },
    },
}
