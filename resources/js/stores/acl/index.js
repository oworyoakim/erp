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
    state: {
        user: null,
        loginPageForm: 'login',
        success: null,
        error: null,
        generalSettings: [],
        selectionsOptions: {
            roles: [],
            usernames: [],
        },
        editorConfig: {
            media_live_embeds: true,
            menubar: "insert edit format",
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks fullscreen',
                'insertdatetime media table paste hr code wordcount',
                'toc'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image video code|toc'
        },
    },
    getters: {
        getUser(state) {
            return state.user;
        },
        TINYMCE_API_KEY: (state) => {
            if(!state.user){
                return 'no-api-key';
            }
            return state.user.tinymceApiKey || 'no-api-key';
        },
        EDITOR_CONFIG(state){
            return state.editorConfig;
        },
        getGeneralSettings(state) {
            return state.generalSettings;
        },
        getRole(state) {
            return state.user ? state.user.role : null;
        },
        FORM_SELECTIONS_OPTIONS(state) {
            return state.selectionsOptions;
        },
        HAS_ACCESS(state){
            return (permission = '') => {
                return !!permission && !!state.user && !!state.user.permissions[permission];
            }
        },
        HAS_ANY_ACCESS(state){
            return (permissions = []) => {
                return !!state.user && permissions.some((permission) => !!state.user.permissions[permission]);
            }
        },
    },
    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },
        setGeneralSettings(state, payload) {
            state.generalSettings = payload;
        },
        SET_FORM_SELECTIONS_OPTIONS(state, payload) {
            state.selectionsOptions = payload;
        },
    },
    actions: {
        async getUser({commit}) {
            try {
                let response = await axios.get('/user-data');
                commit('setUser', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async getGeneralSettings({commit}) {
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
        async GET_FORM_SELECTIONS_OPTIONS({commit}) {
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
        async LOGIN({commit}, payload) {
            try {
                let response = await axios.post('/login', payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
        async RESET_PASSWORD({commit}, payload) {
            try {
                let response = await axios.post('/reset-password', payload);
                return Promise.resolve(response.data);
            } catch (error) {
                let message = resolveError(error);
                return Promise.reject(message);
            }
        },
    },
});
