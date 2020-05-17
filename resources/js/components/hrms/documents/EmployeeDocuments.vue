<template>
    <div class="employee-documents">
        <app-spinner v-if="isLoading"/>
        <template v-else-if="!!!employee.id">
            <h4>No employee selected!</h4>
        </template>
        <template v-else>
            <div class="card profile-box flex-fill">
                <div class="card-body table-responsive">
                    <h3 class="card-title mb-5">
                        {{title}}
                        <button v-if="!!showAddButton" class="edit-icon" @click="uploadEmployeeDocument()"
                                title="Add Document">
                            <i class="fa fa-plus"></i>
                        </button>
                    </h3>
                    <EmployeeDocumentsList :employee-documents="employeeDocuments"/>
                    <EmployeeDocumentForm
                        :employee-documents="employeeDocuments"
                        :employee-id="employee.id"
                        :employee-number="employee.employeeNumber"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import EmployeeDocumentsList from "./EmployeeDocumentsList";
    import EmployeeDocumentForm from "./EmployeeDocumentForm";

    export default {
        components: {EmployeeDocumentForm, EmployeeDocumentsList},
        props: {
            employee: {
                type: Object,
                default: function () {
                    return {id: null}
                },
            },
            title: String,
            showAddButton: {
                type: Boolean,
                default: true,
            },
        },
        created() {
            this.$parent.$on('LOAD_EMPLOYEE_DOCUMENTS', this.getDocuments);
            EventBus.$on(['EMPLOYEE_DOCUMENT_SAVED'], this.getDocuments);
        },
        computed: {
            ...mapGetters({
                employeeDocuments: 'EMPLOYEE_DOCUMENTS',
            }),
        },
        data() {
            return {
                isLoading: false,
            }
        },
        methods: {
            uploadEmployeeDocument() {
                EventBus.$emit("UPLOAD_EMPLOYEE_DOCUMENT");
            },
            async getDocuments() {
                try {
                    this.isLoading = true;
                    let data = {};
                    if (!!this.employee.id) {
                        data.employeeId = this.employee.id;
                    }
                    await this.$store.dispatch('GET_EMPLOYEE_DOCUMENTS', data);
                    this.isLoading = false;
                } catch (error) {
                    console.error(error);
                    this.isLoading = false;
                }
            }
        },
    }
</script>

<style scoped>

</style>
