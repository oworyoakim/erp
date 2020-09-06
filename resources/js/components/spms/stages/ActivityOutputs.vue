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
                    <button @click="editOutput()" class="btn btn-primary add-btn mt-2"
                            type="button"><i
                        class="fa fa-plus"></i> Add Output
                    </button>
                </div>
                <!-- /Add Stage Button -->
            </div>
        </div>
        <div class="row payroll-table card">
            <div class="col-sm-12 table-responsive">
                <!-- Output -->
                <table class="table table-hover table-radius">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="output in outputs">
                        <tr :key="output.id">
                            <th>{{output.name}}</th>
                            <td>{{$stringLimit(output.description)}}</td>
                            <td class="text-right">
                                <button class="btn btn-info btn-sm" title="Edit" @click="editOutput(output)"><i
                                    class="fa fa-pencil m-r-5"></i></button>
                                <button class="btn btn-danger btn-sm" title="Delete" @click="deleteOutput(output)"><i
                                    class="fa fa-trash-o m-r-5"></i></button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <!--/Outputs -->
                <!-- Output form -->
                <OutputForm :activity="true"/>
                <!-- /Output form -->
            </div>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import OutputForm from "../outputs/OutputForm";

    export default {
        components: {OutputForm},
        created() {
            EventBus.$on(['OUTPUT_SAVED'], this.getOutputs);
        },
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
                outputs: "OUTPUTS",
            }),
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
            activityId(newValue, oldValue) {
                this.getOutputs();
            },
        },
        methods: {
            async getOutputs() {
                if (!!this.interventionId && !!this.activityId) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("GET_OUTPUTS", {
                            interventionId: this.interventionId,
                            activityId: this.activityId,
                        });
                        this.isLoading = false;
                    } catch (error) {
                        console.error(error);
                        this.isLoading = false;
                    }
                }
            },
            editOutput(output = null) {
                EventBus.$emit("EDIT_OUTPUT", {
                    output,
                    activityId: this.activityId,
                    interventionId: this.interventionId,
                });
            },
            deleteOutput(stage) {

            }
        },

    }
</script>

<style scoped>

</style>
