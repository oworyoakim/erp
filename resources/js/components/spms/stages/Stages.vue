<template>
    <div class="work-plan-stages">
        <div class="row mb-2">
            <div class="col-lg-4 col-md-4 col-sm-6">
                <label>Intervention</label>
                <select class="custom-select" v-model="interventionId">
                    <option value="">Select...</option>
                    <option v-for="intervention in interventionsOptions"
                            :value="intervention.value"
                            :key="intervention.value">{{intervention.text}}
                    </option>
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <label>Activity</label>
                <select class="custom-select" v-model="activityId">
                    <option value="">Select...</option>
                    <option v-for="activity in activitiesOptions"
                            :value="activity.value"
                            :key="activity.value">{{activity.text}}
                    </option>
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 text-right">
                <!-- Add Stage Button -->
                <div class="text-right mb-4 clearfix">
                    <button @click="editStage()" class="btn btn-primary add-btn mt-2"
                            type="button"><i
                        class="fa fa-plus"></i> Add Stage
                    </button>
                </div>
                <!-- /Add Stage Button -->
            </div>
        </div>
        <div class="row payroll-table card">
            <div class="col-sm-12 table-responsive">
                <!-- stages -->
                <table class="table table-hover table-radius">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>StartDate</th>
                        <th>DueDate</th>
                        <th>EndDate</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="stage in stages" :key="stage.id">
                        <th>{{stage.title}}</th>
                        <td>
                            <template v-if="!!stage.startDate">
                                {{$moment(stage.startDate).format("DD MMM, YYYY")}}
                            </template>
                        </td>
                        <td>{{$moment(stage.dueDate).format("DD MMM, YYYY")}}</td>
                        <td>
                            <template v-if="!!stage.endDate">
                                {{$moment(stage.endDate).format("DD MMM, YYYY")}}
                            </template>
                        </td>
                        <td>{{stage.status}}</td>
                        <td class="text-right">
                            <button class="btn btn-info btn-sm" title="Edit" @click="editStage(stage)"><i
                                class="fa fa-pencil m-r-5"></i></button>
                            <button class="btn btn-danger btn-sm" title="Delete" @click="deleteStage(stage)"><i
                                class="fa fa-trash-o m-r-5"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!--/stages -->
                <!-- stage form -->
                <app-stage-form/>
                <!-- /stage form -->
            </div>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        data() {
            return {
                interventionId: '',
                activityId: '',
            }
        },
        computed: {
            ...mapGetters({
                activeWorkPlan: "ACTIVE_WORK_PLAN",
                interventions: "INTERVENTIONS",
            }),
            stages() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.activeWorkPlan.stages.filter((stage) => {
                    return stage.activityId === this.activityId;
                });
            },
            interventionsOptions() {
                return this.interventions.map((intervention) => {
                    return {
                        text: intervention.name,
                        value: intervention.id,
                    }
                });
            },
            activitiesOptions() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.activeWorkPlan.activities.filter((activity) => {
                    return activity.interventionId === this.interventionId;
                }).map((activity) => {
                    return {
                        text: activity.title,
                        value: activity.id,
                    }
                });
            },
        },
        watch: {
            interventionId(newValue, oldValue) {
                this.activityId = '';
            },
        },
        methods: {
            editStage(stage = null) {
                EventBus.$emit("EDIT_STAGE", stage);
            },
            deleteStage(stage) {

            }
        },

    }
</script>

<style scoped>

</style>
