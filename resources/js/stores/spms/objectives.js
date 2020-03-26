import axios from 'axios';
import routes from "../../routes";

export default {
    state: {
        objectives: [],
    },
    getters: {
        OBJECTIVES: (state) => {
            return state.objectives;
        }
    },
    mutations: {
        SET_OBJECTIVES: (state, payload) => {
            state.objectives = payload;
        }
    },
    actions: {
        GET_OBJECTIVES: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.OBJECTIVES + '?planId=' + payload);
                commit('SET_OBJECTIVES', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_OBJECTIVE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.OBJECTIVES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.OBJECTIVES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
    },
}
