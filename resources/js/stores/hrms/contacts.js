import axios from 'axios';
import {resolveError} from "../../utils/helpers";
import routes from "../../routes";

export default {
    state: {},
    getters: {},
    mutations: {},
    actions: {
        async SAVE_CONTACT_INFO({commit}, payload) {
            try {
                let response;
                if (payload.id) {
                    response = await axios.put(routes.CONTACTS, payload);
                } else {
                    response = await axios.post(routes.CONTACTS, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        }
    },
}
