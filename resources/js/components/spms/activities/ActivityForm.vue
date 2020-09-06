<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveActivity" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">Intervention <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="activity.interventionId" :disabled="!!activity.id" required>
                        <option value="">Select...</option>
                        <option v-for="intervention in interventionsOptions"
                                :value="intervention.value"
                                :key="intervention.value">
                            {{intervention.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Department <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="activity.departmentId" :disabled="!!activity.id" required>
                        <option value="">Select...</option>
                        <option v-for="department in departmentsOptions"
                                :value="department.value"
                                :key="department.value">
                            {{department.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="activity.title" class="form-control" type="text" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Team Leader <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" v-model="activity.teamLeaderId" :disabled="!!activity.id" required>
                        <option value="">Select...</option>
                        <option v-for="employee in employeesOptions"
                                :value="employee.value"
                                :key="employee.value">
                            {{employee.text}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Quarter <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <select v-model="activity.quarter" class="form-control" type="text" required>
                        <option>Select...</option>
                        <option value="Q1">Q1</option>
                        <option value="Q2">Q2</option>
                        <option value="Q3">Q3</option>
                        <option value="Q4">Q4</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Due Date <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <app-date-range-picker
                        v-model="activity.dueDate"
                        :value="activity.dueDate"
                        :config="dateConfig"
                        :key="Math.random() * 10"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <!--                    <textarea v-model="activity.description" class="form-control" rows="5"></textarea>-->
                    <TinymceEditor
                        :api-key="$store.getters.TINYMCE_API_KEY"
                        v-model="activity.description"
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
    import Activity from "../../../models/smps/Activity";
    import {mapGetters} from "vuex";
    import {deepClone} from "../../../utils/helpers";

    export default {
        components: {TinymceEditor: require('@tinymce/tinymce-vue').default,},
        created() {
            this.dateConfig.minDate = this.workPlan.startDate;
            this.dateConfig.maxDate = this.workPlan.endDate;
            EventBus.$on(["EDIT_ACTIVITY"], this.editActivity);
            this.$store.dispatch("GET_DEPARTMENTS_OPTIONS");
        },
        data() {
            return {
                activity: new Activity(),
                employees: [],
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
                interventions: "INTERVENTIONS",
                departments: "DEPARTMENTS_OPTIONS",
            }),
            interventionsOptions() {
                return this.interventions.map((intervention) => {
                    return {
                        text: intervention.name,
                        value: intervention.id,
                    }
                });
            },
            departmentsOptions() {
                return this.departments.map((department) => {
                    return {
                        text: department.title,
                        value: department.id,
                    }
                });
            },
            employeesOptions() {
                return this.employees.map((employee) => {
                    return {
                        text: employee.fullName,
                        value: employee.id,
                    }
                });
            },
            title() {
                return (!!this.activity.id) ? "Edit Activity" : "Add Activity";
            },
            formInvalid() {
                return this.isSending || !(!!this.activity.interventionId && !!this.activity.title && !!this.activity.dueDate && this.$moment(this.activity.dueDate).isBefore(this.workPlan.endDate));
            },
        },
        watch: {
            workPlan(newVal, oldVal) {
                this.dateConfig.minDate = this.workPlan.startDate;
                this.dateConfig.maxDate = this.workPlan.endDate;
            },
            "activity.departmentId"(newVal, oldval) {
                this.activity.teamleaderId = null;
                this.getEmployeesOptions();
            }
        },
        methods: {
            editActivity(activity = null) {
                if (activity) {
                    this.activity = deepClone(activity);
                } else {
                    this.activity = new Activity();
                }
                this.isEditing = true;
            },
            async saveActivity() {
                try {
                    this.isSending = true;
                    if (!this.activity.workPlanId) {
                        this.activity.workPlanId = this.workPlan.id;
                    }
                    let response = await this.$store.dispatch('SAVE_ACTIVITY', this.activity);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('ACTIVITY_SAVED');
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            async getEmployeesOptions() {
                try {
                    if (this.activity.departmentId) {
                        this.employees = await this.$store.dispatch('GET_EMPLOYEE_OPTIONS', {departmentId: this.activity.departmentId});
                    }
                } catch (error) {
                    console.error(error);
                }
            },
            resetForm() {
                this.activity = new Activity();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
