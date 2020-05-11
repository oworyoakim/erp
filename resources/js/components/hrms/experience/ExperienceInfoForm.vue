<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="experience-form">
        <form @submit.prevent="saveExperience">
            <div class="form-group row">
                <label class="col-sm-4">From <span class="text-danger">*</span></label>
                <div class="col-sm-4">
                    <select v-model="experience.startMonth" class="form-control" required>
                        <option value="">Select...</option>
                        <option v-for="month in months" :value="month">{{month}}</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input v-model="experience.startYear" class="form-control" min="1900"
                           :max="$moment().format('YYYY')" placeholder="Year" step="1"
                           type="number" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="checkbox-inline col-sm-12">
                    <label>Is Current? <input v-model="experience.isCurrent" type="checkbox"></label>
                </div>
            </div>
            <div v-if="!experience.isCurrent" class="form-group row">
                <label class="col-sm-4">To</label>
                <div class="col-sm-4">
                    <select v-model="experience.endMonth" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="month in months" :value="month">{{month}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input v-model="experience.endYear" class="form-control" min="1900"
                           :max="$moment().format('YYYY')" placeholder="Year" step="1"
                           type="number">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Company <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="experience.company" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Position <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="experience.position" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <textarea v-model="experience.description" class="form-control" rows="5" required></textarea>
                </div>
            </div>
            <div class="submit-section text-right">
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
    import Experience from "../../../models/hrms/Experience";

    export default {
        props: {
            employeeId: Number,
        },
        created() {
            EventBus.$on('EDIT_EXPERIENCE', this.editExperience);
        },
        data() {
            return {
                experience: new Experience(),
                isSending: false,
                isEditing: false,
            }
        },
        computed: {
            ...mapGetters({
                months: 'MONTHS',
            }),
            title() {
                return (!!this.experience.id) ? "Edit Work Experience" : "New Work Experience";
            },
            formInvalid() {
                return this.isSending ||
                    !(!!this.experience.company ||
                        !!this.experience.position ||
                        !!this.experience.description ||
                        !!this.experience.startMonth ||
                        !!this.experience.startYear);
            }
        },
        methods: {
            editExperience(experience = null) {
                if (!!experience) {
                    this.experience = deepClone(experience);
                } else {
                    this.experience = new Experience();
                    this.experience.employeeId = this.employeeId;
                }
                this.isEditing = true;
            },
            async saveExperience() {
                try {
                    this.isSending = true;
                    if (!!!this.experience.employeeId) {
                        this.experience.employeeId = this.employeeId;
                    }
                    let response = await this.$store.dispatch('SAVE_EXPERIENCE', this.experience);
                    this.resetForm();
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('EXPERIENCE_INFO_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.experience = new Experience();
                this.experience.employeeId = this.employeeId;
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        },
    }
</script>

<style scoped>

</style>
