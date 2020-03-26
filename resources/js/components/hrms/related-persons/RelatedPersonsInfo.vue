<template>
    <span v-if="isLoading || !!!employee_id" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else class="card profile-box flex-fill">
        <div class="card-body">
            <h3 class="card-title">Family Members
                <button class="edit-icon" data-toggle="modal"
                        data-target="#personModal"><i class="fa fa-plus"></i></button>
            </h3>
            <div class="table-responsive">
                <app-related-persons-info-list :related-persons="relatedPersons"></app-related-persons-info-list>
            </div>
            <app-related-persons-info-form :employee_id="employee_id"></app-related-persons-info-form>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        props: {
            employee_id: Number,
        },
        created() {
            this.getRelatedPersonInfo();
            EventBus.$on(['relatedPersonSaved','relatedPersonDeleted'],this.getRelatedPersonInfo);
        },
        computed: {
            ...mapGetters({
                relatedPersons: 'getRelatedPersons',
            }),
        },
        data() {
            return {
                isLoading: false,
            }
        },
        methods: {
            async getRelatedPersonInfo() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('getRelatedPersons', {employee_id: this.employee_id});
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
