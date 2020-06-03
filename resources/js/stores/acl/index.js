import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";
import routes from "../../routes";
import rolesModule from "./roles";
import usersModule from "./users";
import {resolveError} from "../../utils/helpers";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        roles: rolesModule,
        users: usersModule,
    },
    state:{
        user: null,
        generalSettings: [],
        selectionsOptions: {
            roles: [],
            usernames: [],
        },
    },
    getters:{
        getUser(state) {
            return state.user;
        },
        getGeneralSettings(state) {
            return state.generalSettings;
        },
        getRole(state)  {
            return state.user ? state.user.role : null;
        },
        FORM_SELECTIONS_OPTIONS(state){
            return state.selectionsOptions;
        }
    },
    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },
        setGeneralSettings(state, payload){
            state.generalSettings = payload;
        },
        SET_FORM_SELECTIONS_OPTIONS(state, payload){
            state.selectionsOptions = payload;
        },
    },
    actions: {
        async getUser ({commit}){
            try {
                let response = await axios.get('/user-data');
                commit('setUser', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.log(error.response);
                if (error.response && error.response.status === 401) {
                    //toastr.error('Session Expired!');
                    toastr.error(error.response.data);
                    location.reload();
                }
                return Promise.reject(error.response.data);
            }
        },
        async getGeneralSettings ({commit}) {
            try {
                let response = await axios.get(routes.GENERAL_SETTINGS_JSON);
                commit('setGeneralSettings', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
        async GET_FORM_SELECTIONS_OPTIONS ({commit}) {
            try {
                let response = await axios.get(routes.ACL_FROM_SELECTIONS_OPTIONS);
                commit('SET_FORM_SELECTIONS_OPTIONS', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                console.error(message);
                return Promise.reject(message);
            }
        },
    }
});
