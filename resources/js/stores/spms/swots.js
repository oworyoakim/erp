import axios from 'axios';
import routes from "../../routes";

export default {
    state: {
        swots: null,
        categories: [],
        types: [
            {text: 'Strengths', value: 'strengths'},
            {text: 'Weaknesses', value: 'weaknesses'},
            {text: 'Opportunities', value: 'opportunities'},
            {text: 'Threats', value: 'threats'},
        ],
    },
    getters: {
        SWOTS: (state) => {
            return state.swots;
        },
        SWOT_TYPES: (state) => {
            return state.types;
        },
        SWOT_CATEGORIES: (state) => {
            return state.categories;
        }
    },
    mutations: {
        SET_SWOTS: (state, payload) => {
            state.swots = payload;
        },
        SET_SWOT_CATEGORIES: (state, payload) => {
            state.categories = payload;
        },
    },
    actions: {
        GET_SWOTS: async ({commit}, payload) => {
            try {
                let response = await axios.get(routes.SWOTS + '?planId=' + payload);
                commit('SET_SWOTS', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_SWOT: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.SWOTS, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.SWOTS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        GET_SWOT_CATEGORIES: async ({commit}) => {
            try {
                let response = await axios.get(routes.SWOT_CATEGORIES);
                commit('SET_SWOT_CATEGORIES', response.data);
                return Promise.resolve("Ok");
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_SWOT_CATEGORY: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.SWOT_CATEGORIES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.SWOT_CATEGORIES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response);
                return Promise.reject(error.response.data);
            }
        },
    },
}
