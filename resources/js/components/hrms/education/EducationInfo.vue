<template>
    <div class="education-info">
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employeeId">
            <h4>No employee selected!</h4>
        </template>
        <template v-else>
            <div class="card profile-box flex-fill">
                <div class="card-body table-responsive">
                    <h3 class="card-title">
                        Education Background
                        <button v-if="$store.getters.HAS_ANY_ACCESS(['employees.manage_education_info'])" class="edit-icon" @click="editEducation()" title="Add Education">
                            <i class="fa fa-plus"></i>
                        </button>
                    </h3>
                    <EducationInfoList
                        :employee-id="employeeId"
                        :educations="educations"
                    />
                    <EducationInfoForm :employee-id="employeeId"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import EducationInfoList from "./EducationInfoList";
    import EducationInfoForm from "./EducationInfoForm";

    export default {
        components: {EducationInfoForm, EducationInfoList},
        props: {
            employeeId: {type: Number, required: true},
        },
        created() {
            this.$parent.$on('LOAD_EDUCATION_INFO', this.getEducationInfo);
            EventBus.$on([
                'EDUCATION_INFO_SAVED',
                'EDUCATION_INFO_DELETED',
            ], this.getEducationInfo);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                educations: 'EDUCATIONS',
            }),
        },
        methods: {
            async getEducationInfo() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_EDUCATIONS', {employeeId: this.employeeId});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            editEducation(education = null) {
                EventBus.$emit('EDIT_EDUCATION', education);
            }
        },
    }
</script>

<style scoped>

</style>
