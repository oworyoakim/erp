<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveEmployeeDocument" ref="employeeDocumentForm">
            <div class="form-group row">
                <label class="col-sm-4">Category</label>
                <div class="col-sm-8">
                    <select v-model="employeeDocument.documentCategoryId"
                            class="form-control"
                            :disabled="!!employeeDocument.id"
                            required>
                        <option value="">Select category...</option>
                        <option v-for="documentCategory in documentCategories"
                                :value="documentCategory.value"
                                v-text="documentCategory.text"></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Document Type</label>
                <div class="col-sm-8">
                    <select v-model="employeeDocument.documentTypeId"
                            class="form-control"
                            :disabled="!!employeeDocument.id"
                            required>
                        <option value="">Select type...</option>
                        <option v-for="documentType in documentTypes" :value="documentType.value"
                                v-text="documentType.text"></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Title <span class="text-danger">*</span></label>
                <div class="col-sm-8">
                    <input v-model="employeeDocument.title"
                           type="text"
                           class="form-control"
                           required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <textarea v-model="employeeDocument.description" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Document<br/>
                    <small>(Allowed formats: jpeg, jpg, png, bmp, pdf)</small>
                </label>
                <div class="col-sm-8">
                    <input type="file"
                           class="form-control"
                           accept=".png, .jpg, .jpeg, .bmp, .pdf"
                           v-on:change="employeeDocument.path = $event.target.files[0]"
                           ref="employeeDocumentFile"
                           required>
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
    import {mapGetters} from "vuex";
    import EmployeeDocument from "../../../models/hrms/EmployeeDocument";
    import {EventBus} from "../../../app";

    export default {
        props: {
            employeeId: Number,
            employeeNumber: String,
            employeeDocuments: {type: Array, default: () => []},
        },
        created() {
            EventBus.$on("UPLOAD_EMPLOYEE_DOCUMENT", this.uploadDocument);
        },
        computed: {
            ...mapGetters({
                formSelectionOptions: 'FORM_SELECTIONS_OPTIONS',
            }),
            documentCategories() {
                return this.formSelectionOptions.documentCategories.filter((category) => {
                    if (!!this.employeeId) {
                        return !category.nonEmployee;
                    }
                    return !!category.nonEmployee;
                }).map((category) => {
                    return {
                        value: category.id,
                        text: category.title,
                    }
                });
            },
            documentTypes() {
                return this.formSelectionOptions.documentTypes.filter((documentType) => {
                    return documentType.categoryId === this.employeeDocument.documentCategoryId;
                }).map((documentType) => {
                    return {
                        value: documentType.id,
                        text: documentType.title,
                    }
                });
            },
            title(){
                return (!!this.employeeId && !!this.employeeNumber) ? "Upload Employee Document" : "Upload Company Document";
            },
            formInvalid(){
                return this.isSending || !(!!this.employeeDocument.title && !!this.employeeDocument.documentCategoryId && !!this.employeeDocument.path);
            },
        },
        data() {
            return {
                isSending: false,
                isEditing: false,
                employeeDocument: new EmployeeDocument(),
            }
        },
        methods: {
            uploadDocument(){
                this.employeeDocument = new EmployeeDocument();
                this.$refs.employeeDocumentForm.reset();
                this.$refs.employeeDocumentFile.value = '';
                this.isEditing = true;
            },
            async saveEmployeeDocument() {
                try {
                    this.employeeDocument.employeeId = this.employeeId || null;
                    this.employeeDocument.employeeNumber = this.employeeNumber || null;
                    let maxSize = 1024 * 1024;
                    if (this.employeeDocument.path.size > maxSize) {
                        await swal({
                            title: "Your file is larger than  1MB.",
                            icon: 'error',
                        });
                        return;
                    }
                    let allowedFileFormats = ['image/jpeg', 'image/jpg', 'image/bmp', 'image/png', 'application/pdf'];
                    console.log(this.employeeDocument.path);
                    if (!allowedFileFormats.includes(this.employeeDocument.path.type)) {
                        await swal({
                            title: "The allowed file formats are: mimes,jpeg,jpg,bmp,png,pdf.",
                            icon: 'error',
                        });
                        return;
                    }
                    let employeeDocument = this.employeeDocuments.find((doc) => {
                        return doc.documentTypeId === this.employeeDocument.documentTypeId;
                    });
                    if (!!employeeDocument) {
                        let isConfirmed = await swal({
                            title: `You already have a ${employeeDocument.documentType.title} document.`,
                            text: 'Would you want to replace it?',
                            icon: 'warning',
                            buttons: [
                                'No',
                                'Yes'
                            ],
                            closeOnClickOutside: false
                        });
                        console.log(isConfirmed);
                        if (!isConfirmed) {
                            return this.resetForm();
                        }
                    }
                    const formData = new FormData();
                    Object.keys(this.employeeDocument).forEach((key) => {
                        if(!!this.employeeDocument[key]){
                            formData.append(key, this.employeeDocument[key]);
                        }
                    });
                    //console.log(this.employeeDocument);
                    console.log(formData);
                    this.isSending = true;
                    let response = await this.$store.dispatch('SAVE_EMPLOYEE_DOCUMENT', formData);
                    this.isSending = false;
                    await swal(response);
                    EventBus.$emit('EMPLOYEE_DOCUMENT_SAVED');
                    this.resetForm();
                } catch (error) {
                    this.isSending = false;
                    this.$refs.employeeDocumentForm.reset();
                    this.$refs.employeeDocumentFile.value = '';
                    let message = document.createElement('div');
                    //message.innerHTML = error.trim('"');
                    message.innerHTML = error;
                    await swal({content: message, icon: 'error'});
                }
            },
            resetForm() {
                this.employeeDocument = new EmployeeDocument();
                this.$refs.employeeDocumentForm.reset();
                this.$refs.employeeDocumentFile.value = '';
                this.isEditing = false;
                $('.modal-backdrop').remove();
            },
        }
    }
</script>

<style scoped>

</style>
