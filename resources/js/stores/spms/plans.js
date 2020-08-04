import axios from 'axios';
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        plans: [],
        activePlan: null,
        strategicPlanReport: null,
    },
    getters: {
        PLANS(state) {
            return state.plans;
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
        ACTIVE_PLAN(state, getters) {
            return state.activePlan || getters.DEFAULT_PLAN;
        },
        STRATEGIC_PLAN_REPORT_DATA(state) {
            return state.strategicPlanReport;
        },
    },
    mutations: {
        SET_PLANS(state, payload) {
            state.plans = payload;
        },
        SET_ACTIVE_PLAN(state, payload) {
            state.activePlan = payload;
        },
        SET_STRATEGIC_PLAN_REPORT_DATA(state, payload) {
            state.strategicPlanReport = payload;
        },
    },
    actions: {
        async GET_PLANS ({commit}, payload) {
            try {
                let response = await axios.get(routes.PLANS + '/all-json');
                commit('SET_PLANS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async SAVE_PLAN ({commit}, payload) {
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
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SET_ACTIVE_PLAN ({commit}, payload) {
            commit("SET_ACTIVE_PLAN", payload);
        },
        async LOAD_STRATEGIC_PLAN_REPORT({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.PLANS + '/monitor/strategy/report' + queryParams);
                commit("SET_STRATEGIC_PLAN_REPORT_DATA", response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    },
}
