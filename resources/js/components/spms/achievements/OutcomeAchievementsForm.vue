<template>
    <div class="mt-1">
        <template v-if="isLoading">
            <app-spinner></app-spinner>
        </template>
        <template v-else>
            <div class="input-group my-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">Select Key Result area: </span>
                </div>
                <select v-model="keyResultAreaId" class="form-control">
                    <option value="">....Select....</option>
                    <option v-for="keyResultArea in keyResultAreas"
                            :value="keyResultArea.id"
                            :key="keyResultArea.id">
                        {{keyResultArea.name}}
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
            <form @submit.prevent="saveOutcomeAchievements">
                <div class="my-2" v-if="!isFetching && outcomeAchievementsChanged">
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
                            <th>Outcome</th>
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
                            <template v-for="outcome in outcomeAchievements">
                                <template v-if="outcome.indicators.length === 0">
                                    <tr>
                                        <td>{{outcome.name}}</td>
                                        <td colspan="3">No indicators</td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td :rowspan="outcome.indicators.length + 1">
                                            {{outcome.name}}
                                        </td>
                                    </tr>
                                    <template v-for="indicator in outcome.indicators">
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
    import {deepClone} from "../../../utils/helpers";
    import {EventBus} from "../../../app";

    export default {
        created() {
            EventBus.$on(["OUTCOME_ACHIEVEMENT_SAVED"], this.getOutcomeAchievements);
            this.$parent.$on(["LOAD_OUTCOME_DATA"],this.getKeyResultAreas);
        },
        computed: {
            ...mapGetters({
                activePlan: 'ACTIVE_PLAN',
                keyResultAreas: "KEY_RESULT_AREAS",
                oldOutcomeAchievements: 'OUTCOME_ACHIEVEMENTS',
            }),
            reportPeriods() {
                if (!this.activePlan) {
                    return [];
                }
                return this.activePlan.reportPeriods;
            },
            outcomeAchievementsChanged() {
                return !this.$isEqual(this.outcomeAchievements, this.oldOutcomeAchievements);
            },
        },
        data() {
            return {
                isLoading: false,
                isFetching: false,
                isSending: false,
                keyResultAreaId: '',
                reportPeriod: '',
                outcomeAchievements: [],
            }
        },
        watch:{
            keyResultAreaId(newValue, oldVale) {
                this.getOutcomeAchievements();
            },
            reportPeriod(newValue, oldVale) {
                this.getOutcomeAchievements();
            },
        },
        methods: {
            async getKeyResultAreas() {
                if (!!this.activePlan) {
                    try {
                        this.isLoading = true;
                        await this.$store.dispatch("GET_KEY_RESULT_AREAS", {planId: this.activePlan.id});
                        this.isLoading = false;
                    } catch (error) {
                        console.log(error);
                        this.isLoading = false;
                    }
                }
            },
            async getOutcomeAchievements() {
                try {
                    this.outcomeAchievements = [];
                    if (!!!this.keyResultAreaId || !!!this.reportPeriod) {
                        return;
                    }
                    this.isFetching = true;
                    this.outcomeAchievements = await this.$store.dispatch("GET_OUTCOME_ACHIEVEMENTS", {
                        keyResultAreaId: this.keyResultAreaId,
                        reportPeriodId: this.reportPeriod
                    });
                    this.$store.dispatch("SET_OUTCOME_ACHIEVEMENTS", deepClone(this.outcomeAchievements));
                    this.isFetching = false;
                } catch (error) {
                    await swal({title: error, icon: 'error'});
                    this.isFetching = false;
                    this.$store.dispatch("SET_OUTCOME_ACHIEVEMENTS", deepClone(this.outcomeAchievements));
                }
            },
            async saveOutcomeAchievements() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch("SAVE_OUTCOME_ACHIEVEMENT", {
                        achievements: this.outcomeAchievements,
                    });
                    this.isSending = false;
                    toastr.success(response);
                    EventBus.$emit("OUTCOME_ACHIEVEMENT_SAVED");
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            }
        },
    }
</script>

<style scoped>

</style>
