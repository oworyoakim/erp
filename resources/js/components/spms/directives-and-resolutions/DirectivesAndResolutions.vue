<template>
    <div class="mt-1">
        <app-spinner v-if="isLoading || !plan || !workPlan"/>
        <template v-else>
            <div class="row">
                <div class="col-md-12">
                    <button @click="editDirectiveAndResolution()" class="btn btn-primary add-btn btn-sm pull-right mb-2">
                        <i class="fa fa-plus"></i>
                        Add Directive And Resolution
                    </button>
                </div>
                <div class="col-md-12 table-responsive">
                    <table class="table table-condensed custom-table">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Source</th>
                            <th>ReceivedAt</th>
                            <th>Deadline</th>
                            <th>Organisation</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="directiveAndResolution in directivesAndResolutions">
                            <td>{{directiveAndResolution.title}}</td>
                            <td>{{directiveAndResolution.type}}</td>
                            <td>{{directiveAndResolution.sourceType}}</td>
                            <td>{{directiveAndResolution.dateReceived}}</td>
                            <td>{{directiveAndResolution.deadline}}</td>
                            <td>{{directiveAndResolution.sourceOrganization}}</td>
                            <td>
                                <button class="btn btn-info btn-sm" title="Edit" @click="editDirectiveAndResolution(directiveAndResolution)"><i
                                    class="fa fa-pencil m-r-5"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <DirectiveAndResolutionForm />
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import DirectiveAndResolutionForm from "./DirectiveAndResolutionForm";

    export default {
        components: {DirectiveAndResolutionForm},
        created() {
            this.$store.dispatch("GET_DIRECTORATES_OPTIONS");
            this.$parent.$on("LOAD_DIRECTIVES_AND_RESOLUTIONS", this.getDirectivesAndResolutions);
            EventBus.$on([
                "DIRECTIVE_AND_RESOLUTION_SAVED"
            ], this.getDirectivesAndResolutions);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                plan: "ACTIVE_PLAN",
                workPlan: "ACTIVE_WORK_PLAN",
                directivesAndResolutions: "DIRECTIVES_AND_RESOLUTIONS",
            }),
        },
        methods: {
            editDirectiveAndResolution(directiveAndResolution = null) {
                EventBus.$emit("EDIT_DIRECTIVE_AND_RESOLUTION", directiveAndResolution);
            },
            async getDirectivesAndResolutions() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_DIRECTIVES_AND_RESOLUTIONS', {
                        planId: this.plan.id,
                        workPlanId: this.workPlan.id,
                    });
                    this.isLoading = false;
                } catch (error) {
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
        }
    }
</script>

<style scoped>

</style>
