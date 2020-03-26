<template>
    <!-- Delegation Modal -->
    <div
        ref="delegationModal"
        id="delegationModal"
        class="modal custom-modal fade"
        role="dialog"
        tabindex="-1"
        data-backdrop="static"
        data-keyboard="false"
    >
        <div class="modal-dialog modal-lg modal-dialog-centered" role="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delegation Form</h5>
                    <button
                        @click="closePreview()"
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveDelegation">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Your Permissions</label>
                                <ul class="list-unstyled">
                                    <li v-for="permission in permissions" :key="permission.slug">{{permission.title}}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <label>Permissions To Delegate</label>
                                <div class="row" v-for="permission in permissions" :key="permission.slug">
                                    <div class="col-sm-8">{{permission}}</div>
                                    <div class="col-sm-4">
                                        <input
                                            type="checkbox"
                                            @change="handleCheckbox"
                                            :value="permission.slug"
                                            v-model="delegation.permissions"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Duration</label>
                            <div class="col-sm-8">
                                <input v-model="delegation.duration" class="form-control" type="number"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-4">
                                Effective Date
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-4">
                                <label class="radio-inline">
                                    <input v-model="delegation.effectiveFrom" type="radio" value="now"/>Now
                                </label>
                                <label class="radio-inline">
                                    <input v-model="delegation.effectiveFrom" type="radio" value="later"/>Later
                                </label>
                            </div>
                            <div class="col-sm-4" v-if="delegation.effectiveFrom === 'later'">
                                <app-date-range-picker
                                    :config="effectiveDateConfig"
                                    v-model="delegation.startDate"
                                    :value="delegation.startDate"
                                ></app-date-range-picker>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">
                                Reason
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <textarea v-model="delegation.reason" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="submit-section text-right">
                            <button
                                :disabled="isSending || !!!delegation.duration || !!!delegation.delegatedId || delegation.permissions.length === 0 || (delegation.effectiveFrom === 'later' && !!!delegation.startDate)"
                                class="btn btn-primary submit-btn"
                            >Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delegation Modal -->
</template>

<script>
    import Delegation from "../../../models/hrms/Delegation";
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            employee: {
                type: Object,
                default: () => {
                }
            }
        },
        created() {
            EventBus.$on("editDelegation", this.editDelegation);
        },
        data() {
            return {
                delegation: new Delegation(),
                isSending: false,
                effectiveDateConfig: {
                    showDropdowns: true,
                    singleDatePicker: true,
                    minDate: this.$moment(), // today
                    opens: "center",
                    locale: {
                        format: "DD MMM YYYY"
                    }
                }
            };
        },
        computed: {
            permissions() {
                return [];
            }
        },
        methods: {
            editDelegation(delegation) {
                this.delegation = delegation;
                $(this.$refs.delegationModal).modal("show");
            },
            handleCheckbox() {

            },
            async saveDelegation() {
                try {
                    this.isSending = true;

                    let response = await this.$store.dispatch(
                        "SAVE_DELEGATION",
                        this.delegation
                    );
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit("delegationSaved");
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    //toastr.error(error);
                    swal({title: error, icon: "error"});
                    this.isSending = false;
                }
            },
            closePreview() {
                this.experience = new Experience();
                $(this.$refs.delegationModal).modal("hide");
                $(".modal-backdrop").remove();
            }
        }
    };
</script>

<style scoped>
</style>
