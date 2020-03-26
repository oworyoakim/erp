<template>
    <!-- Experience Modal -->
    <div ref="experienceModal" id="experienceModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Experience Form</h5>
                    <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveExperience">
                        <div class="form-group row">
                            <label class="col-sm-4">From <span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <select v-model="experience.start_month" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="month in months" :value="month">{{month}}</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input v-model="experience.start_year" class="form-control" min="1900"
                                       :max="$moment().format('YYYY')" placeholder="Year" step="1"
                                       type="number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="checkbox-inline col-sm-12">
                                <label>Is Current? <input v-model="experience.is_current" type="checkbox"></label>
                            </div>
                        </div>
                        <div v-if="!experience.is_current" class="form-group row">
                            <label class="col-sm-4">To</label>
                            <div class="col-sm-4">
                                <select v-model="experience.end_month" class="form-control">
                                    <option value="">Select...</option>
                                    <option v-for="month in months" :value="month">{{month}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input v-model="experience.end_year" class="form-control" min="1900"
                                       :max="$moment().format('YYYY')" placeholder="Year" step="1"
                                       type="number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Company <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input v-model="experience.company" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Position <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input v-model="experience.position" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Description <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <textarea v-model="experience.description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="submit-section text-right">
                            <button
                                :disabled="isSending || !!!experience.company || !!!experience.position || !!!experience.start_month || !!!experience.start_year || !!!experience.description"
                                class="btn btn-primary submit-btn">Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Experience Modal -->
</template>

<script>
    import Experience from "../../../models/hrms/Experience";
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            employee_id: Number,
        },
        created() {
            EventBus.$on('editExperience', this.editExperience);
        },
        data() {
            return {
                experience: new Experience(),
                isSending: false,
            }
        },
        computed:{
            ...mapGetters({
                months: 'getMonths',
            }),
        },
        methods: {
            editExperience(experience) {
                this.experience = experience;
                $(this.$refs.experienceModal).modal('show');
            },
            async saveExperience() {
                try {
                    this.isSending = true;
                    if (!!!this.experience.employee_id) {
                        this.experience.employee_id = this.employee_id;
                    }
                    let response = await this.$store.dispatch('SAVE_EXPERIENCE', this.experience);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('experienceInfoSaved');
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error,icon: 'error'});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.experience = new Experience();
                $(this.$refs.experienceModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
