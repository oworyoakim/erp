import routes from "../routes";

export default {
    state: {
        users: [],
    },
    getters: {
        USERS: (state) => {
            return state.users;
        },
    },
    mutations: {
        SET_USERS: (state, payload) => {
            state.users = payload;
        },
    },
    actions: {
        GET_USERS: async ({commit}) => {
            try {
                let response = await axios.get(routes.USERS + '/all-json');
                commit("SET_USERS", response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },

        SAVE_USER: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    response = await axios.put(routes.USERS, payload);
                } else {
                    response = await axios.post(routes.USERS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
    },
}
