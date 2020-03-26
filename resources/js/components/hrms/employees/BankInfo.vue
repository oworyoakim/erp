<template>
    <div class="card profile-box flex-fill">
        <div class="card-body text-lg">
            <h3 class="card-title">
                Bank Details
                <button v-if="banks.length === 0" class="edit-icon" data-toggle="modal"
                        data-target="#bankModal"><i class="fa fa-plus"></i></button>
            </h3>
            <ul class="personal-info" v-for="bk in banks">
                <li><a href="javascript:void(0)" class="edit-icon pull-right" data-toggle="modal"
                       @click="editBank(bk)"><i class="fa fa-pencil"></i></a></li>
                <li>
                    <div class="title">Bank Name: </div>
                    <div class="text">{{bk.bankName}}</div>
                </li>
                <li>
                    <div class="title">Bank Branch: </div>
                    <div class="text">{{bk.bankBranch}}</div>
                </li>
                <li>
                    <div class="title">Account Name:</div>
                    <div class="text">{{bk.accountName}}</div>
                </li>
                <li>
                    <div class="title">Account No.</div>
                    <div class="text">{{bk.accountNumber}}</div>
                </li>
                <li>
                    <div class="title">Swift Code:</div>
                    <div class="text">{{bk.swiftCode}}</div>
                </li>
            </ul>
            <!-- Bank Modal -->
            <div ref="bankModal" id="bankModal" class="modal custom-modal fade" role="dialog" tabindex="-1"
                 data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Bank Form</h5>
                            <button @click="closePreview()" type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form @submit.prevent="saveBankInfo">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">Bank Name </label>
                                    <div class="col-sm-8">
                                        <input v-model="bank.bankName" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">Branch </label>
                                    <div class="col-sm-8">
                                        <input v-model="bank.bankBranch" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">Account Name </label>
                                    <div class="col-sm-8">
                                        <input v-model="bank.accountName" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">Account Number </label>
                                    <div class="col-sm-8">
                                        <input v-model="bank.accountNumber" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-sm-4">Swift Code </label>
                                    <div class="col-sm-8">
                                        <input v-model="bank.swiftCode" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button
                                        :disabled="!!!bank.bankName || !!!bank.bankBranch || !!!bank.accountName || !!!bank.accountNumber"
                                        class="btn btn-primary submit-btn">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Bank Modal -->
        </div>
    </div>
</template>

<script>
    import Bank from "../../../models/hrms/Bank";

    export default {
        props: {
            employee_id: Number,
            banks: Array,
        },
        created() {
        },
        data() {
            return {
                bank: new Bank(),
                isSending: false,
            }
        },
        methods: {
            editBank(bank){
                this.bank = bank;
                $(this.$refs.bankModal).modal('show');
            },
            async saveBankInfo() {
                try {
                    this.isSending = true;
                    if (!!this.bank.id) {
                        // update
                        let response = await this.$http.put('/employees/bank', {
                            id: this.bank.id,
                            employee_id: this.employee_id,
                            bank_name: this.bank.bankName,
                            bank_branch: this.bank.bankBranch,
                            account_name: this.bank.accountName,
                            account_number: this.bank.accountNumber,
                            swift_code: this.bank.swiftCode,
                        });
                        toastr.success(response.data);
                    } else {
                        // insert new
                        let response = await this.$http.post('/employees/bank', {
                            employee_id: this.employee_id,
                            bank_name: this.bank.bankName,
                            bank_branch: this.bank.bankBranch,
                            account_name: this.bank.accountName,
                            account_number: this.bank.accountNumber,
                            swift_code: this.bank.swiftCode,
                        });
                        toastr.success(response.data);
                    }
                    this.isSending = false;
                    this.$emit('bankInfoSaved');
                    this.closePreview();
                } catch (error) {
                    console.log(error);
                    toastr.error(error.response.data);
                    this.isSending = false;
                }
            },
            closePreview() {
                this.bank = new Bank();
                $(this.$refs.bankModal).modal('hide');
                $('.modal-backdrop').remove();
            },
        }
    }
</script>

<style scoped>
    .bottom-dashed {
        border-bottom: 2px dashed #ccc;
    }
</style>
