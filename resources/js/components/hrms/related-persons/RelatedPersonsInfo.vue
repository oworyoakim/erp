<template>
    <div class="related-persons">
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employeeId">
            <h4>No employee selected!</h4>
        </template>
        <template v-else>
            <div class="card profile-box flex-fill">
                <div class="card-body">
                    <h3 class="card-title">Family Members
                        <button class="edit-icon" @click="editRelatedPersonInfo()"><i class="fa fa-plus"></i></button>
                    </h3>
                    <div class="table-responsive">
                        <RelatedPersonsInfoList
                            :employee-id="employeeId"
                            :related-persons="relatedPersons"
                        />
                    </div>
                    <RelatedPersonsInfoForm :employee-id="employeeId"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import RelatedPersonsInfoList from "./RelatedPersonsInfoList";
    import RelatedPersonsInfoForm from "./RelatedPersonsInfoForm";

    export default {
        components: {RelatedPersonsInfoList, RelatedPersonsInfoForm},
        props: {
            employeeId: {type: Number, required: true},
        },
        created() {
            this.$parent.$on('LOAD_RELATED_PERSONS_INFO', this.getRelatedPersons);
            EventBus.$on([
                'RELATED_PERSON_SAVED',
                'RELATED_PERSON_DELETED'
            ], this.getRelatedPersons);
        },
        computed: {
            ...mapGetters({
                relatedPersons: 'RELATED_PERSONS',
            }),
        },
        data() {
            return {
                isLoading: false,
            }
        },
        methods: {
            async getRelatedPersons() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_RELATED_PERSONS', {employeeId: this.employeeId});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            editRelatedPersonInfo(relatedPerson = null) {
                EventBus.$emit("EDIT_RELATED_PERSON", relatedPerson)
            }
        },
    }
</script>

<style scoped>

</style>
