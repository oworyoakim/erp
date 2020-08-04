<template>
    <div class="mt-1">
        <app-spinner v-if="isLoading || !plan || !workPlan"/>
        <template v-else>
            <div class="row">
                <label class="col-md-3">Directive and resolution</label>
                <div class="col-md-6">
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
                <div class="col-md-3">
                    <button @click="editActivity()" class="btn btn-primary add-btn btn-sm pull-right mb-md-2 my-sm-2">
                        <i class="fa fa-plus"></i>
                        Add Activity
                    </button>
                </div>
            </div>
            <div class="row m-b-10">
                <div class="col-md-12" v-if="!!directiveAndResolutionId">
                    <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded mb-3">
                        <li class="nav-item">
                            <a class="nav-link active"
                               @click="dirAndResActivityStatus = null;"
                               href="javascript:void(0);"
                               data-toggle="tab">All</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               @click="dirAndResActivityStatus = 'submitted';"
                               href="javascript:void(0);"
                               data-toggle="tab">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               @click="dirAndResActivityStatus = 'approved';"
                               href="javascript:void(0);"
                               data-toggle="tab">Approved</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               @click="dirAndResActivityStatus = 'declined';"
                               href="javascript:void(0);"
                               data-toggle="tab">Declined</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               @click="dirAndResActivityStatus = 'ongoing';"
                               href="javascript:void(0);"
                               data-toggle="tab">In Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               @click="dirAndResActivityStatus = 'onhold';"
                               href="javascript:void(0);"
                               data-toggle="tab">On Hold</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               @click="dirAndResActivityStatus = 'completed';"
                               href="javascript:void(0);"
                               data-toggle="tab">Completed</a>
                        </li>
                    </ul>
                </div>
                <template v-for="activity in activities">
                    <div class="col-md-4">
                        <DirAndResActivityCard :activity="activity" />
                    </div>
                </template>
            </div>
            <DirAndResActivityForm/>
            <DirAndResActivityCompletionForm />
        </template>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import {displayErrorMessage} from "../../../utils/helpers";
    import DirAndResActivityForm from "./DirAndResActivityForm";
    import DirAndResActivityCard from "./DirAndResActivityCard";
    import DirAndResActivityCompletionForm from "./DirAndResActivityCompletionForm";

    export default {
        components: {DirAndResActivityCard, DirAndResActivityForm, DirAndResActivityCompletionForm},
        created() {
            this.$parent.$on("LOAD_DIR_AND_RES_ACTIVITIES", () => {
                this.$store.dispatch('GET_DIRECTIVES_AND_RESOLUTIONS', {
                    planId: this.plan.id,
                    workPlanId: this.workPlan.id,
                });
                this.getDirAndResActivities();
            });
            EventBus.$on([
                "DIR_AND_RES_ACTIVITY_SAVED"
            ], this.getDirAndResActivities);
        },
        data() {
            return {
                isLoading: false,
                isSending: false,
                directiveAndResolutionId: null,
                dirAndResActivityStatus: null,
            }
        },
        computed: {
            ...mapGetters({
                plan: "ACTIVE_PLAN",
                workPlan: "ACTIVE_WORK_PLAN",
                directivesAndResolutions: "DIRECTIVES_AND_RESOLUTIONS",
                dirAndResActivities: "DIR_AND_RES_ACTIVITIES",
            }),
            activities(){
                if(!!this.dirAndResActivityStatus){
                    return this.dirAndResActivities.filter((activity) => activity.status === this.dirAndResActivityStatus);
                }
                return this.dirAndResActivities;
            },
        },
        watch: {
            directiveAndResolutionId(newVal, oldVal) {
                this.getDirAndResActivities();
            }
        },
        methods: {
            async getDirAndResActivities() {
                if (!!this.directiveAndResolutionId) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch('GET_DIR_AND_RES_ACTIVITIES', {
                            directiveAndResolutionId: this.directiveAndResolutionId,
                        });
                        this.isLoading = false;
                    } catch (error) {
                        displayErrorMessage(error);
                        this.isLoading = false;
                    }
                }
            },
            editActivity(activity = null) {
                EventBus.$emit("EDIT_DIR_AND_RES_ACTIVITY", activity);
            },
            deleteActivity(activity) {

            },
        }
    }
</script>

<style scoped>

</style>
