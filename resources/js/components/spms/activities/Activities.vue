<template>
    <div class="work-plan-activities">
        <div class="row mb-2">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Intervention</span>
                    </div>
                    <select class="custom-select" v-model="interventionId">
                        <option value="">Select...</option>
                        <option v-for="intervention in interventionsOptions"
                                :value="intervention.value"
                                :key="intervention.value">{{intervention.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 text-right">
                <!-- Add Activity Button -->
                <div class="text-right mb-4 clearfix">
                    <button @click="editActivity()" class="btn btn-primary add-btn mt-2"
                            type="button"><i
                        class="fa fa-plus"></i> Add Activity
                    </button>
                </div>
                <!-- /Add Activity Button -->
            </div>
        </div>
        <div class="row payroll-table card">
            <div class="col-sm-12 table-responsive">
                <!-- activities -->
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
                    <tr v-for="activity in activities" :key="activity.id">
                        <th>{{activity.title}}</th>
                        <td>{{$moment(activity.startDate).format("DD MM, YYYY")}}</td>
                        <td>{{$moment(activity.dueDate).format("DD MM, YYYY")}}</td>
                        <td>
                            <template v-if="!!activity.endDate">
                                {{$moment(activity.endDate).format("DD MM, YYYY")}}
                            </template>
                        </td>
                        <td>{{activity.status}}</td>
                        <td class="text-right">
                            <button class="btn btn-info btn-sm" title="Edit" @click="editActivity(activity)"><i
                                class="fa fa-pencil m-r-5"></i></button>
                            <button class="btn btn-danger btn-sm" title="Delete" @click="deleteActivity(activity)"><i
                                class="fa fa-trash-o m-r-5"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!--/activities -->
                <!-- activity form -->
                <app-activity-form/>
                <!-- /activity form -->
            </div>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        data(){
            return {
                interventionId: '',
            }
        },
        computed: {
            ...mapGetters({
                activeWorkPlan: "ACTIVE_WORK_PLAN",
                interventions: "INTERVENTIONS",
            }),
            activities() {
                if (!this.activeWorkPlan) {
                    return [];
                }
                return this.activeWorkPlan.activities.filter((activity) => {
                    return activity.interventionId === this.interventionId;
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
        },
        methods: {
            editActivity(activity = null) {
                EventBus.$emit("EDIT_ACTIVITY", activity);
            },
            deleteActivity(activity) {

            }
        },

    }
</script>

<style scoped>

</style>
