<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveStage" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="stage.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Due Date <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <DateRangePicker
                        v-model="stage.dueDate"
                        :value="stage.dueDate"
                        :config="dateConfig"
                        :key="Math.random() * 10"
                    />
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label>Description</label>
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
import DateRangePicker from "../../shared/DateRangePicker";

export default {
    components: {DateRangePicker},
    created() {
        EventBus.$on(["EDIT_STAGE"], this.editStage);
    },
    data() {
        return {
            stage: new Stage(),
            isEditing: false,
            isSending: false,
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
            mainActivity: "ACTIVE_MAIN_ACTIVITY",
            activity: "ACTIVE_ACTIVITY",
        }),
        title() {
            return (!!this.stage.id) ? "Edit Stage" : "Add Stage";
        },
        formInvalid() {
            return this.isSending || !(!!this.stage.title && !!this.stage.dueDate);
        },
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
                if (!this.stage.activityId) {
                    this.stage.activityId = this.activity.id;
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
            this.isEditing = false;
            $('.modal-backdrop').remove();
        }
    }
}
</script>

<style scoped>

</style>
