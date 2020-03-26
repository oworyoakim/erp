<template>
    <!-- Department Modal -->
    <div ref="departmentModal" id="departmentModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Department Form</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveDepartment">
                        <div v-if="!!!scope" class="form-group">
                            <label>Directorate</label>
                            <select v-model="department.directorate_id" class="form-control select2"
                                    :disabled="!!department.id || !!directorate_id">
                                <option value="">Select directorate...</option>
                                <option v-for="directorate in formSelectionOptions.directorates" :value="directorate.id"
                                        v-text="directorate.title"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department Name <span class="text-danger">*</span></label>
                            <input v-model="department.title" class="form-control" type="text">
                        </div>
                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea v-model="department.description" class="form-control"></textarea>
                        </div>
                        <div class="submit-section">
                            <button :disabled="isSending || !!!department.title"
                                    class="btn btn-primary submit-btn">Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Department Modal -->
</template>

<script>
    import Department from "../../../models/hrms/Department";
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        props: ['directorate_id', 'scope'],
        created() {
            setTimeout(() => {
                this.department.directorate_id = this.directorate_id || '';
                this.department.scope = this.scope || '';
                setTimeout(this.getFormSelections, 300);
            }, 300);
            EventBus.$on('editDepartment', this.editDepartment);
        },
        data() {
            return {
                department: new Department(),
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
                    if (!!!this.department.id) {
                        if (selection === 'directorate') {
                            this.department.department_id = '';
                        }
                    }
                    await this.$store.dispatch('getFormSelections', this.department);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editDepartment(department) {
                this.department = JSON.parse(JSON.stringify(department));
                this.department.scope = this.scope;
                console.log(department);
                $(this.$refs.departmentModal).modal('show');
            },
            async saveDepartment() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_DEPARTMENT', this.department);
                    toastr.success(response);
                    this.closePreview();
                    this.isSending = false;
                    this.getFormSelections();
                    EventBus.$emit('departmentSaved');
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.department = new Department({
                    directorate_id: this.directorate_id || '',
                    scope: this.scope || ''
                });
                $(this.$refs.departmentModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
