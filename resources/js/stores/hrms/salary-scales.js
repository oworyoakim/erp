import axios from "axios";
import routes from "../../routes";

export default {
    state: {
        salaryScales: [],
    },
    getters: {
        SALARY_SCALES: (state) => {
            return state.salaryScales;
        },
    },
    mutations: {
        SET_SALARY_SCALES: (state, payload) => {
            state.salaryScales = payload || [];
        },
    },
    actions: {
        GET_SALARY_SCALES: async ({commit}) => {
            try {
                let response = await axios.get(routes.SALARY_SCALES + '/all-json');
                commit('SET_SALARY_SCALES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
        SAVE_SALARY_SCALE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.SALARY_SCALES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.SALARY_SCALES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
        DELETE_SALARY_SCALE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.SALARY_SCALES + '?salaryScaleId=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);

                return Promise.reject(error.response.data);
            }
        },
    }
}
