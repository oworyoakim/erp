<template>
    <app-spinner v-if="isLoading"/>
    <div v-else>
        <div class="row m-b-5">
            <div class="col-sm-8">
                <h2>{{title}}</h2>
            </div>
            <div class="col-sm-4">
                <button @click="editUser()" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Create Non-Employee User</button>
            </div>
        </div>
        <div class="row m-b-5">
            <div class="col table-responsive">
                <table class="table table-striped custom-table mb-0 datatable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="users.length === 0">
                        <tr>
                            <th colspan="5">No data</th>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="user in users" :key="user.id">
                            <td>
                                <app-users-widget
                                    :key="user.username"
                                    :name="user.fullName"
                                    :username="user.username"
                                    :avatar="user.avatar"
                                />
                            </td>
                            <td>{{user.email}}</td>
                            <td>
                                <span v-if="user.role">{{user.role.name}}</span>
                            </td>
                            <td>
                                <input @click="toggleUserStatus(user)" type="checkbox" class="custom-checkbox" :checked="!!user.active">
                            </td>
                            <td>
                               <button @click="editUser(user)" class="btn btn-sm btn-info">Edit</button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <app-user-form />
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            title: String,
        },
        created() {
            this.$store.dispatch("GET_FORM_SELECTIONS_OPTIONS");
            this.getUsers();
            EventBus.$on("USER_SAVED",this.getUsers);
        },
        computed: {
            ...mapGetters({
                users: "USERS",
                roles: "ROLES",
            })
        },
        data() {
            return {
                isLoading: false,
            }
        },
        methods: {
            async getUsers() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("GET_USERS");
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },

            async toggleUserStatus(user) {
                try {
                    let action = 'DEACTIVATE';
                    if(!user.active){
                        action = 'ACTIVATE';
                    }
                    let msg = !!user.active ? "Deactivate" : "Activate";
                    let confirmed = await swal({
                        title: 'User ' + !!user.active ? "Deactivation" : "Activation",
                        icon: 'warning',
                        text: "You will "+ msg + " this user. Are you sure?",
                        buttons: [
                            'No',
                            'Yes'
                        ],
                        closeOnClickOutside: false
                    });
                    if(!!confirmed){
                        let response = await this.$store.dispatch(`${action}_USER`,user);
                        await swal({content: response, icon: 'success'});
                        EventBus.$emit("USER_SAVED");
                    }
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                }
            },
            editUser(user = null) {
                EventBus.$emit('EDIT_USER', user);
            }
        }
    }
</script>

<style scoped>

</style>
