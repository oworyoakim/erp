<template>
    <div class="row">
        <div class="col-sm-4 col-lg-3">
            <button class="btn btn-primary btn-block" @click="uploadDocument()">
                <i class="fa fa-plus"></i> Add Document
            </button>
            <div class="roles-menu">
                <ul>
                    <li v-bind:class="{active: activeView === 'organisational'}">
                        <a href="javascript:void(0)" @click="setActiveView('organisational')">Organisational</a>
                    </li>
                    <li v-bind:class="{active: activeView === 'employees'}">
                        <a href="javascript:void(0)" @click="setActiveView('employees')">
                            Employee Related</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-sm-8 col-lg-9">
            <template v-if="activeView === 'organisational'">
                <div class="card card-body table-responsive">
                    <h3 class="card-title mb-5">Organisational Documents</h3>
                    <EmployeeDocumentsList
                        :employee-documents="organisationalDocuments"
                        key="organisational-documents"
                    />
                </div>
            </template>
            <template v-if="activeView === 'employees'">
                <div class="card card-body table-responsive">
                    <h3 class="card-title mb-5">Employees Documents</h3>
                    <EmployeeDocumentsList
                        :employee-documents="employeesDocuments"
                        key="employees-documents"
                    />
                </div>
            </template>
            <EmployeeDocumentForm :employee-documents="employeeDocuments"/>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";
    import EmployeeDocumentsList from "./EmployeeDocumentsList";
    import EmployeeDocumentForm from "./EmployeeDocumentForm";

    export default {
        components: {EmployeeDocumentForm, EmployeeDocumentsList},
        props: {
            title: String,
        },
        created() {
            this.$store.dispatch('GET_FORM_SELECTIONS_OPTIONS', {});
            this.getDocuments();
            EventBus.$on(['EMPLOYEE_DOCUMENT_SAVED'], this.getDocuments);
        },
        data() {
            return {
                activeView: 'organisational',
            }
        },
        computed: {
            ...mapGetters({
                employeeDocuments: 'EMPLOYEE_DOCUMENTS',
            }),
            organisationalDocuments() {
                return this.employeeDocuments.filter((employeeDocument) => {
                    return !!employeeDocument.documentCategory && !!employeeDocument.documentCategory.nonEmployee;
                });
            },
            employeesDocuments() {
                return this.employeeDocuments.filter((employeeDocument) => {
                    return !!employeeDocument.documentCategory && !!!employeeDocument.documentCategory.nonEmployee;
                });
            },
        },
        methods: {
            async getDocuments() {
                try {
                    let data = {};
                    await this.$store.dispatch('GET_EMPLOYEE_DOCUMENTS', data);
                } catch (error) {
                    console.error(error);
                }
            },
            setActiveView(activeView) {
                this.activeView = activeView;
            },
            uploadDocument() {
                if (this.activeView === 'employees') {
                    return swal({
                        title: "Add employee related documents from the employee profile page.",
                        icon: 'info'
                    });
                }
                EventBus.$emit("UPLOAD_EMPLOYEE_DOCUMENT");
            },
        },
    }
</script>

<style scoped>

</style>
