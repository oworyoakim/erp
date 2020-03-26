import axios from "axios";
import routes from "../../routes";
import educationsModule from './education';
import experiencesModule from './experiences';

export default {
    modules: {
        educations: educationsModule,
        experiences: experiencesModule,
    },
    state: {
        employees: [],
    },
    getters: {
        GET_EMPLOYEES: (state) => {
            return state.employees;
        },
    },
    mutations: {
        SET_EMPLOYEES: (state, payload) => {
            state.employees = payload || [];
        },
    },
    actions: {
        GET_EMPLOYEES: async ({commit}, payload) => {
            try {
                let params = [];
                if (!!payload.name) {
                    params.push('name=' + payload.name);
                }
                if (!!payload.employee_status) {
                    params.push('employee_status=' + payload.employee_status);
                }
                if (!!payload.employment_status) {
                    params.push('employment_status=' + payload.employment_status);
                }
                if (!!payload.employment_term) {
                    params.push('employment_term=' + payload.employment_term);
                }
                if (!!payload.employment_type) {
                    params.push('employment_type=' + payload.employment_type);
                }
                if (!!payload.designation_id) {
                    params.push('designation_id=' + payload.designation_id);
                }
                if (!!payload.department_id) {
                    params.push('department_id=' + payload.department_id);
                }
                if (!!payload.directorate_id) {
                    params.push('directorate_id=' + payload.directorate_id);
                }
                if (!!payload.division_id) {
                    params.push('division_id=' + payload.division_id);
                }
                if (!!payload.section_id) {
                    params.push('section_id=' + payload.section_id);
                }
                if (!!payload.scope) {
                    params.push('scope=' + payload.scope);
                }
                let queryParam = params.join('&');
                let response = await axios.get(routes.EMPLOYEES_JSON + '?' + queryParam);
                commit('SET_EMPLOYEES', response.data);
                return Promise.resolve('Ok');
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        SAVE_EMPLOYEE: async ({commit}, payload) => {
            try {
                let response;
                if (!!payload.id) {
                    // update
                    response = await axios.put(routes.EMPLOYEES, payload);
                } else {
                    // insert new
                    response = await axios.post(routes.EMPLOYEES, payload);
                }
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        DELETE_EMPLOYEE: async ({commit}, payload) => {
            try {
                let response = await axios.delete(routes.EMPLOYEES + '?employee_id=' + payload);
                console.log(response.data);
                return Promise.resolve(response.data);
            } catch (error) {
                console.log(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        UPDATE_PROFILE: async ({commit}, payload) => {
            try {
                let response = await axios.patch(routes.EMPLOYEES_PROFILE + '/' + payload.username, payload);
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                return Promise.reject(error.response.data);
            }
        },
        UPLOAD_PROFILE_PICTURE: async ({commit}, payload) => {
            try {
                let response = await axios.post(routes.EMPLOYEES + '/upload-profile-picture', payload,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                return Promise.resolve(response.data);
            } catch (error) {
                console.error(error.response.data);
                axios.defaults.headers.common['Content-Type'] = 'application/json';
                return Promise.reject(error.response.data);
            }
        },
    }
};
