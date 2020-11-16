<template>
    <div class="work-plan-activities">
        <Spinner v-if="isLoading"/>
        <template v-else>
            <div class="row mb-2">
                <div class="col-lg-9 col-md-8 col-sm-6">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Directorate</span>
                        </div>
                        <select class="custom-select" v-model="directorateId">
                            <option value="">Select...</option>
                            <option v-for="directorate in directoratesOptions"
                                    :value="directorate.value"
                                    :key="directorate.value">{{ directorate.text }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row payroll-table card">
                <div class="col-sm-12 table-responsive">
                    <table class="table table-hover table-radius">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>StartDate</th>
                            <th>EndDate</th>
                            <th>DueDate</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-if="activities.length > 0">
                            <tr v-for="activity in activitiesFiltered" :key="activity.id">
                                <th>{{ activity.title }}</th>
                                <td>
                                    <template v-if="!!activity.startDate">
                                        {{ $moment(activity.startDate).format("DD MMM, YYYY") }}
                                    </template>
                                </td>

                                <td>
                                    <template v-if="!!activity.endDate">
                                        {{ $moment(activity.endDate).format("DD MMM, YYYY") }}
                                    </template>
                                </td>
                                <td>{{ $moment(activity.dueDate).format("DD MMM, YYYY") }}</td>
                                <td>{{ activity.status }}</td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary">Actions</button>
                                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <span class="sr-only">Actions</span>
                                        </button>
                                        <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(72px, -118px, 0px);">
                                            <a @click="setActiveActivity(activity)" class="dropdown-item" href="javascript:void(0)">
                                                <i class="fa fa-eye m-r-5"></i> View</a>
                                            <a @click="editActivity(activity)" class="dropdown-item" href="javascript:void(0)">
                                                <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                            <a @click="deleteActivity(activity)" class="dropdown-item" href="javascript:void(0)">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void(0)">Approve</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <th colspan="6">No Activities!</th>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import {EventBus} from "../../../app";
import {mapGetters} from "vuex";
import ActivityForm from "./ActivityForm";
import Spinner from "../../shared/Spinner";

export default {
    components: {Spinner, ActivityForm},
    created() {
        console.log("Initializes activities");
        EventBus.$on(['ACTIVITY_SAVED'], this.getActivities);
    },
    mounted() {
        this.getActivities();
    },
    data() {
        return {
            isLoading: false,
            directorateId: '',
        }
    },
    computed: {
        ...mapGetters({
            activeWorkPlan: "ACTIVE_WORK_PLAN",
            mainActivity: "ACTIVE_MAIN_ACTIVITY",
            interventions: "INTERVENTIONS",
            activities: "ACTIVITIES",
            directorates: "DIRECTORATES_OPTIONS",
        }),
        activitiesFiltered() {
            if (!this.directorateId) {
                return this.activities;
            }
            return this.activities.filter((activity) => {
                return activity.directorateId === this.directorateId;
            });
        },
        directoratesOptions() {
            return this.directorates.map((directorate) => {
                return {
                    text: directorate.title,
                    value: directorate.id,
                }
            });
        },
    },
    methods: {
        editActivity(activity = null) {
            EventBus.$emit("EDIT_ACTIVITY", activity);
        },
        deleteActivity(activity) {

        },

        setActiveActivity(activity) {
            this.$store.commit('SET_ACTIVE_ACTIVITY', activity);
        },
        async getActivities() {
            try {
                console.log("Loading activities");
                this.isLoading = true;
                await this.$store.dispatch("GET_ACTIVITIES", {
                    workPlanId: this.activeWorkPlan.id,
                    activityBlockId: this.mainActivity.id,
                });
                this.isLoading = false;
                console.log("Finished Loading activities");
            } catch (error) {
                await swal({text: error, icon: 'error'});
                console.log("Error Loading activities", error);
                this.isLoading = false;
            }
        },
    },

}
</script>

<style scoped>

</style>
