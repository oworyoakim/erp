import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        directorates: [],
    },
    getters: {
        GET_DIRECTORATES: (state) => {
            return state.directorates;
        },
    },
    mutations: {
        SET_DIRECTORATES: (state, payload) => {
            state.directorates = payload || [];
        },
    },
    actions: {
        GET_DIRECTORATES: async ({commit}) => {
            try {
                let response = await axios.get(routes.DIRECTORATES_JSON);
                commit('SET_DIRECTORATES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
        SAVE_DIRECTORATE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.DIRECTORATES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.DIRECTORATES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_DIRECTORATE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.DIRECTORATES + '?directorate_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
    },
};
