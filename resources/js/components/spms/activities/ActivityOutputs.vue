<template>
    <div class="activity-outputs">
        <Spinner v-if="isLoading" />
        <template v-else>
            <div class="card mb-2">
                <div class="card-header">
                    <!-- Add Output Button -->
                    <h4 class="card-title">
                        <button @click="editOutput()" class="btn btn-primary add-btn float-right mt-2"
                                type="button"><i
                            class="fa fa-plus"></i> Add Output
                        </button>
                    </h4>
                    <!-- /Add Output Button -->
                </div>
                <div class="card-body table-responsive">
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
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary">Actions</button>
                                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span class="sr-only">Actions</span>
                                        </button>
                                        <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(72px, -118px, 0px);">
                                            <a @click="editOutput(output)" class="dropdown-item" href="javascript:void(0)">
                                                <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a @click="deleteOutput(output)" class="dropdown-item" href="javascript:void(0)">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                    <!--/Outputs -->
                </div>
            </div>
            <!-- Output form -->
            <OutputForm />
            <!-- /Output form -->
        </template>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import OutputForm from "../outputs/OutputForm";
    import Spinner from "../../shared/Spinner";

    export default {
        components: {Spinner, OutputForm},
        created() {
            EventBus.$on(['OUTPUT_SAVED'], this.getOutputs);
            this.$parent.$on('loadOutputs', this.getOutputs);
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
                outputs: "OUTPUTS",
            }),
        },
        methods: {
            async getOutputs() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("GET_OUTPUTS", {
                        activityId: this.activity.id,
                    });
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    this.isLoading = false;
                }
            },
            editOutput(output = null) {
                EventBus.$emit("EDIT_OUTPUT", output);
            },
            deleteOutput(stage) {

            }
        },

    }
</script>

<style scoped>

</style>
