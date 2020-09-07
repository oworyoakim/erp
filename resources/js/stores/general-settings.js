import axios from "axios";
import routes from "../routes";
import {resolveError} from "../utils/helpers";

export default {
    state: {
        generalSettings: {
            companyName: null,
            companyEmail: null,
            companyPhone: null,
            companyWebsite: null,
            companyAddress: null,
            companyLogo: null,
            passwordResetEmailSubject: null,
            passwordResetEmailTemplate: null,
            defaultLeaveApplicationVerifier: null,
            defaultLeaveApplicationGranter: null,
        },
    },
    getters: {
        GENERAL_SETTINGS(state) {
            return state.generalSettings;
        },
    },
    mutations: {
        SET_GENERAL_SETTINGS(state, payload) {
            state.generalSettings = payload;
        },
    },
    actions: {
        async GET_GENERAL_SETTINGS({commit}) {
            try {
                let response = await axios.get(routes.SECTIONS);
                commit('SET_GENERAL_SETTINGS', response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
}
