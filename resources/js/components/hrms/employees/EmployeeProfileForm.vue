<template>
    <!-- Profile Modal -->
    <div ref="profileModal" id="profileModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Profile Information</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateProfileInfo">
                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">NIN </label>
                            <div class="col-sm-8">
                                <input v-model="employee.nin" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">Passport </label>
                            <div class="col-sm-8">
                                <input v-model="employee.passport" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">Phone </label>
                            <div class="col-sm-8">
                                <input v-model="employee.mobile" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">Email </label>
                            <div class="col-sm-8">
                                <input v-model="employee.email" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">Gender </label>
                            <div class="col-sm-8">
                                <select v-model="employee.gender" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="gender in formSelectionOptions.genders" :value="gender.slug">{{gender.title}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">Marital Status </label>
                            <div class="col-sm-8">
                                <select v-model="employee.marital_status_id" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="maritalStatus in formSelectionOptions.maritalStatuses" :value="maritalStatus.id">{{maritalStatus.title}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">Religion </label>
                            <div class="col-sm-8">
                                <select v-model="employee.religion_id" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="religion in formSelectionOptions.religions" :value="religion.id">{{religion.title}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button class="btn btn-primary submit-btn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Profile Modal -->
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import Employee from "../../../models/hrms/Employee";

    export default {
        computed:{
            ...mapGetters({
                formSelectionOptions: 'getFormSelections',
            }),
        },
        data(){
            return {
                employee: new Employee(),
                isSending: false,
            }
        },
        created(){
            EventBus.$on('updateProfileInfo',this.editProfileInfo);
        },
        methods:{
            editProfileInfo(employee){
                this.employee = JSON.parse(JSON.stringify(employee));
                $(this.$refs.profileModal).modal('show');
            },
            async updateProfileInfo(){
                try {
                    this.isSending = true;
                    console.log(this.employee);
                    let data = {
                        employee_id: this.employee.id,
                        username: this.employee.username,
                        nin: this.employee.nin,
                        passport: this.employee.passport,
                        mobile: this.employee.mobile,
                        email: this.employee.email,
                        gender: this.employee.gender,
                        marital_status_id: this.employee.marital_status_id,
                        religion_id: this.employee.religion_id,
                    };
                    let response = await this.$store.dispatch('UPDATE_PROFILE',data);
                    this.isSending = false;
                    toastr.success(response);
                    EventBus.$emit('profileInfoSaved');
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.employee = new Employee();
                $(this.$refs.profileModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
