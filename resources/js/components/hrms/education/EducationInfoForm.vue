<template>
    <!-- Education Modal -->
    <div
        ref="educationModal"
        id="educationModal"
        class="modal custom-modal fade"
        role="dialog"
        tabindex="-1"
        data-backdrop="static"
        data-keyboard="false"
    >
        <div
            class="modal-dialog modal-lg modal-dialog-centered"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Education Form</h5>
                    <button
                        @click="closePreview()"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveEducation">
                        <div class="form-group row">
                            <label class="col-sm-4"
                                >Institution
                                <span class="text-danger">*</span></label
                            >
                            <div class="col-sm-8">
                                <input
                                    v-model="education.institution"
                                    class="form-control"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4"
                                >Qualification
                                <span class="text-danger">*</span></label
                            >
                            <div class="col-sm-8">
                                <input
                                    v-model="education.qualification"
                                    class="form-control"
                                    type="text"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4"
                                >From <span class="text-danger">*</span></label
                            >
                            <div class="col-sm-4">
                                <select
                                    v-model="education.start_month"
                                    class="form-control"
                                >
                                    <option value="">Select...</option>
                                    <option
                                        v-for="month in months"
                                        :value="month"
                                        >{{ month }}</option
                                    >
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input
                                    v-model="education.start_year"
                                    class="form-control"
                                    min="1900"
                                    :max="$moment().format('YYYY')"
                                    step="1"
                                    type="number"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">To</label>
                            <div class="col-sm-4">
                                <select
                                    v-model="education.end_month"
                                    class="form-control"
                                >
                                    <option value="">Select...</option>
                                    <option
                                        v-for="month in months"
                                        :value="month"
                                        :key="month"
                                        >{{ month }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input
                                    v-model="education.end_year"
                                    class="form-control"
                                    min="1900"
                                    :max="$moment().format('YYYY')"
                                    step="1"
                                    type="number"
                                />
                            </div>
                        </div>
                        <div class="submit-section text-right">
                            <button
                                :disabled="
                                    isSending ||
                                        !!!education.institution ||
                                        !!!education.qualification ||
                                        !!!education.start_month ||
                                        !!!education.start_year
                                "
                                class="btn btn-primary submit-btn"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Education Modal -->
</template>

<script>
import Education from "../../../models/hrms/Education";
import { mapGetters } from "vuex";
import { EventBus } from "../../../app";
import {deepClone} from "../../../utils/helpers";

export default {
    props: {
        employee_id: Number
    },
    created() {
        EventBus.$on("editEducation", this.editEducation);
    },
    data() {
        return {
            education: new Education(),
            isSending: false
        };
    },
    computed: {
        ...mapGetters({
            months: "getMonths"
        })
    },
    methods: {
        editEducation(education) {
            this.education = deepClone(education);
            $(this.$refs.educationModal).modal("show");
        },
        async saveEducation() {
            try {
                this.isSending = true;
                if (!!!this.education.employee_id) {
                    this.education.employee_id = this.employee_id;
                }
                let response = await this.$store.dispatch(
                    "SAVE_EDUCATION",
                    this.education
                );
                toastr.success(response);
                this.isSending = false;
                EventBus.$emit("educationInfoSaved");
                this.closePreview();
            } catch (error) {
                console.log(error);
                //toastr.error(error);
                swal({ title: error, icon: "error" });
                this.isSending = false;
            }
        },
        closePreview() {
            this.education = new Education();
            $(this.$refs.educationModal).modal("hide");
            $(".modal-backdrop").remove();
        }
    }
};
</script>

<style scoped></style>
