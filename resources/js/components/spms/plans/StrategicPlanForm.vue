<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="savePlan" autocomplete="off">
            <div class="form-group row">
                <label class="col-sm-4">
                    Title
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <input type="text" v-model="plan.name" class="form-control"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Start Date - End Date <br/>
                    <small class="text-muted">Format: {{dateConfig.locale.format}}</small></label>
                <div class="col-sm-8">
                    <app-date-range-picker
                        v-model="dateRange"
                        :value="dateRange"
                        :config="dateConfig"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Theme</label>
                <div class="col-sm-8">
                    <textarea v-model="plan.theme" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Vision</label>
                <div class="col-sm-8">
                    <textarea v-model="plan.vision" class="form-control"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4">Mission</label>
                <div class="col-sm-8">
                    <textarea v-model="plan.mission" class="form-control"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4">Values</label>
                <div class="col-sm-8">
                    <textarea v-model="plan.values" class="form-control"></textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4">Monitor Frequency</label>
                <div class="col-sm-8">
                    <select v-model="plan.frequency" class="form-control">
                        <option value="">Select...</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="4-months">Every 4 months</option>
                        <option value="6-months">Every 6 months</option>
                        <option value="yearly">Yearly</option>
                    </select>
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
    import Plan from "../../../models/smps/Plan";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        props: {
            startOfNextFinancialYear: String,
        },
        created() {
            EventBus.$on('EDIT_PLAN', this.editPlan);
            this.dateRange = moment(this.startOfNextFinancialYear).format('YYYY-MM-DD') + ' - ' + moment(this.startOfNextFinancialYear).add(12, 'months').subtract(1, 'days').format('YYYY-MM-DD');
        },
        watch: {
            dateRange(newValue, oldValue) {
                this.setPlanDates(newValue);
            },
        },
        computed: {
            title() {
                return (!!this.plan.id) ? "Edit Strategic Plan" : "Add Strategic Plan";
            },
            formInvalid() {
                return this.isSending || !(!!this.plan.name && !!this.plan.theme && !!this.plan.frequency && !!this.plan.startDate && !!this.plan.endDate);
            },
        },
        data() {
            return {
                isEditing: false,
                isSending: false,
                dateRange: '',
                plan: new Plan(),
                dateConfig: {
                    showDropdowns: true,
                    minDate: this.$moment(), // now
                    ranges: {
                        'Next Financial Year': [this.$moment(this.startOfNextFinancialYear), this.$moment(this.startOfNextFinancialYear).add(12, 'months').subtract(1, 'days')],
                        'Next 2 Financial Years': [this.$moment(this.startOfNextFinancialYear), this.$moment(this.startOfNextFinancialYear).add(24, 'months').subtract(1, 'days')],
                        'Next 3 Financial Years': [this.$moment(this.startOfNextFinancialYear), this.$moment(this.startOfNextFinancialYear).add(36, 'months').subtract(1, 'days')],
                        'Next 4 Financial Years': [this.$moment(this.startOfNextFinancialYear), this.$moment(this.startOfNextFinancialYear).add(48, 'months').subtract(1, 'days')],
                        'Next 5 Financial Years': [this.$moment(this.startOfNextFinancialYear), this.$moment(this.startOfNextFinancialYear).add(60, 'months').subtract(1, 'days')],
                    },
                    opens: "center",
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                }
            }
        },
        methods: {
            editPlan(plan = null) {
                if (!!plan) {
                    this.plan = deepClone(plan);
                    this.dateRange = this.plan.startDate + ' - ' + this.plan.endDate;
                } else {
                    this.plan.plan_id = this.plan.id;
                    this.plan = new Plan();
                    this.dateRange = this.$moment(this.startOfNextFinancialYear).format('YYYY-MM-DD') + ' - ' + this.$moment(this.startOfNextFinancialYear).add(12, 'months').subtract(1, 'days').format('YYYY-MM-DD');
                    this.setPlanDates(this.dateRange);
                }
                this.isEditing = true;
            },
            async savePlan() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_PLAN', this.plan);
                    toastr.success(response);
                    this.isSending = false;
                    this.resetForm();
                    EventBus.$emit('PLAN_SAVED');
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            setPlanDates(dateRange) {
                let dates = dateRange.split(' - ');
                this.plan.startDate = dates[0];
                this.plan.endDate = dates[dates.length - 1];
            },
            resetForm() {
                this.plan = new Plan();
                this.isEditing = false;
                this.dateRange = this.$moment(this.startOfNextFinancialYear).format('YYYY-MM-DD') + ' - ' + this.$moment(this.startOfNextFinancialYear).add(12, 'months').subtract(1, 'days').format('YYYY-MM-DD');
                this.setPlanDates(this.dateRange);
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
