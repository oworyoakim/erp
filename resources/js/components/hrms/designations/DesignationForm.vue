<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveDesignation">
            <div class="form-group row">
                <label class="col-5">Title <span class="text-danger">*</span></label>
                <div class="col-7">
                    <input v-model="designation.title" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-5">Short Name <span class="text-danger">*</span></label>
                <div class="col-7">
                    <input v-model="designation.shortName" class="form-control" type="text">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-5">Salary Scale <span class="text-danger">*</span></label>
                <div class="col-7">
                    <select v-model="designation.salaryScaleId" class="form-control">
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
                    <select v-model="designation.supervisorId" class="form-control">
                        <option value="">Select Supervisor Designation</option>
                        <option v-for="desig in designations" :value="desig.id">{{desig.title}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-9">Maximum Holders <span class="text-danger">*</span></label>
                <div class="col-3">
                    <input v-model="designation.maxHolders" class="form-control" type="number" min="1">
                </div>
            </div>
            <div v-if="!!!scope" class="form-group row">
                <label class="col-5">Directorate</label>
                <div class="col-7">
                    <select v-model="designation.directorateId" class="form-control select">
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
                    <select v-model="designation.departmentId" class="form-control select">
                        <option value="">Select...</option>
                        <option v-for="department in departments" :value="department.id">
                            {{department.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-5">Division</label>
                <div class="col-7">
                    <select v-model="designation.divisionId" class="form-control select">
                        <option value="">Select...</option>
                        <option v-for="division in divisions" :value="division.id">
                            {{division.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-5">Section</label>
                <div class="col-7">
                    <select v-model="designation.sectionId"
                            class="form-control select">
                        <option value="">Select...</option>
                        <option v-for="section in sections" :value="section.id">
                            {{section.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-5">Is Head Of?</label>
                <div class="col-7">
                    <select v-model="designation.heads"
                            class="form-control select">
                        <option value="">Select...</option>
                        <option v-for="structure in structures" :value="structure.value">
                            {{structure.text}}
                        </option>
                    </select>
                    <span v-if="structureInvalid" class="text-danger">If this designation heads a {{designation.heads}}, you must select the {{designation.heads}} where the designation belongs! </span>
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
                    <input v-model="designation.probationPeriod" type="number" min="1"
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
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import {mapGetters} from "vuex";
    import Designation from "../../../models/hrms/Designation";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        props: ["directorateId", "departmentId", "divisionId", "sectionId", "scope"],
        created() {
            EventBus.$on('EDIT_DESIGNATION', this.editDesignation);
        },
        data() {
            return {
                designation: new Designation(),
                isSending: false,
                isEditing: false,
            };
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            structures(){
                return [
                    {text: 'Executive Director Office', value: 'executive-director-office'},
                    {text: 'Directorate', value: 'directorate'},
                    {text: 'Department', value: 'department'},
                    {text: 'Division', value: 'division'},
                    {text: 'Section', value: 'section'},
                ]
            },
            directorates() {
                return this.formSelectionOptions.directorates || [];
            },
            departments() {
                return this.formSelectionOptions.departments.filter((department, index) => {
                    return (!!!department.directorateId && !!!this.designation.directorateId) ||
                        department.directorateId === this.designation.directorateId;
                });
            },
            divisions() {
                return this.formSelectionOptions.divisions.filter((division, index) => {
                    return ((!!!division.departmentId && !!!this.designation.departmentId) ||
                        division.departmentId === this.designation.departmentId) &&
                        ((!!!division.directorateId && !!!this.designation.directorateId) ||
                            division.directorateId === this.designation.directorateId);
                });
            },
            sections() {
                return this.formSelectionOptions.sections.filter((section, index) => {
                    return ((!!!section.divisionId && !!!this.designation.divisionId) ||
                        section.divisionId === this.designation.divisionId) &&
                        ((!!!section.departmentId && !!!this.designation.departmentId) ||
                        section.departmentId === this.designation.departmentId) &&
                        ((!!!section.directorateId && !!!this.designation.directorateId) ||
                            section.directorateId === this.designation.directorateId);
                });
            },
            designations() {
                return this.formSelectionOptions.designations;
            },
            salaryScales() {
                return this.formSelectionOptions.salaryScales;
            },
            title(){
                return (!!this.designation.id) ? "Edit Designation" : "New Designation";
            },
            structureInvalid(){
                return (this.designation.heads === 'directorate' && !!!this.designation.directorateId) ||
                    (this.designation.heads === 'department' && !!!this.designation.departmentId) ||
                    (this.designation.heads === 'division' && !!!this.designation.divisionId) ||
                    (this.designation.heads === 'section' && !!!this.designation.sectionId);
            },
            formInvalid(){
                return this.isSending ||
                    !!!this.designation.title ||
                    !!!this.designation.shortName ||
                    !!!this.designation.salaryScaleId ||
                    this.structureInvalid
            },
        },
        watch: {
            "designation.directorateId"(newVal,oldVal){
                if(!!!this.directorateId && !!!this.designation.id){
                    this.designation.departmentId = null;
                    this.designation.divisionId = null;
                    this.designation.sectionId = null;
                }
            },
            "designation.departmentId"(newVal,oldVal){
                if((!!!this.directorateId || !!!this.directorateId)  && !!!this.designation.id){
                    this.designation.divisionId = null;
                    this.designation.sectionId = null;
                }
            },
            "designation.divisionId"(newVal,oldVal){
                if((!!!this.directorateId || !!!this.directorateId)  && !!!this.designation.id){
                    this.designation.sectionId = null;
                }
            }
        },
        methods: {
            editDesignation(designation = null) {
                if (!!designation) {
                    this.designation = deepClone(designation);
                } else {
                    this.designation = new Designation({
                        directorateId: this.directorateId,
                        departmentId: this.departmentId,
                        divisionId: this.divisionId,
                        sectionId: this.sectionId,
                        scope: this.scope,
                    });
                }
                this.isEditing = true;
            },
            async saveDesignation() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DESIGNATION', this.designation);
                    EventBus.$emit('DESIGNATION_SAVED');
                    toastr.success(response);
                    this.resetForm();
                    this.isSending = false;
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.designation = new Designation({
                    directorateId: this.directorateId,
                    departmentId: this.departmentId,
                    divisionId: this.divisionId,
                    sectionId: this.sectionId,
                    scope: this.scope,
                });
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        }
    }
</script>
