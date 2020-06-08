import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        leaves: [],
        leaveTypes: [],
    },
    getters: {
        LEAVES: (state) => {
            return state.leaves;
        },
        LEAVE_TYPES: (state) => {
            return state.leaveTypes;
        },
    },
    mutations: {
        SET_LEAVES: (state, payload) => {
            state.leaves = payload || [];
        },
        SET_LEAVE_TYPES: (state, payload) => {
            state.leaveTypes = payload || [];
        },
    },
    actions: {
        GET_LEAVES: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.LEAVES_JSON +  queryParams);
                commit('SET_LEAVES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        RECALL_LEAVE: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVES + '/recall', {leaveId:  payload});
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        DELETE_LEAVE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.LEAVES + '?leaveId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        GET_LEAVE_TYPES: async ({commit}) => {
            try {
                let response = await axios.get(routes.LEAVE_TYPES);
                commit('SET_LEAVE_TYPES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        SAVE_LEAVE_TYPE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.LEAVE_TYPES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.LEAVE_TYPES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        DELETE_LEAVE_TYPE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.LEAVE_TYPES + '?leaveTypeId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        ACTIVATE_LEAVE_TYPE: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_TYPES + '/activate', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        DEACTIVATE_LEAVE_TYPE: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_TYPES + '/deactivate', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        GET_LEAVE_POLICIES: async ({commit}, payload) => {
            try {
                if (!!!payload.leaveTypeId) {
                    return Promise.reject('Leave type required!');
                }
                let response = await axios.get(routes.LEAVE_POLICIES + '?leaveTypeId=' + payload.leaveTypeId);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        SAVE_LEAVE_POLICY: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    response = await axios.put(routes.LEAVE_POLICIES, payload);
                } else {
                    response = await axios.post(routes.LEAVE_POLICIES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        DELETE_LEAVE_POLICY: async ({commit}, payload) => {
            try {
                if (!!!payload.leavePolicyId) {
                    return Promise.reject('Leave policy required!');
                }
                let response = await axios.delete(routes.LEAVE_POLICIES + '?leavePolicyId=' + payload.leavePolicyId);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        ACTIVATE_LEAVE_POLICY: async ({commit}, payload) => {
            try {
                if (!!!payload.leavePolicyId) {
                    return Promise.reject('Leave policy required!');
                }
                let response = await axios.patch(routes.LEAVE_POLICIES + '/activate?leavePolicyId=' + payload.leavePolicyId);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        DEACTIVATE_LEAVE_POLICY: async ({commit}, payload) => {
            try {
                if (!!!payload.leavePolicyId) {
                    return Promise.reject('Leave policy required!');
                }
                let response = await axios.patch(routes.LEAVE_POLICIES + '/deactivate?leavePolicyId=' + payload.leavePolicyId);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async SAVE_LEAVE_APPLICATION_SETTINGS({commit}, payload) {
            try {
                let response = await axios.post(routes.APPROVAL_SETTINGS + '/leave-applications-settings', payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
};
