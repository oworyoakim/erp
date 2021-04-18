import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        leaveApplications: [],
    },
    getters: {
        LEAVE_APPLICATIONS: (state) => {
            return state.leaveApplications;
        },
    },
    mutations: {
        SET_LEAVE_APPLICATIONS: (state, payload) => {
            state.leaveApplications = payload || [];
        },
    },
    actions: {
        GET_LEAVE_APPLICATIONS: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.LEAVE_APPLICATIONS + '/all-json' + queryParams);
                commit('SET_LEAVE_APPLICATIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        SAVE_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.LEAVE_APPLICATIONS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.LEAVE_APPLICATIONS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DELETE_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.LEAVE_APPLICATIONS + '?leaveApplicationId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        APPROVE_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/approve', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DECLINE_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/decline', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        REJECT_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/reject', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        GRANT_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/grant', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        VERIFY_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/verify', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        RETURN_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/return', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
}
;
