<template>
    <div class="bank-list">
        <ul class="personal-info" v-for="bank in banks">
            <li>
                <a v-if="$store.getters.HAS_ANY_ACCESS(['employees.manage_bank_info'])" href="javascript:void(0)" class="edit-icon pull-right" data-toggle="modal"
                   @click="editBank(bank)"><i class="fa fa-pencil"></i></a></li>
            <li>
                <div class="title">Bank Name:</div>
                <div class="small text-muted-light">{{bank.bankName}}</div>
            </li>
            <li>
                <div class="title">Bank Branch:</div>
                <div class="small text-muted-light">{{bank.bankBranch}}</div>
            </li>
            <li>
                <div class="title">Account Name:</div>
                <div class="small text-muted-light">{{bank.accountName}}</div>
            </li>
            <li>
                <div class="title">Account No.</div>
                <div class="small text-muted-light">{{bank.accountNumber}}</div>
            </li>
            <li>
                <div class="title">Swift Code:</div>
                <div class="small text-muted">{{bank.swiftCode}}</div>
            </li>
        </ul>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            employeeId: {type: Number, required: true},
            banks: {type: Array, default: () => []},
        },
        created() {
        },
        data() {
            return {}
        },
        methods: {
            editBank(bank = null) {
                EventBus.$emit("EDIT_BANK_INFO", bank);
            }
        }
    }
</script>

<style scoped>
    .bottom-dashed {
        border-bottom: 2px dashed #ccc;
    }
</style>
