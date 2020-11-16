<template>
    <div class="mx-auto">
        <!--          Display some details of the main activity                  -->
        <div class="row">
            <div class="col-md-7 col-lg-8 col-xl-9">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="project-title">
                            <h3 class="card-title">
                                {{ activity.title }}
                                <button type="button"
                                        @click="backToMainActivity()"
                                        class="btn btn-secondary btn-sm float-right">
                                    <i class="fa fa-backward m-r-5"></i> Back To Main Activity
                                </button>
                            </h3>
                        </div>
                        <div class="mx-auto" v-html="activity.description"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-lg-4 col-xl-3">
                <div class="card mb-2">
                    <div class="card-body">
                        <h6 class="card-title m-b-15">Activity Details</h6>
                        <table class="table table-striped table-border">
                            <tbody>
                            <tr>
                                <td>Code</td>
                                <td class="text-right">{{ activity.code }}</td>
                            </tr>
                            <tr>
                                <td>Quarter</td>
                                <td class="text-right">{{ activity.quarter }}</td>
                            </tr>
                            <tr>
                                <td>Cost:</td>
                                <td class="text-right">UGX {{ activity.cost | currency }}</td>
                            </tr>
                            <tr>
                                <td>Created:</td>
                                <td class="text-right">
                                    <template v-if="activity.createdAt">
                                        {{ $moment(activity.createdAt).format('DD MMM, YYYY') }}
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td>Deadline:</td>
                                <td class="text-right">
                                    <template v-if="activity.dueDate">
                                        {{ $moment(activity.dueDate).format('DD MMM, YYYY') }}
                                    </template>
                                </td>
                            </tr>
                            <tr>
                                <td>Team Leader:</td>
                                <td class="text-right">
                                    <a href="#" v-if="teamLeader">{{ teamLeader.fullName }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td class="text-right">{{ activity.status }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <p class="m-b-5">Progress <span
                            class="text-success float-right">{{ activity.completion }}%</span></p>
                        <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip"
                                 :title="`${activity.completion}%`" :style="`width: ${activity.completion}%`"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 m-b-30 table-responsive">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_stages">Stages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="$emit('loadTasks')" data-toggle="tab" href="#tab_tasks">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="$emit('loadOutputs')" data-toggle="tab" href="#tab_outputs">Outputs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="$emit('loadIndicators')" data-toggle="tab" href="#tab_indicators">Indicators</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" @click="$emit('loadAchievements')" data-toggle="tab" href="#tab_targets_and_achievements">Targets &
                            Achievements</a>
                    </li>
                </ul>
                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Activity Stages Tab -->
                    <div class="tab-pane show active" id="tab_stages">
                        <!--  stages   -->
                        <ActivityStages />
                        <!--/stages   -->
                    </div>
                    <!-- /Activity Stages Tab -->
                    <!-- Stage Tasks Tab -->
                    <div class="tab-pane" id="tab_tasks">
                        <!--  tasks   -->
                        <ActivityTasks/>
                        <!--//tasks    -->
                    </div>
                    <!-- /Stage Tasks Tab -->
                    <!-- Activity Outputs Tab -->
                    <div class="tab-pane" id="tab_outputs">
                        <!--  Activity Outputs   -->
                        <ActivityOutputs/>
                        <!--//Activity Outputs    -->
                    </div>
                    <!-- /Activity Outputs Tab -->
                    <!-- Activity Output Indicators Tab -->
                    <div class="tab-pane" id="tab_indicators">
                        <!--  Activity Output Indicators   -->
                        <ActivityOutputIndicators/>
                        <!--//Activity Output Indicators    -->
                    </div>
                    <!-- /Activity Output Indicators Tab -->
                    <!-- Activity Output Indicator Targets Tab -->
                    <div class="tab-pane" id="tab_targets_and_achievements">
                        <!--  Activity Output Indicator Targets  -->
                        <ActivityOutputIndicatorTargetsAndAchievements/>
                        <!--//Activity Output Indicator Targets    -->
                    </div>
                    <!-- /Activity Output Indicator Targets Tab -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import ActivityStages from "../stages/Stages";
import ActivityTasks from "../tasks/Tasks";
import ActivityOutputs from "./ActivityOutputs";
import ActivityOutputIndicators from "./ActivityOutputIndicators";
import ActivityOutputIndicatorTargetsAndAchievements from "./ActivityOutputIndicatorTargetsAndAchievements";

export default {
    components: {
        ActivityStages,
        ActivityTasks,
        ActivityOutputs,
        ActivityOutputIndicators,
        ActivityOutputIndicatorTargetsAndAchievements,
    },
    computed: {
        ...mapGetters({
            workPlan: 'ACTIVE_WORK_PLAN',
            mainActivity: 'ACTIVE_MAIN_ACTIVITY',
            activity: 'ACTIVE_ACTIVITY',
            directoratesOptions: "DIRECTORATES_OPTIONS",
            teamLeader: "TEAM_LEADER_DETAILS",
        }),
    },
    methods: {
        backToMainActivity() {
            this.$store.commit('SET_ACTIVE_ACTIVITY', null);
        }
    }
}
</script>

<style scoped>

</style>
