<template>
    <div class="work-plan-tasks">
        <div class="row mb-2">
            <div class="col-sm-6">
                <label>Intervention</label>
                <select class="custom-select" v-model="interventionId">
                    <option value="">Select...</option>
                    <option v-for="intervention in interventionsOptions"
                            :value="intervention.value"
                            :key="intervention.value">{{intervention.text}}
                    </option>
                </select>
            </div>
            <div class="col-sm-6">
                <label>Activity</label>
                <select class="custom-select" v-model="activityId">
                    <option value="">Select...</option>
                    <option v-for="activity in activitiesOptions"
                            :value="activity.value"
                            :key="activity.value">{{activity.text}}
                    </option>
                </select>
            </div>
            <div class="col-sm-6">
                <label>Stage</label>
                <select class="custom-select" v-model="stageId">
                    <option value="">Select...</option>
                    <option v-for="stage in stagesOptions"
                            :value="stage.value"
                            :key="stage.value">{{stage.text}}
                    </option>
                </select>
            </div>
            <div class="col-sm-6 text-right">
                <!-- Add Task Button -->
                <div class="text-right mb-4 clearfix">
                    <button @click="editTask()" class="btn btn-primary add-btn mt-2"
                            type="button"><i
                        class="fa fa-plus"></i> Add Task
                    </button>
                </div>
                <!-- /Add Task Button -->
            </div>
        </div>
        <div class="row payroll-table card">
            <div class="col-sm-12 table-responsive">
                <!-- tasks -->
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
                    <tr v-for="task in tasks" :key="task.id">
                        <th>{{task.title}}</th>
                        <td>{{$moment(task.startDate).format("DD MMM, YYYY")}}</td>
                        <td>{{$moment(task.dueDate).format("DD MMM, YYYY")}}</td>
                        <td>
                            <template v-if="!!task.endDate">
                                {{$moment(task.endDate).format("DD MMM, YYYY")}}
                            </template>
                        </td>
                        <td>{{task.status}}</td>
                        <td class="text-right">
                            <button class="btn btn-info btn-sm" title="Edit" @click="editTask(task)"><i
                                class="fa fa-pencil m-r-5"></i></button>
                            <button class="btn btn-danger btn-sm" title="Delete" @click="deleteTask(task)"><i
                                class="fa fa-trash-o m-r-5"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!--/tasks -->
                <!-- task form -->
                <app-task-form/>
                <!-- /task form -->
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
                stageId: '',
            }
        },
        computed: {
            ...mapGetters({
                activeWorkPlan: "ACTIVE_WORK_PLAN",
                interventions: "INTERVENTIONS",
            }),
            tasks() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.activeWorkPlan.tasks.filter((task) => {
                    return task.stageId === this.stageId;
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
            stagesOptions() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.activeWorkPlan.stages.filter((stage) => {
                    return stage.activityId === this.activityId;
                }).map((stage) => {
                    return {
                        text: stage.title,
                        value: stage.id,
                    }
                });
            },
        },
        watch: {
            interventionId(newValue, oldValue) {
                this.activityId = '';
                this.stageId = '';
            },
            activityId(newValue, oldValue) {
                this.stageId = '';
            },
        },
        methods: {
            editTask(task = null) {
                EventBus.$emit("EDIT_TASK", task);
            },
            deleteTask(task) {

            }
        },

    }
</script>

<style scoped>

</style>
