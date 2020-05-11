<template>
    <div class="experience-info">
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employeeId">
            <h4>No employee selected!</h4>
        </template>
        <template v-else>
            <div class="card profile-box flex-fill">
                <div class="card-body table-responsive">
                    <h3 class="card-title">Work Experience <a href="#" class="edit-icon" @click="editExperience()">
                        <i class="fa fa-plus"></i></a></h3>
                    <ExperienceInfoList
                        :employee-id="employeeId"
                        :experiences="experiences"
                    />
                    <ExperienceInfoForm :employee-id="employeeId"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import ExperienceInfoList from "./ExperienceInfoList";
    import ExperienceInfoForm from "./ExperienceInfoForm";

    export default {
        components: {ExperienceInfoForm, ExperienceInfoList},
        props: {
            employeeId: {type: Number, required: true},
        },
        created() {
            this.$parent.$on('LOAD_EXPERIENCE_INFO', this.getExperienceInfo);
            EventBus.$on(['EXPERIENCE_INFO_SAVED', 'EXPERIENCE_INFO_DELETED'], this.getExperienceInfo);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                experiences: 'EXPERIENCES',
            }),
        },
        methods: {
            async getExperienceInfo() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_EXPERIENCES', {employeeId: this.employeeId});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            editExperience(experience = null) {
                EventBus.$emit('EDIT_EXPERIENCE', experience);
            }
        },
    }
</script>

<style scoped>

</style>
