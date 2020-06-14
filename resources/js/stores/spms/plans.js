import axios from 'axios';
import routes from "../../routes";

export default {
    state: {
        plans: [],
        activePlan: null,
    },
    getters: {
        PLANS(state) {
            return state.plans;
        },
        ACTIVE_PLAN (state, getters) {
            return state.activePlan || getters.DEFAULT_PLAN;
        },
        DEFAULT_PLAN(state) {
            if (state.plans.length === 0) {
                return null;
            }
            // try to pick the plan that is in execution
            let plan = state.plans.find((plan) => plan.status === 'execution');
            if (!plan) {
                // pick the first plan from the list
                plan = state.plans[0];
            }
            return plan;
        },
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
