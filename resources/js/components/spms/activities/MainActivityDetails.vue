<template>
    <div class="mx-auto">
        <!--          Display some details of the main activity                  -->
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title">
                    Main Activity: {{ mainActivity.title }}
                    <button type="button"
                            @click="backToWorkPlan()"
                            class="btn btn-secondary btn-sm float-right">
                        <i class="fa fa-backward m-r-5"></i> Back To Work Plan
                    </button>
                </h4>
            </div>
            <div class="card-body table-responsive">
                <ul class="list-group notification-list">
                    <li class="list-group-item">
                        Work Plan:
                        <div class="status-toggle">
                            <a href="javascript:void(0)" @click="backToWorkPlan()">{{workPlan.title}}</a>
                        </div>
                    </li>
                    <li class="list-group-item">
                        Strategic Objective:
                        <div class="status-toggle" v-if="mainActivity.objective">
                            {{mainActivity.objective.name}}
                        </div>
                    </li>
                    <li class="list-group-item">
                        Outcome:
                        <div class="status-toggle" v-if="mainActivity.outcome">
                            {{mainActivity.outcome.name}}
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="float-left">
                            <span class="mr-5">Code:</span>
                            <div class="status-toggle">{{mainActivity.code}}</div>
                        </div>
                        <div class="float-right">
                            <span class="mr-5">Cost:</span>
                            <div class="status-toggle">{{mainActivity.cost | currency}}</div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        Directorates:
                        <div class="status-toggle">{{directorates}}</div>
                    </li>
                    <li class="list-group-item">
                        Action:
                        <div class="status-toggle">
                            <button type="button"
                                    @click="editMainActivity()"
                                    class="btn btn-outline-info btn-sm">
                                <i class="fa fa-pencil m-r-5"></i> Edit
                            </button>
                            <button type="button"
                                    @click="deleteMainActivity()"
                                    class="btn btn-outline-danger btn-sm">
                                <i class="fa fa-trash-o m-r-5"></i> Delete
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!--          Display activities                  -->
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title">
                    Activities
                    <button type="button"
                            @click="editActivity()"
                            class="btn btn-success add-btn float-right">
                        <i class="fa fa-plus m-r-5"></i> Add Activity
                    </button>
                </h4>
            </div>
            <div class="card-body">
                <!-- activities -->
                <Activities />
                <!--/activities -->
                <!-- activity form -->
                <ActivityForm/>
                <!-- /activity form -->
            </div>
        </div>
        <MainActivityForm/>
    </div>
</template>
<script>
import MainActivityForm from "./MainActivityForm";
import ActivityOutputs from "../activities/ActivityOutputs";
import ActivityOutputIndicators from "../activities/ActivityOutputIndicators";
import ActivityOutputIndicatorTargetsAndAchievements from "../activities/ActivityOutputIndicatorTargetsAndAchievements";
import Activities from "./Activities";
import {mapGetters} from "vuex";
import {EventBus} from "../../../app";
import ActivityForm from "./ActivityForm";

export default {
    components: {
        ActivityForm,
        MainActivityForm,
        Activities,
        ActivityOutputIndicators,
        ActivityOutputs,
        ActivityOutputIndicatorTargetsAndAchievements,
    },
    created() {
        console.log("Initializes Main Activity Details");
    },
    computed: {
        ...mapGetters({
            workPlan: 'ACTIVE_WORK_PLAN',
            mainActivity: 'ACTIVE_MAIN_ACTIVITY',
            directoratesOptions: "DIRECTORATES_OPTIONS",
        }),
        directorates(){
            return this.directoratesOptions
                .filter((directorate) => this.mainActivity.directorateIds.includes(directorate.id))
                .map((directorate) => directorate.title)
                .join(' | ');
        },
    },
    methods: {
        backToWorkPlan() {
            this.$store.commit('SET_ACTIVE_MAIN_ACTIVITY', null);
        },
        editMainActivity() {
            EventBus.$emit('EDIT_MAIN_ACTIVITY', this.mainActivity);
        },
        deleteMainActivity() {

        },
        editActivity(activity = null) {
            EventBus.$emit("EDIT_ACTIVITY", activity);
        },
    }
}
</script>

<style scoped>

</style>
