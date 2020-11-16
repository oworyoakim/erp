<template>
    <div class="mt-1">
        <template v-if="isLoading">
            <app-spinner></app-spinner>
        </template>
        <template v-else>
            <div class="input-group my-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Select Objective: </span>
                </div>
                <select v-model="objectiveId" class="form-control">
                    <option value="">....Select....</option>
                    <option v-for="objective in objectives"
                            :value="objective.id"
                            :key="objective.id">
                        {{objective.name}}
                    </option>
                </select>
            </div>
            <div class="input-group my-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Select Report Period: </span>
                </div>
                <select v-model="reportPeriod" class="form-control">
                    <option value="">....Select....</option>
                    <option v-for="period in reportPeriods"
                            :value="period.id"
                            :key="period.id">
                        {{period.name}}
                    </option>
                </select>
            </div>
            <form @submit.prevent="saveOutputAchievements">
                <div class="my-2" v-if="!isFetching && outputAchievementsChanged">
                    <button type="submit"
                            :disabled="isSending"
                            class="btn btn-primary pull-right mb-4">
                        <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                        <span>Save</span>
                    </button>
                </div>

                <div class="my-2">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-light">
                            <th>Output</th>
                            <th>Indicator</th>
                            <th>Target</th>
                            <th>Actual</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-if="isFetching">
                            <tr>
                                <td colspan="4">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <template v-for="output in outputAchievements">
                                <template v-if="output.indicators.length === 0">
                                    <tr>
                                        <td>{{output.name}}</td>
                                        <td colspan="3">No indicators</td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td :rowspan="output.indicators.length + 1">
                                            {{output.name}}
                                        </td>
                                    </tr>
                                    <template v-for="indicator in output.indicators">
                                        <tr>
                                            <td>{{indicator.name}}</td>
                                            <td>
                                                <input v-if="indicator.unit === 'percent'"
                                                       :value="indicator.target + '%'"
                                                       class="form-control form-control-sm text-right"
                                                       readonly>
                                                <input v-else :value="indicator.target"
                                                       class="form-control form-control-sm text-right"
                                                       readonly>
                                            </td>
                                            <td class="text-right">
                                                <input v-model="indicator.achievement"
                                                       class="form-control form-control-sm text-right"
                                                       required>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </template>
                        </template>
                        </tbody>
                    </table>
                </div>
            </form>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        created() {
            EventBus.$on(["OUTPUT_ACHIEVEMENT_SAVED"], this.getOutputAchievements);
            this.$parent.$on("LOAD_OUTPUT_DATA", this.getObjectives);
        },
        computed: {
            ...mapGetters({
                activePlan: 'ACTIVE_PLAN',
                objectives: "OBJECTIVES",
                oldOutputAchievements: 'OUTPUT_ACHIEVEMENTS',
            }),
            reportPeriods() {
                if (!this.activePlan) {
                    return [];
                }
                return this.activePlan.reportPeriods;
            },
            outputAchievementsChanged() {
                return !this.$isEqual(this.outputAchievements, this.oldOutputAchievements);
            },
        },
        watch: {
            objectiveId(newValue, oldVale) {
                this.getOutputAchievements();
            },
            reportPeriod(newValue, oldVale) {
                this.getOutputAchievements();
            },
        },
        data() {
            return {
                isLoading: false,
                isFetching: false,
                isSending: false,
                objectiveId: '',
                reportPeriod: '',
                outputAchievements: [],
            }
        },
        methods: {
            async getObjectives() {
                if (!!this.activePlan) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("GET_OBJECTIVES", {planId: this.activePlan.id});
                        this.isLoading = false;
                    } catch (error) {
                        console.log(error);
                        this.isLoading = false;
                    }
                }
            },
            async getOutputAchievements() {
                try {
                    this.outputAchievements = [];
                    if (!!!this.objectiveId || !!!this.reportPeriod) {
                        return;
                    }
                    this.isFetching = true;
                    this.outputAchievements = await this.$store.dispatch("GET_OUTPUT_ACHIEVEMENTS", {
                        objectiveId: this.objectiveId,
                        reportPeriodId: this.reportPeriod
                    });
                    this.$store.commit("SET_OUTPUT_ACHIEVEMENTS", deepClone(this.outputAchievements));
                    this.isFetching = false;
                } catch (error) {
                    await swal({title: error, icon: 'error'});
                    this.isFetching = false;
                    this.$store.commit("SET_OUTPUT_ACHIEVEMENTS", deepClone(this.outputAchievements));
                }
            },
            async saveOutputAchievements() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_OUTPUT_ACHIEVEMENT", {
                        achievements: this.outputAchievements,
                    });
                    this.isSending = false;
                    toastr.success(response);
                    EventBus.$emit("OUTPUT_ACHIEVEMENT_SAVED");
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
        }
    }
</script>

<style scoped>

</style>
