import axios from "axios";
import routes from "../../routes";

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
                let params = [];
                if (!!payload.employee_id) {
                    params.push('employee_id=' + payload.employee_id);
                }
                if (!!payload.leave_type_id) {
                    params.push('leave_type_id=' + payload.leave_type_id);
                }
                let queryParams = params.join('&');
                let response = await axios.get(routes.LEAVES_JSON + '?' + queryParams);
                commit('SET_LEAVES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        RECALL_LEAVE: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVES + '/recall', {leave_id:  payload});
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_LEAVE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.LEAVES + '?leave_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        GET_LEAVE_TYPES: async ({commit}) => {
            try {
                let response = await axios.get(routes.LEAVE_TYPES_JSON);
                commit('SET_LEAVE_TYPES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
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
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_LEAVE_TYPE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.LEAVE_TYPES + '?leave_type_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        ACTIVATE_LEAVE_TYPE: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_TYPES + '/activate', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DEACTIVATE_LEAVE_TYPE: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_TYPES + '/deactivate', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        GET_LEAVE_POLICIES: async ({commit}, payload) => {
            try {
                if (!!!payload.leave_type_id) {
                    return Promise.reject('Leave type required!');
                }
                let response = await axios.get(routes.LEAVE_POLICIES + '?leave_type_id=' + payload.leave_type_id);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
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
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_LEAVE_POLICY: async ({commit}, payload) => {
            try {
                if (!!!payload.leave_policy_id) {
                    return Promise.reject('Leave policy required!');
                }
                let response = await axios.delete(routes.LEAVE_POLICIES + '?leave_policy_id=' + payload.leave_policy_id);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        ACTIVATE_LEAVE_POLICY: async ({commit}, payload) => {
            try {
                if (!!!payload.leave_policy_id) {
                    return Promise.reject('Leave policy required!');
                }
                let response = await axios.patch(routes.LEAVE_POLICIES + '/activate?leave_policy_id=' + payload.leave_policy_id);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DEACTIVATE_LEAVE_POLICY: async ({commit}, payload) => {
            try {
                if (!!!payload.leave_policy_id) {
                    return Promise.reject('Leave policy required!');
                }
                let response = await axios.patch(routes.LEAVE_POLICIES + '/deactivate?leave_policy_id=' + payload.leave_policy_id);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
