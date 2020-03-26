<template>
    <span v-if="isLoading || !!!employee_id" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else class="card profile-box flex-fill">
        <div class="card-body table-responsive">
            <h3 class="card-title">Work Experience <a href="#" class="edit-icon" data-toggle="modal"
                                                      data-target="#experienceModal"><i class="fa fa-plus"></i></a></h3>
            <app-experience-info-list :experiences="experiences"></app-experience-info-list>
            <app-experience-info-form :employee_id="employee_id"></app-experience-info-form>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            employee_id: Number,
        },
        created() {
            this.getExperienceInfo();
            EventBus.$on(['experienceInfoSaved', 'experienceInfoDeleted'], this.getExperienceInfo);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                experiences: 'GET_EXPERIENCES',
            }),
        },
        methods: {
            async getExperienceInfo() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_EXPERIENCES', {employee_id: this.employee_id});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
        },
    }
</script>

<style scoped>

</style>
