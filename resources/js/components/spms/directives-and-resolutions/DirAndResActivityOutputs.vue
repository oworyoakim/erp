<template>
    <div class="mt-1">
        <div class="row">
            <div class="col-md-4">
                <label>Directive and resolution</label>
                <select class="custom-select" v-model="directiveAndResolutionId"
                        v-bind:class="{'is-invalid': !!!directiveAndResolutionId}">
                    <option value="">Select...</option>
                    <option v-for="dirAndRes in directivesAndResolutions"
                            :value="dirAndRes.id"
                            :key="dirAndRes.id">
                        {{dirAndRes.title}}
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Activity</label>
                <select class="custom-select" v-model="dirAndResActivityId"
                        v-bind:class="{'is-invalid': !!!dirAndResActivityId}">
                    <option value="">Select...</option>
                    <option v-for="activity in activities"
                            :value="activity.id"
                            :key="activity.id">
                        {{activity.title}}
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <button @click="editActivityOutput()" class="btn btn-primary add-btn btn-sm pull-right mb-md-2 my-sm-2">
                    <i class="fa fa-plus"></i>
                    Add Output
                </button>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-md-12 table-responsive" v-if="!!dirAndResActivityId">
                <app-spinner v-if="isLoading || !plan || !workPlan"/>
                <template v-else>
                    <table class="table table-condensed table-sm">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Unit</th>
                            <th>Target</th>
                            <th>Actual</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="output in dirAndResActivityOutputs">
                            <td>{{output.title}}</td>
                            <td>{{output.unit}}</td>
                            <td>{{output.target}}</td>
                            <td>{{output.actual}}</td>
                            <td>
                                <button class="btn btn-info btn-sm" title="Edit" @click="editActivityOutput(output)"><i
                                    class="fa fa-pencil m-r-5"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </template>
            </div>
        </div>
        <DirAndResActivityOutputForm/>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import {displayErrorMessage} from "../../../utils/helpers";
    import DirAndResActivityOutputForm from "./DirAndResActivityOutputForm";

    export default {
        components: {DirAndResActivityOutputForm},
        created() {
            this.$parent.$on("LOAD_DIR_AND_RES_ACTIVITIES", () => {
                this.$store.dispatch('GET_DIRECTIVES_AND_RESOLUTIONS', {
                    planId: this.plan.id,
                    workPlanId: this.workPlan.id,
                });
                this.getDirAndResActivities();
            });
            EventBus.$on([
                "DIR_AND_RES_ACTIVITY_OUTPUT_SAVED"
            ], this.getDirAndResActivityOutputs);
        },
        data() {
            return {
                isLoading: false,
                isSending: false,
                directiveAndResolutionId: null,
                dirAndResActivityId: null,
            }
        },
        computed: {
            ...mapGetters({
                plan: "ACTIVE_PLAN",
                workPlan: "ACTIVE_WORK_PLAN",
                directivesAndResolutions: "DIRECTIVES_AND_RESOLUTIONS",
                dirAndResActivities: "DIR_AND_RES_ACTIVITIES",
                dirAndResActivityOutputs: "DIR_AND_RES_ACTIVITY_OUTPUTS",
            }),
            activities() {
                if (!!this.directiveAndResolutionId) {
                    return this.dirAndResActivities.filter((activity) => activity.directiveAndResolutionId === this.directiveAndResolutionId);
                }
                return [];
            },
        },
        watch: {
            directiveAndResolutionId(newVal, oldVal) {
                this.getDirAndResActivities();
            },
            dirAndResActivityId(newVal, oldVal) {
                this.getDirAndResActivityOutputs();
            },
        },
        methods: {
            getDirAndResActivities() {
                this.dirAndResActivityId = null;
                if (!!this.directiveAndResolutionId) {
                    this.$store.dispatch('GET_DIR_AND_RES_ACTIVITIES', {
                        directiveAndResolutionId: this.directiveAndResolutionId,
                    });
                }
            },
            async getDirAndResActivityOutputs() {
                if (!!this.directiveAndResolutionId && !!this.dirAndResActivityId) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch('GET_DIR_AND_RES_ACTIVITY_OUTPUTS', {
                            directiveAndResolutionId: this.directiveAndResolutionId,
                            dirAndResActivityId: this.dirAndResActivityId,
                        });
                        this.isLoading = false;
                    } catch (error) {
                        displayErrorMessage(error);
                        this.isLoading = false;
                    }
                }
            },
            editActivityOutput(output = null) {
                EventBus.$emit("EDIT_DIR_AND_RES_ACTIVITY_OUTPUT", output);
            },
            deleteActivityOutput(output) {

            },
        }
    }
</script>

<style scoped>

</style>
