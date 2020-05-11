<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveStage" autocomplete="off">
            <div class="form-group row" v-if="!stage.id">
                <label class="col-sm-4">Intervention <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="interventionId" :disabled="!!stage.id" required>
                        <option value="">Select...</option>
                        <option v-for="intervention in interventionsOptions"
                                :value="intervention.value"
                                :key="intervention.value">
                            {{intervention.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" v-if="!stage.id">
                <label class="col-sm-4">Activity <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="stage.activityId" :disabled="!!stage.id" required>
                        <option value="">Select...</option>
                        <option v-for="activity in activitiesOptions"
                                :value="activity.value"
                                :key="activity.value">
                            {{activity.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="stage.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Due Date <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <app-date-range-picker
                        v-model="stage.dueDate"
                        :value="stage.dueDate"
                        :config="dateConfig"
                        :key="Math.random() * 10"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <textarea v-model="stage.description" class="form-control" rows="5"></textarea>
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
    import Stage from "../../../models/smps/Stage";
    import {mapGetters} from "vuex";
    import {deepClone} from "../../../utils/helpers";

    export default {
        created() {
            EventBus.$on(["EDIT_STAGE"], this.editStage);
        },
        data() {
            return {
                stage: new Stage(),
                isEditing: false,
                isSending: false,
                interventionId: '',
                dateConfig: {
                    showDropdowns: true,
                    singleDatePicker: true,
                    minDate: null,
                    maxDate: null,
                    opens: "center",
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                },
            }
        },
        computed: {
            ...mapGetters({
                workPlan: "ACTIVE_WORK_PLAN",
                interventions: "INTERVENTIONS",
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
                if (!this.workPlan) {
                    return [];
                }
                return this.workPlan.activities.filter((activity) => {
                    return activity.interventionId === this.interventionId;
                }).map((activity) => {
                    return {
                        text: activity.title,
                        value: activity.id,
                    }
                });
            },
            title() {
                return (!!this.stage.id) ? "Edit Stage" : "Add Stage";
            },
            formInvalid() {
                return this.isSending || !(!!this.stage.activityId && !!this.stage.title && !!this.stage.dueDate);
            },
            selectedActivity() {
                if (!this.workPlan) {
                    return null;
                }
                return this.workPlan.activities.find((activity) => activity.id === this.stage.activityId) || null;
            },
        },
        watch: {
            selectedActivity(newVal, oldVal) {
                if (!!this.selectedActivity) {
                    this.dateConfig.minDate = this.workPlan.startDate;
                    this.dateConfig.maxDate = this.selectedActivity.dueDate;
                }
            }
        },
        methods: {
            editStage(stage = null) {
                if (stage) {
                    this.stage = deepClone(stage);
                } else {
                    this.stage = new Stage();
                }
                this.isEditing = true;
            },
            async saveStage() {
                try {
                    this.isSending = true;
                    if (!this.stage.workPlanId) {
                        this.stage.workPlanId = this.workPlan.id;
                    }
                    let response = await this.$store.dispatch('SAVE_STAGE', this.stage);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('STAGE_SAVED');
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
                this.stage = new Stage();
                this.interventionId = '';
                this.activityId = '';
                this.isEditing = false;
            }
        }
    }
</script>

<style scoped>

</style>
