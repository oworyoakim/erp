<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="education-form">
        <form @submit.prevent="saveEducation">
            <div class="form-group row">
                <label class="col-md-4">Institution<span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="education.institution" class="form-control" type="text" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4">Qualification<span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input v-model="education.qualification" class="form-control" type="text" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">From <span class="text-danger">*</span></label>
                <div class="col-sm-4">
                    <select v-model="education.startMonth" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="month in months" :value="month">{{ month }}</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input
                        v-model="education.startYear"
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
                    <select v-model="education.endMonth" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="month in months" :value="month">{{ month }}</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input
                        v-model="education.endYear"
                        class="form-control"
                        min="1900"
                        :max="$moment().format('YYYY')"
                        step="1"
                        type="number"
                    />
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";
    import Education from "../../../models/hrms/Education";

    export default {
        props: {
            employeeId: Number
        },
        created() {
            EventBus.$on("EDIT_EDUCATION", this.editEducation);
        },
        data() {
            return {
                education: new Education(),
                isSending: false,
                isEditing: false
            };
        },
        computed: {
            ...mapGetters({
                months: "MONTHS"
            }),
            title(){
                return (!!this.education.id) ? "Edit Education Background" : "New Education Background";
            },
            formInvalid() {
                return this.isSending ||
                    !(!!this.education.institution &&
                        !!this.education.qualification &&
                        !!this.education.startMonth &&
                        !!this.education.startYear);
            }
        },
        methods: {
            editEducation(education = null) {
                if (!!education) {
                    this.education = deepClone(education);
                } else {
                    this.education = new Education();
                    this.education.employeeId = this.employeeId;
                }
                this.isEditing = true;
            },
            async saveEducation() {
                try {
                    this.isSending = true;
                    if (!!!this.education.employeeId) {
                        this.education.employeeId = this.employeeId;
                    }
                    let response = await this.$store.dispatch(
                        "SAVE_EDUCATION",
                        this.education
                    );
                    this.resetForm();
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit("EDUCATION_INFO_SAVED");
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.education = new Education();
                this.education.employeeId = this.employeeId;
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    };
</script>

<style scoped></style>
