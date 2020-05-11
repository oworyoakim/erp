<template>
    <div class="bank-info">
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employeeId">
            <h4>No employee selected!</h4>
        </template>
        <template v-else>
            <div class="card profile-box flex-fill">
                <div class="card-body text-lg">
                    <h3 class="card-title">
                        Bank Details
                        <button v-if="banks.length === 0" @click="editBank()" class="edit-icon">
                            <i class="fa fa-plus"></i></button>
                    </h3>
                    <BankList :employee-id="employeeId" :banks="banks"/>
                    <BankForm :employee-id="employeeId" v-on:BANK_INFO_SAVED="getBankInfo()"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import BankForm from "./BankForm";
    import BankList from "./BankList";

    export default {
        props: {
            employeeId: {type: Number, required: true},
        },
        components: {BankList, BankForm,},
        created() {
            this.$parent.$on('LOAD_BANK_INFO', this.getBankInfo);
            EventBus.$on([
                'EDUCATION_INFO_SAVED',
                'EDUCATION_INFO_DELETED',
            ], this.getBankInfo);
        },
        data() {
            return {
                isLoading: false,
            }
        },
        computed: {
            ...mapGetters({
                banks: "BANKS",
            })
        },
        methods: {
            async getBankInfo() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch("GET_BANK_INFO", {employeeId: this.employeeId});
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
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
