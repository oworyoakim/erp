import axios from 'axios';
import routes from "../../routes";
import {prepareQueryParams} from "../../utils/helpers";

export default {
    state: {
        workPlans: [],
        activeWorkPlan: null,
        interventions: [],
    },
    getters: {
        WORK_PLANS: (state) => {
            return state.workPlans;
        },
        ACTIVE_WORK_PLAN: (state) => {
            return state.activeWorkPlan;
        },
        INTERVENTIONS: (state) => {
            return state.interventions;
        }
    },
    mutations: {
        SET_WORK_PLANS: (state, payload) => {
            state.workPlans = payload;
            if (state.workPlans.length > 0 && !!state.activeWorkPlan) {
                let activeWorkPlan = state.workPlans.find((workPlan) => workPlan.id === state.activeWorkPlan.id);
                state.activeWorkPlan = activeWorkPlan || null;
            }
        },
        SET_ACTIVE_WORK_PLAN: (state, payload) => {
            state.activeWorkPlan = payload;
        },
        SET_INTERVENTIONS: (state, payload) => {
            state.interventions = payload;
        },
    },
    actions: {
        GET_WORK_PLANS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.WORK_PLANS + "?planId=" + payload);
                commit('SET_WORK_PLANS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_WORK_PLAN: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.WORK_PLANS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.WORK_PLANS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        GET_INTERVENTIONS: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.INTERVENTIONS + queryParams);
                commit('SET_INTERVENTIONS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        SET_ACTIVE_WORK_PLAN: async ({commit}, payload) => {
            commit("SET_ACTIVE_WORK_PLAN", payload);
        },
        SET_WORK_PLANS({commit},payload){
            commit('SET_WORK_PLANS', payload);
        }
    },
}
