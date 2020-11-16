<template>
    <div class="work-plan-stages">
        <Spinner v-if="isLoading"/>
        <template v-else>
            <div class="card mb-2">
                <div class="card-header">
                    <!-- Add Stage Button -->
                    <h4 class="card-title">
                        <button @click="editStage()" class="btn btn-primary add-btn float-right mt-2"
                                type="button"><i
                            class="fa fa-plus"></i> Add Stage
                        </button>
                    </h4>
                    <!-- /Add Stage Button -->
                </div>
                <div class="card-body row table-responsive">
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
                            <th>{{ stage.title }}</th>
                            <td>
                                <template v-if="!!stage.startDate">
                                    {{ $moment(stage.startDate).format("DD MMM, YYYY") }}
                                </template>
                            </td>
                            <td>{{ $moment(stage.dueDate).format("DD MMM, YYYY") }}</td>
                            <td>
                                <template v-if="!!stage.endDate">
                                    {{ $moment(stage.endDate).format("DD MMM, YYYY") }}
                                </template>
                            </td>
                            <td>{{ stage.status }}</td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary">Actions</button>
                                    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span class="sr-only">Actions</span>
                                    </button>
                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(72px, -118px, 0px);">
                                        <a @click="editStage(stage)" class="dropdown-item" href="javascript:void(0)">
                                            <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a @click="deleteStage(stage)" class="dropdown-item" href="javascript:void(0)">
                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!--/stages -->
                </div>
            </div>
            <!-- stage form -->
            <StageForm/>
            <!-- /stage form -->
        </template>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import {EventBus} from "../../../app";
import StageForm from "./StageForm";
import Spinner from "../../shared/Spinner";

export default {
    created() {
        EventBus.$on(['STAGE_SAVED'], this.getStages);
    },
    mounted() {
        this.getStages();
    },
    components: {
        StageForm,
        Spinner
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
            stages: "STAGES",
        }),
    },
    methods: {
        editStage(stage = null) {
            EventBus.$emit("EDIT_STAGE", stage);
        },
        deleteStage(stage) {

        },
        async getStages() {
            try {
                console.log("Loading stages");
                this.isLoading = true;
                await this.$store.dispatch("GET_STAGES", {
                    workPlanId: this.activeWorkPlan.id,
                    activityId: this.activity.id,
                });
                this.isLoading = false;
                console.log("Finished Loading stages");
            } catch (error) {
                await swal({text: error, icon: 'error'});
                console.log("Error Loading statges", error);
                this.isLoading = false;
            }
        },
    },

}
</script>

<style scoped>

</style>
