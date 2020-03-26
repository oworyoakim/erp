<template>
    <!-- Section Modal -->
    <div ref="sectionModal" id="sectionModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Section Form</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveSection">
                        <div v-if="!!!scope" class="form-group">
                            <label>Directorate</label>
                            <select v-model="section.directorate_id" @change="getFormSelections('directorate')"
                                    class="form-control select2" :disabled="!!section.id || !!directorate_id">
                                <option value="">Select directorate...</option>
                                <option v-for="directorate in formSelectionOptions.directorates" :value="directorate.id"
                                        v-text="directorate.title"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department <span class="text-danger">*</span></label>
                            <select v-model="section.department_id" @change="getFormSelections('department')"
                                    class="form-control" :disabled="!!section.id || !!department_id">
                                <option value="">Select...</option>
                                <option v-for="department in formSelectionOptions.departments" :value="department.id">
                                    {{department.title}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Division</label>
                            <select v-model="section.division_id" class="form-control select2"
                                    :disabled="!!section.id || !!division_id">
                                <option value="">Select division...</option>
                                <option v-for="division in formSelectionOptions.divisions" :value="division.id"
                                        v-text="division.title"></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <input v-model="section.title" class="form-control" type="text">
                        </div>
                        <div class="form-group">
                            <label>Description <span class="text-danger">*</span></label>
                            <textarea v-model="section.description" class="form-control"></textarea>
                        </div>
                        <div class="submit-section">
                            <button :disabled="isSending || !!!section.title"
                                    class="btn btn-primary submit-btn">Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Section Modal -->
</template>

<script>
    import Section from "../../../models/Section";
    import {EventBus} from '../../../app';
    import {mapGetters} from "vuex";

    export default {
        props: ['directorate_id', 'department_id', 'division_id','scope'],
        created() {
            setTimeout(() => {
                this.section.directorate_id = this.directorate_id || '';
                this.section.department_id = this.department_id || '';
                this.section.division_id = this.division_id || '';
                this.section.scope = this.scope || '';
                setTimeout(this.getFormSelections, 300);
            }, 300);
            EventBus.$on('editSection', this.editSection);
        },
        data() {
            return {
                section: new Section(),
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
                    if (!!!this.section.id) {
                        if (selection === 'directorate') {
                            this.section.department_id = '';
                            this.section.division_id = '';
                        }
                        if (selection === 'department') {
                            this.section.division_id = '';
                        }
                    }
                    await this.$store.dispatch('getFormSelections', this.section);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
            editSection(section) {
                this.section = JSON.parse(JSON.stringify(section));
                this.section.scope = this.scope;
                console.log(section);
                $(this.$refs.sectionModal).modal('show');
            },
            async saveSection() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_SECTION', this.section);
                    toastr.success(response);
                    this.closePreview();
                    this.isSending = false;
                    this.getFormSelections();
                    EventBus.$emit('sectionSaved');
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.section = new Section({
                    directorate_id: this.directorate_id,
                    department_id: this.department_id,
                    division_id: this.division_id,
                    scope: this.scope,
                });
                $(this.$refs.sectionModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        }
    }
</script>
