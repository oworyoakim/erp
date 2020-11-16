<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveMainActivity" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">Strategic Objective <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="mainActivity.objectiveId" required>
                        <option value="">Select...</option>
                        <option v-for="objective in objectivesOptions"
                                :value="objective.value"
                                :key="objective.value">
                            {{objective.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Outcome <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="mainActivity.outcomeId" required>
                        <option value="">Select...</option>
                        <option v-for="outcome in outcomesOptions"
                                :value="outcome.value"
                                :key="outcome.value">
                            {{outcome.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="mainActivity.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Code <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="mainActivity.code" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Quarter <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select v-model="mainActivity.quarter" class="form-control" type="text" required>
                        <option>Select...</option>
                        <option value="Q1">Q1</option>
                        <option value="Q2">Q2</option>
                        <option value="Q3">Q3</option>
                        <option value="Q4">Q4</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Cost <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input type="number"
                           v-model="mainActivity.cost"
                           class="form-control"
                           min="0"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Directorates <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <label class="form-check" v-for="directorate in directoratesOptions">
                        <input
                            type="checkbox"
                            :value="directorate.value"
                            :key="directorate.value"
                            v-model="mainActivity.directorateIds"
                            multiple>
                        {{directorate.text}}
                    </label>
                    <!--                    <select class="form-control custom-select"-->
                    <!--                            v-model="mainActivity.directorateIds"-->
                    <!--                            multiple-->
                    <!--                            required>-->
                    <!--                        <option value="">Select...</option>-->
                    <!--                        <option v-for="directorate in directoratesOptions"-->
                    <!--                                :value="directorate.value"-->
                    <!--                                :key="directorate.value">-->
                    <!--                            {{directorate.text}}-->
                    <!--                        </option>-->
                    <!--                    </select>-->
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                <label>Description</label>
                    <TinymceEditor
                        :api-key="$store.getters.TINYMCE_API_KEY"
                        v-model="mainActivity.description"
                    />
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import {deepClone} from "../../../utils/helpers";
    import MainActivity from "../../../models/smps/MainActivity";

    export default {
        components: {TinymceEditor: require('@tinymce/tinymce-vue').default,},
        created() {
            console.log("Initializes Main Activity Form");
            EventBus.$on(["EDIT_MAIN_ACTIVITY"], this.editMainActivity);
            this.$store.dispatch("GET_OBJECTIVES", {planId: this.workPlan.planId});
        },
        data() {
            return {
                mainActivity: new MainActivity(),
                employees: [],
                isEditing: false,
                isSending: false,
            }
        },
        computed: {
            ...mapGetters({
                workPlan: "ACTIVE_WORK_PLAN",
                outcomes: "OUTCOMES_OPTIONS",
                objectives: "OBJECTIVES",
                directorates: "DIRECTORATES_OPTIONS",
            }),
            directoratesOptions() {
                return this.directorates.map((directorate) => {
                    return {
                        text: directorate.title,
                        value: directorate.id,
                    }
                });
            },
            outcomesOptions() {
                return this.outcomes.map((outcome) => {
                    return {
                        text: outcome.name,
                        value: outcome.id,
                    }
                });
            },

            objectivesOptions() {
                return this.objectives.map((objective) => {
                    return {
                        text: objective.name,
                        value: objective.id,
                    }
                });
            },
            title() {
                return (!!this.mainActivity.id) ? "Edit Main Activity" : "Add Main Activity";
            },
            formInvalid() {
                return this.isSending || !(!!this.mainActivity.objectiveId && !!this.mainActivity.outcomeId && !!this.mainActivity.title && !!this.mainActivity.code && !!this.mainActivity.quarter);
            },
        },
        watch: {
            "mainActivity.objectiveId"(newVal, oldVal){
                let objective = this.objectives.find((objective) => objective.id === this.mainActivity.objectiveId);
                if(objective){
                    this.$store.dispatch("GET_OUTCOMES_OPTIONS", {
                        keyResultAreaId: objective.keyResultAreaId
                    });
                }
            },
        },
        methods: {
            editMainActivity(activity = null) {
                if (activity) {
                    this.mainActivity = deepClone(activity);
                } else {
                    this.mainActivity = new MainActivity();
                }
                this.isEditing = true;
            },
            async saveMainActivity() {
                try {
                    this.isSending = true;
                    if (!this.mainActivity.workPlanId) {
                        this.mainActivity.workPlanId = this.workPlan.id;
                    }
                    let response = await this.$store.dispatch('SAVE_MAIN_ACTIVITY', this.mainActivity);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('MAIN_ACTIVITY_SAVED');
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.mainActivity = new MainActivity();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
