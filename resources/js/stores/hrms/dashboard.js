import axios from "axios";
import routes from "../../routes";
import {resolveError} from "../../utils/helpers";
export default {
    state: {
        dashboardStatistics: {
            employeeStatistics: [],
            totalEmployees: 0,
            totalLeavesUpcoming: 0,
            totalLeavesOngoing: 0,
            totalLeaveApplications: 0,
        },
    },
    getters: {
        DASHBOARD_STATISTICS: (state) => {
            return state.dashboardStatistics;
        },
    },
    mutations: {
        SET_DASHBOARD_STATISTICS: (state, payload) => {
            state.dashboardStatistics = payload;
        },
    },
    actions: {
        GET_DASHBOARD_STATISTICS: async ({commit}) => {
            try {
                let response = await axios.get(routes.DASHBOARD_STATISTICS_HRMS);
                commit('SET_DASHBOARD_STATISTICS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
};
