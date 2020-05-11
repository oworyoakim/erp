<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveSection">
            <div v-if="!!!scope" class="form-group row">
                <label class="col-md-4">Directorate <span v-if="!!!scope" class="text-danger">*</span></label>
                <div class="col-md-8">
                    <select v-model="section.directorateId"
                            class="form-control"
                            :disabled="!!section.id || !!directorateId"
                            :required="!(!!scope || !!directorateId)"
                    >
                        <option value="">Select directorate...</option>
                        <option v-for="directorate in directorates" :value="directorate.id">
                            {{directorate.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Department</label>
                <div class="col-md-8">
                    <select v-model="section.departmentId"
                            class="form-control" :disabled="!!section.id  || !!departmentId">
                        <option value="">Select...</option>
                        <option v-for="department in departments" :value="department.id">
                            {{department.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Division</label>
                <div class="col-md-8">
                    <select v-model="section.divisionId" class="form-control"
                            :disabled="!!section.id || !!divisionId">
                        <option value="">Select division...</option>
                        <option v-for="division in divisions" :value="division.id">
                            {{division.title}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Title <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="section.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Description</label>
                <div class="col-md-8">
                    <textarea v-model="section.description" class="form-control" rows="3"></textarea>
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
    import Section from "../../../models/hrms/Section";
    import {EventBus} from '../../../app';
    import {mapGetters} from "vuex";
    import {deepClone} from "../../../utils/helpers";

    export default {
        props: ['directorateId', 'departmentId', 'divisionId', 'scope'],
        created() {
            EventBus.$on('EDIT_SECTION', this.editSection);
        },
        data() {
            return {
                section: new Section(),
                isSending: false,
                isEditing: false,
            };
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            directorates() {
                return this.formSelectionOptions.directorates;
            },
            departments() {
                return this.formSelectionOptions.departments.filter((department, index) => {
                    return (!!!department.directorateId && !!!this.section.directorateId) ||
                        department.directorateId === this.section.directorateId;
                });
            },
            divisions() {
                return this.formSelectionOptions.divisions.filter((division, index) => {
                    return ((!!!division.departmentId && !!!this.section.departmentId) ||
                        division.departmentId === this.section.departmentId) &&
                        ((!!!division.directorateId && !!!this.section.directorateId) ||
                            division.directorateId === this.section.directorateId);
                });
            },
            title() {
                return (!!this.section.id) ? "Edit Section" : "New Section";
            },
            formInvalid() {
                // if in ES, directorate should be null and not required
                return this.isSending || !!!this.section.title || (!!!this.scope && !!!this.section.directorateId);
            },
        },
        watch: {
            "section.directorateId"(newVal,oldVal){
                if(!!!this.directorateId && !!!this.section.id){
                    this.section.departmentId = null;
                    this.section.divisionId = null;
                }
            },
            "section.departmentId"(newVal,oldVal){
                if((!!!this.directorateId || !!!this.directorateId)  && !!!this.section.id){
                    this.section.divisionId = null;
                }
            }
        },
        methods: {
            editSection(section = null) {
                if (!!section) {
                    this.section = deepClone(section);
                    this.section.divisionId = section.divisionId;
                } else {
                    this.section = new Section({
                        directorateId: this.directorateId,
                        departmentId: this.departmentId,
                        divisionId: this.divisionId,
                    });
                }
                this.section.scope = this.scope;
                this.isEditing = true;
            },
            async saveSection() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_SECTION', this.section);
                    toastr.success(response);
                    this.resetForm();
                    this.isSending = false;
                    EventBus.$emit('SECTION_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.section = new Section({
                    directorateId: this.directorateId,
                    departmentId: this.departmentId,
                    divisionId: this.divisionId,
                });
                this.section.scope = this.scope;
                this.isEditing = false;
                $(".modal-backdrop").remove();
            },
        }
    }
</script>
