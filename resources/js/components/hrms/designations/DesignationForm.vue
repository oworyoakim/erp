<template>
    <!-- Designation Modal -->
    <div ref="designationModal" id="designationModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Designation Form</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveDesignation">
                        <div class="form-group row">
                            <label class="col-5">Title <span class="text-danger">*</span></label>
                            <div class="col-7">
                                <input v-model="designation.title" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5">Salary Scale <span class="text-danger">*</span></label>
                            <div class="col-7">
                                <select v-model="designation.salary_scale_id" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="sscale in salaryScales" :value="sscale.id">
                                        {{sscale.scale}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5">Supervisor</label>
                            <div class="col-7">
                                <select v-model="designation.supervisor_id" class="form-control">
                                    <option value="">Select Supervisor Designation</option>
                                    <option v-for="desig in designations" :value="desig.id">{{desig.title}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-9">Maximum Holders <span class="text-danger">*</span></label>
                            <div class="col-3">
                                <input v-model="designation.max_holders" class="form-control" type="number" min="1">
                            </div>
                        </div>
                        <div  v-if="!!!scope" class="form-group row">
                            <label class="col-5">Directorate</label>
                            <div class="col-7">
                                <select v-model="designation.directorate_id" @change="getDepartments()"
                                        class="form-control select">
                                    <option value="">Select...</option>
                                    <option v-for="directorate in directorates" :value="directorate.id">
                                        {{directorate.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5">Department</label>
                            <div class="col-7">
                                <select v-model="designation.department_id" @change="getSections()"
                                        class="form-control select">
                                    <option value="">Select...</option>
                                    <option v-for="department in departments" :value="department.id">
                                        {{department.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-5">Section</label>
                            <div class="col-7">
                                <select v-model="designation.section_id"
                                        class="form-control select">
                                    <option value="">Select...</option>
                                    <option v-for="section in sections" :value="section.id">
                                        {{section.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="checkbox-inline col-12">
                                <label>Probational?
                                    <input v-model="designation.probational" type="checkbox"
                                           :checked="designation.probational">
                                </label>
                            </div>
                        </div>
                        <div class="form-group row" v-if="designation.probational">
                            <label class="col-9">Probation Period (in months)</label>
                            <div class="col-3">
                                <input v-model="designation.probation_period" type="number" min="1"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12">Description</label>
                            <div class="col-12">
                                <textarea v-model="designation.description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12">Summary</label>
                            <div class="col-12">
                                <textarea v-model="designation.summary" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="submit-section">
                            <button
                                :disabled="isSending || !!!designation.title || !!!designation.salary_scale_id"
                                class="btn btn-primary submit-btn">Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Designation Modal -->
</template>

<script>
    import Designation from "../../../models/hrms/Designation";
    import {EventBus} from "../../../app";

    export default {
        props: {
            directorates: Array,
            departments: Array,
            sections: Array,
            designations: Array,
            salaryScales: Array,
            scope: String,
        },
        created() {
            EventBus.$on('editDesignation',this.editDesignation);
        },
        data() {
            return {
                designation: new Designation(),
                isSending: false,
            };
        },
        methods: {
            getSections(){
                if (!!!this.designation.id) {
                    this.designation.section_id = '';
                }
                EventBus.$emit('getSection',this.designation.department_id);
            },
            getDepartments(){
                if (!!!this.designation.id) {
                    this.designation.department_id = '';
                }
                EventBus.$emit('GET_DEPARTMENTS',this.designation.directorate_id);
            },
            editDesignation(designation) {
                this.designation = designation;
                this.designation.scope = this.scope;
                this.getSections();
                this.getDepartments();
                console.log(designation);
                $(this.$refs.designationModal).modal('show');
            },
            async saveDesignation() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DESIGNATION', this.designation);
                    EventBus.$emit('designationSaved');
                    toastr.success(response);
                    this.closePreview();
                    this.isSending = false;
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.designation = new Designation({
                    scope: this.scope,
                });
                $(this.$refs.designationModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        }
    }
</script>
