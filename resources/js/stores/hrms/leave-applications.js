import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        leaveApplications: [],
    },
    getters: {
        GET_LEAVE_APPLICATIONS: (state) => {
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
                let params = [];
                if (!!payload.employee_id) {
                    params.push('employee_id=' + payload.employee_id);
                }
                if (!!payload.leave_type_id) {
                    params.push('leave_type_id=' + payload.leave_type_id);
                }
                let queryParams = params.join('&');
                let response = await axios.get(routes.LEAVE_APPLICATIONS_JSON + '?' + queryParams);
                commit('SET_LEAVE_APPLICATIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
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
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.LEAVE_APPLICATIONS + '?leave_application_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        APPROVE_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/approve', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DECLINE_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/decline', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        REJECT_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/reject', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        GRANT_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/grant', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        VERIFY_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/verify', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        RETURN_LEAVE_APPLICATION: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.LEAVE_APPLICATIONS + '/return', payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    }
};
