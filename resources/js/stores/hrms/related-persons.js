import axios from "axios";
import routes from "../../routes";
import {prepareQueryParams, resolveError} from "../../utils/helpers";

export default {
    state: {
        relatedPersons: [],
    },
    getters: {
        RELATED_PERSONS(state) {
            return state.relatedPersons || [];
        },
    },
    mutations: {
        SET_RELATED_PERSONS(state, payload) {
            state.relatedPersons = payload;
        }
    },
    actions: {
        async GET_RELATED_PERSONS({commit}, payload) {
            try {
                if (!payload.employeeId) {
                    return Promise.reject("Employee ID required!");
                }
                let queryParams = prepareQueryParams(payload);
                let response = await axios.get(routes.EMPLOYEES  + '/related-persons' + queryParams);
                commit('SET_RELATED_PERSONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async SAVE_RELATED_PERSON({commit}, payload) {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES + "/related-persons", payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES + "/related-persons", payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
}
