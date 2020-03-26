<template>
    <span v-if="isLoading || !!!employee_id" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else class="card profile-box flex-fill">
        <div class="card-body table-responsive">
            <h3 class="card-title">
                Education Background
                <button class="edit-icon" data-toggle="modal"
                        data-target="#educationModal" title="Add Education"><i class="fa fa-plus"></i></button>
            </h3>
            <app-education-info-list :educations="educations"></app-education-info-list>
            <app-education-info-form :employee_id="employee_id"></app-education-info-form>
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
            this.getEducationInfo();
            EventBus.$on(['educationInfoSaved', 'educationInfoDeleted'], this.getEducationInfo);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                educations: 'GET_EDUCATIONS',
                months: 'getMonths',
            }),
        },
        methods: {
            async getEducationInfo() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_EDUCATIONS', {employee_id: this.employee_id});
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
