<template>
    <div class="row">
        <div class="col-sm-4 col-lg-3">
            <button class="btn btn-primary btn-block"
                    @click="checkActiveView"><i
                class="fa fa-plus"></i> Add Document
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
                    <app-employee-documents-list
                        :employee-documents="organisationalDocuments">
                    </app-employee-documents-list>
                </div>
            </template>
            <template v-if="activeView === 'employees'">
                <div class="card card-body table-responsive">
                    <h3 class="card-title mb-5">Employees Documents</h3>
                    <app-employee-documents-list
                        :employee-documents="employeesDocuments">
                    </app-employee-documents-list>
                </div>
            </template>
            <app-employee-document-form
                :employee-documents="employeeDocuments">
            </app-employee-document-form>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        props: {
            title: String,
        },
        created() {
            this.$store.dispatch('getFormSelections', {});
            this.getDocuments();
            EventBus.$on(['SAVE_EMPLOYEE_DOCUMENT'], this.getDocuments);
        },
        data() {
            return {
                activeView: 'organisational',
            }
        },
        computed: {
            ...mapGetters({
                employeeDocuments: 'GET_EMPLOYEE_DOCUMENTS',
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
            checkActiveView(event) {
                if (this.activeView === 'employees') {
                    return swal({
                        title: "Add employee related documents from the employee profile page.",
                        icon: 'info'
                    });
                }
                $("#employeeDocumentModal").modal('show');
            }
        },
    }
</script>

<style scoped>

</style>
