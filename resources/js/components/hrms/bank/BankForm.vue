<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()" key="bank-info">
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
                <button :disabled="formInvalid" class="btn btn-primary btn-block submit-btn">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Submit</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>

<script>
    import Bank from "../../../models/hrms/Bank";
    import {deepClone} from "../../../utils/helpers";
    import {EventBus} from "../../../app";

    export default {
        props: {
            employeeId: {type: Number, required: true},
        },
        created() {
            EventBus.$on("EDIT_BANK_INFO", this.editBank);
        },
        data() {
            return {
                bank: new Bank(),
                isSending: false,
                isEditing: false,
            }
        },
        computed: {
            title() {
                return !!this.bank.id ? "Edit Bank Info" : "New Bank Info";
            },
            formInvalid() {
                return this.isSending || !(!!this.bank.bankName && !!this.bank.bankBranch && !!this.bank.accountName && !!this.bank.accountNumber);
            }
        },
        methods: {
            editBank(bank = null) {
                if (!!bank) {
                    this.bank = deepClone(bank);
                } else {
                    this.bank = new Bank();
                    this.bank.employeeId = this.employeeId;
                }
                this.isEditing = true;
            },
            async saveBankInfo() {
                try {
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_BANK_INFO', this.bank);
                    toastr.success(response);
                    this.isSending = false;
                    this.$emit('BANK_INFO_SAVED');
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
                this.bank = new Bank();
                this.bank.employeeId = this.employeeId;
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        }
    }
</script>

<style scoped>

</style>
