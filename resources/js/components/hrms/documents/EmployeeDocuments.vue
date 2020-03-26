<template>
    <div v-else class="card profile-box flex-fill">
        <div class="card-body table-responsive">
            <h3 class="card-title mb-5">
                {{title}}
                <button v-if="!!showAddButton" class="edit-icon" data-toggle="modal"
                        data-target="#employeeDocumentModal" title="Add Document"><i class="fa fa-plus"></i></button>
            </h3>
            <app-employee-documents-list
                :employee-documents="employeeDocuments">
            </app-employee-documents-list>
            <app-employee-document-form
                :employee-documents="employeeDocuments"
                :employee-id="employee.id">
            </app-employee-document-form>
        </div>
        <div class="tab-content">
            <!-- Employee Info Tab -->
            <div id="employee-info" class="pro-overview tab-pane fade active show">
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
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
            this.getDocuments();
            EventBus.$on(['SAVE_EMPLOYEE_DOCUMENT'], this.getDocuments);
        },
        computed: {
            ...mapGetters({
                employeeDocuments: 'GET_EMPLOYEE_DOCUMENTS',
            }),
        },
        data() {
            return {}
        },
        methods: {
            async getDocuments() {
                try {
                    let data = {};
                    if (!!this.employee.id) {
                        data.employee_id = this.employee.id;
                    }
                    await this.$store.dispatch('GET_EMPLOYEE_DOCUMENTS', data);
                } catch (error) {
                    console.error(error);
                }
            }
        },
    }
</script>

<style scoped>

</style>
