<template>
    <!-- Leave Policy Modal -->
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="hrms-leave-policy-form">
        <form @submit.prevent="saveLeavePolicy">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4">Policy Name<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="leavePolicy.title"
                                   class="form-control"
                                   type="text"
                                   :disabled="!!leavePolicy.id"
                            >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Duration (in days)<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input v-model="leavePolicy.duration"
                                   class="form-control" type="number" min="1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Gender</label>
                        <div class="col-sm-8">
                            <select v-model="leavePolicy.gender" class="form-control">
                                <option value="both">Both</option>
                                <option v-for="gender in formSelectionOptions.genders" :value="gender.slug">
                                    {{gender.title}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Earned leave?</label>
                        <div class="col-md-8">
                            <div class="checkbox-inline">
                                <input v-model="leavePolicy.earnedLeave" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">With weekend?</label>
                        <div class="col-md-8">
                            <div class="checkbox-inline">
                                <input v-model="leavePolicy.withWeekend" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Carry forward?</label>
                        <div class="col-md-8">
                            <div class="checkbox-inline">
                                <input v-model="leavePolicy.carryForward" type="checkbox">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" v-if="!!leavePolicy.carryForward">
                        <label class="col-md-4">Maximum Carry forward Days</label>
                        <div class="col-md-8">
                            <input v-model="leavePolicy.maxCarryForwardDuration"
                                   type="number" :max="leavePolicy.duration"
                                   class="form-control" min="1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Is Active?</label>
                        <div class="col-md-8">
                            <div class="checkbox-inline">
                                <input v-model="leavePolicy.active" type="checkbox">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label>Description </label>
                            <textarea v-model="leavePolicy.description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group leave-duallist">
                        <label>Assign Salary Scales <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-lg-5 col-md-5">
                                <select ref="scaleIds"
                                        class="form-control"
                                        :disabled="!!leavePolicy.id"
                                        multiple
                                >
                                    <option v-for="salaryScale in salaryScales" :key="salaryScale.id"
                                            :value="salaryScale.id"
                                            v-text="salaryScale.scale"></option>
                                </select>
                            </div>
                            <div class="multiselect-controls col-lg-2 col-md-2">
                                <button type="button" :disabled="!!leavePolicy.id" @click="addAll()"
                                        class="btn btn-block btn-white btn-sm"><i
                                    class="fa fa-forward"></i>
                                </button>
                                <button type="button" :disabled="!!leavePolicy.id" @click="addSelected()"
                                        class="btn btn-block btn-white btn-sm"><i
                                    class="fa fa-chevron-right"></i></button>
                                <button type="button" :disabled="!!leavePolicy.id" @click="removeSelected()"
                                        class="btn btn-block btn-white btn-sm"><i
                                    class="fa fa-chevron-left"></i></button>
                                <button type="button" :disabled="!!leavePolicy.id" @click="removeAll()"
                                        class="btn btn-block btn-white btn-sm"><i
                                    class="fa fa-backward"></i></button>
                            </div>
                            <div class="col-lg-5 col-md-5">
                                <select :disabled="!!leavePolicy.id" ref="selectedScaleIds" class="form-control"
                                        multiple></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-primary submit-btn btn-block">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
    <!-- /Leave Policy Modal -->
</template>

<script>
    import LeavePolicy from "../../../models/hrms/LeavePolicy";
    import {mapActions, mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";
    import LeaveType from "../../../models/hrms/LeaveType";

    export default {
        props: {
            leaveTypeId: {type: Number, required: true}
        },
        created() {
            this.cloneSalaryScales();
            EventBus.$on('EDIT_LEAVE_POLICY', this.editLeavePolicy);
        },
        data() {
            return {
                leavePolicy: new LeavePolicy(),
                salaryScales: [],
                selectedSalaryScaleIds: [],
                isEditing: false,
                isSending: false,
            }
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            scales(){
              return this.formSelectionOptions.salaryScales;
            },
            title() {
                return !!this.leavePolicy.id ? "Edit Leave Policy" : "New Leave Policy";
            },
            formInvalid() {
                return this.isSending || !(!!this.leavePolicy.title && !!this.leavePolicy.duration  && this.leavePolicy.selectedSalaryScaleIds.length > 0);
            },
        },
        methods: {
            editLeavePolicy(leavePolicy = null) {
                if (!!leavePolicy) {
                    this.leavePolicy = deepClone(leavePolicy);
                    this.leavePolicy.selectedSalaryScaleIds = this.leavePolicy.salaryScaleIds || [];
                } else {
                    this.leaveType = new LeaveType();
                }
                this.isEditing = true;
            },
            cloneSalaryScales() {
                this.salaryScales = this.scales.map((scale) => {
                    return JSON.parse(JSON.stringify(scale));
                });
            },
            setSelectedScales() {
                if (!!this.$refs.selectedScaleIds) {
                    this.leavePolicy.selectedSalaryScaleIds = [];
                    let selectedOptions = this.$refs.selectedScaleIds.children;
                    for (let i = 0; i < selectedOptions.length; i++) {
                        let value = selectedOptions.item(i).value;
                        //console.log(value);
                        this.leavePolicy.selectedSalaryScaleIds.push(value);
                    }
                }
            },
            moveItems(origin, dest) {
                $(origin).find(':selected').appendTo(dest);
            },
            addAll() {
                $(this.$refs.scaleIds).children().appendTo(this.$refs.selectedScaleIds);
                this.setSelectedScales();
            },
            removeAll() {
                $(this.$refs.selectedScaleIds).children().appendTo(this.$refs.scaleIds);
                this.setSelectedScales();
            },
            addSelected() {
                this.moveItems(this.$refs.scaleIds, this.$refs.selectedScaleIds);
                this.setSelectedScales();
            },
            removeSelected() {
                this.moveItems(this.$refs.selectedScaleIds, this.$refs.scaleIds);
                this.setSelectedScales();
            },

            async saveLeavePolicy() {
                try {
                    if(!!!this.leavePolicy.leaveTypeId){
                        this.leavePolicy.leaveTypeId = this.leaveTypeId;
                    }
                    console.log(this.leavePolicy);
                    if (!!!this.leavePolicy.gender) {
                        let confirmed = await swal({
                            title: "Gender Check!",
                            text: "Are you sure this policy is for both male and female?",
                            icon: 'warning',
                            buttons: [
                                'Select Gender',
                                'Yes'
                            ],
                            closeOnClickOutside: false
                        });
                        if (!!!confirmed) {
                            return;
                        }
                    }
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_LEAVE_POLICY', this.leavePolicy);
                    EventBus.$emit('leavePolicySaved');
                    let wrapper = document.createElement('div');
                    wrapper.innerHTML = response;
                    await swal({content: wrapper});
                    this.resetForm();
                    this.isSending = false;
                } catch (error) {
                    console.log(error);
                    swal({title: error, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.leavePolicy = new LeavePolicy();
                this.leavePolicy.leaveTypeId = this.leaveTypeId;
                this.cloneSalaryScales();
                this.leavePolicy.selectedSalaryScaleIds = [];
                this.removeSelected();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        }
    }
</script>

<style scoped>

</style>
