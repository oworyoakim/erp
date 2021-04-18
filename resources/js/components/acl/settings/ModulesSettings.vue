<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <div class="row m-b-5">
            <div class="col-sm-8">
                <h2>{{ title }}</h2>
            </div>
            <div class="col-sm-4">

            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
                <div class="roles-menu">
                    <ul>
                        <li v-for="user in users"
                            v-bind:class="{active: !!activeUser && user.id === activeUser.id}"
                            :key="user.id">
                            <a href="javascript:void(0);" @click="activeUser = user">{{ user.fullName }} (<span class="text-muted">{{user.username}}</span>)</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9 table-responsive">
                <template v-if="!!activeUser">
                    <div class="m-b-5">
                        <h3>Modules Permissions</h3>
                        <form @submit.prevent="updateModulesAccess()">
                            <table class="table table-striped custom-table">
                                <tr v-for="module in modules" :key="module.id">
                                    <td>
                                        <input type="checkbox"
                                               :value="module.id"
                                               v-model="activeUser.modules"
                                        />
                                    </td>
                                    <td class="text-bold">{{ module.name }}</td>
                                    <td>{{ module.description }}</td>
                                </tr>
                                <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <button :disabled="isSending || !moduleSettingsChanged"
                                                class="btn add-btn btn-sm">Update
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </form>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import {EventBus} from "../../../app";
import {mapGetters} from "vuex";
import {isEqual} from 'lodash';

export default {
    props: {
        title: String,
    },
    mounted() {
        this.getModulesAccessSettings();
        EventBus.$on(['MODULE_SAVED', 'MODULE_ACCESS_UPDATED'], this.getModulesAccessSettings);
    },
    data() {
        return {
            activeUser: null,
            isLoading: false,
            isSending: false,
            moduleSettings: null,
        };
    },
    computed: {
        ...mapGetters({
            modulesAccessSettings: "MODULES_ACCESS_SETTINGS",
        }),
        users() {
            if (!this.moduleSettings) {
                return [];
            }
            return this.moduleSettings.users;
        },
        modules() {
            if (!this.moduleSettings) {
                return [];
            }
            return this.moduleSettings.modules;
        },
        moduleSettingsChanged() {
            if (!this.activeUser || !this.modulesAccessSettings) {
                return false;
            }
            let activeUser = this.modulesAccessSettings.users.find((user) => user.id === this.activeUser.id);
            if (!activeUser) {
                return false;
            }
            return !isEqual(activeUser.modules,this.activeUser.modules);
        },
    },
    methods: {
        async getModulesAccessSettings() {
            try {
                this.isLoading = true;
                this.moduleSettings = await this.$store.dispatch("GET_MODULES_ACCESS_SETTINGS");
                this.isLoading = false;
            } catch (error) {
                console.log(error);
                toastr.error(error);
                this.isLoading = false;
            }
        },

        editModule(module = null) {
            EventBus.$emit('EDIT_MODULE', module);
        },

        async updateModulesAccess() {
            try {
                // update
                this.isSending = true;
                let response = await this.$store.dispatch("UPDATE_MODULES_ACCESS_SETTINGS", this.activeUser);
                this.isSending = false;
                toastr.success(response);
                EventBus.$emit('MODULE_ACCESS_UPDATED');
            } catch (error) {
                this.isSending = false;
                console.log(error);
                toastr.error(error);
            }
        },
    }
};
</script>
