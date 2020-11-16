import axios from 'axios';
import routes from "../../routes";
import {deepClone, prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        activities: [],
        activeActivity: null,
        teamLeader: null,
        activityReportData: null,
        activityPerformance: [],
        stages: [],
        tasks: [],
    },
    getters: {
        ACTIVITIES(state) {
            return state.activities;
        },
        ACTIVITY_PERFORMANCE(state) {
            return state.activityPerformance;
        },
        ACTIVE_ACTIVITY(state) {
            return state.activeActivity;
        },
        STAGES(state) {
            return state.stages;
        },
        TASKS(state) {
            return state.tasks;
        },
        TEAM_LEADER_DETAILS(state) {
            return state.teamLeader;
        },
        ACTIVITY_REPORT_DATA(state) {
            return state.activityReportData;
        },
    },
    mutations: {
        SET_ACTIVITIES(state, payload) {
            state.activities = payload;
        },
        SET_ACTIVE_ACTIVITY(state, payload) {
            state.activeActivity = payload;
            if(payload && payload.teamLeaderId){
                this.dispatch('GET_TEAM_LEADER_DETAILS', {employeeId: payload.teamLeaderId});
            }
        },
        SET_ACTIVITY_PERFORMANCE(state, payload) {
            state.activityPerformance = payload;
        },
        SET_STAGES(state, payload) {
            state.stages = payload;
        },
        SET_TASKS(state, payload) {
            state.tasks = payload;
        },
        SET_TEAM_LEADER_DETAILS(state, payload) {
            state.teamLeader = payload;
        },
        SET_ACTIVITY_REPORT_DATA(state, payload) {
            state.activityReportData = payload;
        },
    },
    actions: {
        async GET_ACTIVITIES({commit}, payload = {}) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.ACTIVITIES + queryParams);
                commit('SET_ACTIVITIES', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_ACTIVITY({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.ACTIVITIES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.ACTIVITIES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async GET_STAGES({commit}, payload = {}) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.STAGES + queryParams);
                commit('SET_STAGES', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_STAGE({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.STAGES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.STAGES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async GET_TASKS({commit}, payload = {}) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.TASKS + queryParams);
                commit('SET_TASKS', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_TASK({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.TASKS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.TASKS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async GET_ACTIVITY_PERFORMANCE({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.ACTIVITIES + '/performance' + queryParams);
                let data = response.data;
                commit("SET_ACTIVITY_PERFORMANCE", data);
                return Promise.resolve(deepClone(data));
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },

        async SAVE_ACTIVITY_PERFORMANCE({commit}, payload) {
            try {
                let response = await axios.post(routes.ACTIVITIES + '/performance', payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async GET_TEAM_LEADER_DETAILS({commit}, payload = {}) {
            try {
                if (!payload.employeeId) {
                    throw new Error("Team leader ID required!");
                }
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get('/hrms/employees/details' + queryParams);
                commit("SET_TEAM_LEADER_DETAILS", response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async LOAD_ACTIVITY_REPORT_DATA({commit}, payload) {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.PLANS + '/monitor/activity/report' + queryParams);
                commit("SET_ACTIVITY_REPORT_DATA", response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    },
}
