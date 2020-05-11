import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state:{
        departments: [],
        activeDepartment: null,
    },
    getters:{
        DEPARTMENTS: (state) => {
            return state.departments;
        },
        ACTIVE_DEPARTMENT: (state) => {
            return state.activeDepartment;
        },
    },
    mutations:{
        SET_DEPARTMENTS: (state, payload) => {
            state.departments = payload || [];
        },
        SET_ACTIVE_DEPARTMENT: (state, payload) => {
            state.activeDepartment = payload || null;
        },
    },
    actions:{
        GET_DEPARTMENTS: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DEPARTMENTS + '/all-json' +  queryParams);
                commit('SET_DEPARTMENTS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        SAVE_DEPARTMENT: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.DEPARTMENTS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.DEPARTMENTS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        DELETE_DEPARTMENT: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DEPARTMENTS + '?departmentId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        GET_ACTIVE_DEPARTMENT: async ({commit}, payload) => {
            try {
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.DEPARTMENTS + '/details' + queryParams);
                commit('SET_ACTIVE_DEPARTMENT', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    },
};
