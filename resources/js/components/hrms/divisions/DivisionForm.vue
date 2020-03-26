<template>
    <!-- Division Modal -->
    <div ref="divisionModal" id="divisionModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Division Form</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveDivision">
                        <div v-if="!!!scope" class="form-group">
                            <label>Directorate</label>
                            <select v-model="division.directorate_id" @change="getFormSelections" class="form-control"
                                    :disabled="!!division.id || !!directorate_id">
                                <option value="">Select directorate...</option>
                                <option v-for="directorate in formSelectionOptions.directorates" :value="directorate.id"
                                        v-text="directorate.title"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select v-model="division.department_id" class="form-control select2"
                                    :disabled="!!division.id || !!department_id">
                                <option value="">Select department...</option>
                                <option v-for="department in formSelectionOptions.departments" :value="department.id"
                                        v-text="department.title"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Division Name <span class="text-danger">*</span></label>
                            <input v-model="division.title" class="form-control" type="text">
                        </div>
                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea v-model="division.description" class="form-control"></textarea>
                        </div>
                        <div class="submit-section">
                            <button :disabled="isSending || !!!division.title"
                                    class="btn btn-primary submit-btn">Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Division Modal -->
</template>

<script>
    import {EventBus} from "../../../app";
    import Division from "../../../models/hrms/Division";
    import {mapGetters} from "vuex";

    export default {
        props: ['directorate_id', 'department_id', 'scope'],
        created() {
            setTimeout(() => {
                this.division.directorate_id = this.directorate_id || '';
                this.division.department_id = this.department_id || '';
                this.division.scope = this.scope || '';
                setTimeout(this.getFormSelections, 300);
            }, 300);
            EventBus.$on('editDivision', this.editDivision);
        },
        data() {
            return {
                division: new Division(),
                isSending: false,
            };
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'getFormSelections',
            }),
        },
        methods: {
            async getFormSelections(selection = '') {
                try {
                    if (!!!this.division.id) {
                        if (selection === 'directorate') {
                            this.division.department_id = '';
                        }
                    }
                    await this.$store.dispatch('getFormSelections', this.division);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editDivision(division) {
                this.division = JSON.parse(JSON.stringify(division));
                this.division.scope = this.scope;
                this.getFormSelections();
                console.log(division);
                $(this.$refs.divisionModal).modal('show');
            },
            async saveDivision() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DIVISION', this.division);
                    toastr.success(response);
                    this.closePreview();
                    this.getFormSelections();
                    this.isSending = false;
                    EventBus.$emit('divisionSaved');
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.division = new Division({
                    directorate_id: this.directorate_id,
                    department_id: this.department_id,
                    scope: this.scope,
                });
                $(this.$refs.divisionModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
