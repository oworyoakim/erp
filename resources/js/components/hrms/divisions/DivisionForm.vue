<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="division-form">
        <form @submit.prevent="saveDivision">
            <div v-if="!!!scope" class="form-group row">
                <label class="col-md-4">Directorate <span v-if="!!!scope" class="text-danger">*</span></label>
                <div class="col-md-8">
                    <select v-model="division.directorateId"
                            class="form-control"
                            :required="!(!!scope  || !!directorateId)"
                            :disabled="!!division.id || !!directorateId">
                        <option value="">Select directorate...</option>
                        <option v-for="directorate in directorates" :value="directorate.id"
                                v-text="directorate.title"></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Department</label>
                <div class="col-md-8">
                    <select v-model="division.departmentId" class="form-control select2"
                            :disabled="!!division.id || !!departmentId">
                        <option value="">Select department...</option>
                        <option v-for="department in departments" :value="department.id"
                                v-text="department.title"></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Division Name <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="division.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Description</label>
                <div class="col-md-8">
                    <textarea v-model="division.description" class="form-control"></textarea>
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
    import {EventBus} from "../../../app";
    import Division from "../../../models/hrms/Division";
    import {deepClone} from "../../../utils/helpers";

    export default {
        props: ['directorateId', 'departmentId', 'scope'],
        created() {
            EventBus.$on('EDIT_DIVISION', this.editDivision);
        },
        data() {
            return {
                division: new Division(),
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
                    return (!!!department.directorateId && !!!this.division.directorateId) ||
                        department.directorateId === this.division.directorateId;
                });
            },
            title() {
                if (!!this.division.id) {
                    return "Edit Division";
                }
                return "New Division";
            },
            formInvalid() {
                return this.isSending || !!!this.division.title || (!!!this.scope && !!!this.division.directorateId);
            }
        },
        watch:{
            "section.directorateId"(newVal,oldVal){
                if(!!!this.directorateId && !!!this.division.id){
                    this.division.departmentId = null;
                }
            },
        },
        methods: {
            editDivision(division = null) {
                if (!!division) {
                    this.division = deepClone(division);
                    this.division.scope = this.scope;
                } else {
                    this.division = new Division({
                        directorateId: this.directorateId,
                        departmentId: this.departmentId,
                        scope: this.scope,
                    });
                }
                this.isEditing = true;
            },
            async saveDivision() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DIVISION', this.division);
                    this.resetForm();
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('DIVISION_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.division = new Division({
                    directorateId: this.directorateId,
                    departmentId: this.departmentId,
                    scope: this.scope,
                });
                this.isEditing = false;
                $(".modal-backdrop").remove();
            },
        },
    }
</script>

<style scoped>

</style>
