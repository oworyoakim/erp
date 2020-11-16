<template>
    <div class="work-plan-tasks">
        <Spinner v-if="isLoading"/>
        <template v-else>
            <div class="card mb-2">
                <div class="card-header">
                    <!-- Add Task Button -->
                    <h4 class="card-title">
                        <button @click="editTask()" class="btn btn-primary add-btn float-right"
                                type="button"><i
                            class="fa fa-plus"></i> Add Task
                        </button>
                    </h4>
                    <!-- /Add Task Button -->
                </div>
                <div class="card-body table-responsive">
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
                            <td>
                                <template v-if="!!task.startDate">
                                    {{$moment(task.startDate).format("DD MMM, YYYY")}}
                                </template>
                            </td>
                            <td>{{$moment(task.dueDate).format("DD MMM, YYYY")}}</td>
                            <td>
                                <template v-if="!!task.endDate">
                                    {{$moment(task.endDate).format("DD MMM, YYYY")}}
                                </template>
                            </td>
                            <td>{{task.status}}</td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary">Actions</button>
                                    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span class="sr-only">Actions</span>
                                    </button>
                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(72px, -118px, 0px);">
                                        <a @click="editTask(task)" class="dropdown-item" href="javascript:void(0)">
                                            <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a @click="deleteTask(task)" class="dropdown-item" href="javascript:void(0)">
                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="javascript:void(0)">Approve</a>
                                        <a class="dropdown-item" href="javascript:void(0)">Mark As Complete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!--/tasks -->
                </div>
            </div>
            <!-- task form -->
            <TaskForm/>
            <!-- /task form -->
        </template>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import TaskForm from "./TaskForm";
    import Spinner from "../../shared/Spinner";

    export default {
        created() {
            EventBus.$on(['TASK_SAVED','TASK_DELETED'], this.getTasks);
            this.$parent.$on('loadTasks', this.getTasks);
        },
        mounted() {
            //this.getTasks();
        },
        components: {
            Spinner,
            TaskForm,
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                activeWorkPlan: "ACTIVE_WORK_PLAN",
                mainActivity: "ACTIVE_MAIN_ACTIVITY",
                activity: "ACTIVE_ACTIVITY",
                tasks: "TASKS",
            }),
        },
        methods: {
            editTask(task = null) {
                EventBus.$emit("EDIT_TASK", task);
            },
            deleteTask(task) {

            },
            async getTasks(){
                try {
                    console.log("Loading tasks");
                    this.isLoading = true;
                    await this.$store.dispatch("GET_TASKS", {
                        workPlanId: this.activeWorkPlan.id,
                        activityId: this.activity.id,
                    });
                    this.isLoading = false;
                    console.log("Finished Loading tasks");
                } catch (error) {
                    await swal({text: error, icon: 'error'});
                    console.log("Error Loading tasks", error);
                    this.isLoading = false;
                }
            },
        },

    }
</script>

<style scoped>

</style>
