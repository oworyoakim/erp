<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveWorkPlan" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">
                    Financial Year
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <select v-model="workPlan.financialYear" class="form-control" :disabled="!!workPlan.id" @change="setWorkPlanTitle()">
                        <option value="">Select...</option>
                        <option v-for="financial in plan.financialYears"
                                :value="financial"
                                :key="financial">
                            {{financial}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">
                    Title
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <input type="text" v-model="workPlan.title" class="form-control" readonly/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Theme</label>
                <div class="col-sm-8">
                    <textarea v-model="workPlan.theme" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <textarea v-model="workPlan.description" class="form-control"></textarea>
                </div>
            </div>

            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-info btn-block add-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import WorkPlan from "../../../models/smps/WorkPlan";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";
    import {mapGetters} from "vuex";

    export default {
        props: {},
        created() {
            EventBus.$on('EDIT_WORK_PLAN', this.editWorkPlan);
        },
        computed: {
            ...mapGetters({
                plan: "ACTIVE_PLAN",
            }),
            title() {
                return (!!this.workPlan.id) ? "Edit Work Plan" : "Add Work Plan";
            },
            formInvalid() {
                return this.isSending || !(!!this.workPlan.title && !!this.workPlan.financialYear && !!this.workPlan.theme);
            },
        },
        data() {
            return {
                isEditing: false,
                isSending: false,
                workPlan: new WorkPlan(),
            }
        },
        methods: {
            editWorkPlan(workPlan = null) {
                if (!!workPlan) {
                    this.workPlan = deepClone(workPlan);
                } else {
                    this.workPlan = new WorkPlan();
                }
                this.isEditing = true;
            },
            setWorkPlanTitle(){
                if(!!this.workPlan.financialYear){
                    this.workPlan.title = this.workPlan.financialYear + " Annual Work Plan";
                }else{
                    this.workPlan.title = '';
                }

            },
            async saveWorkPlan() {
                try {
                    this.isSending = true;
                    if(!this.workPlan.planId){
                        this.workPlan.planId = this.plan.id;
                    }
                    let response = await this.$store.dispatch('SAVE_WORK_PLAN', this.workPlan);
                    toastr.success(response);
                    this.isSending = false;
                    this.resetForm();
                    EventBus.$emit('WORK_PLAN_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.workPlan = new WorkPlan();
                this.isEditing = false;
            }
        }
    }
</script>

<style scoped>

</style>
