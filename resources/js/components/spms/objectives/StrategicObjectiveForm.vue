<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveObjective">
            <div class="form-group row">
                <label class="col-sm-4">
                    Name
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <input type="text" v-model="objective.name" class="form-control"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <textarea v-model="objective.description" class="form-control"></textarea>
                    <TinymceEditor
                        :api-key="$store.getters.TINYMCE_API_KEY"
                        :init="$store.getters.EDITOR_CONFIG"
                        v-model="objective.description"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Rank</label>
                <div class="col-sm-8">
                    <select v-model="objective.rank" class="form-control">
                        <option value="">Select...</option>
                        <option v-for="i in 10" :key="i" :value="i">{{i}}</option>
                    </select>
                </div>
            </div>

            <div class="submit-section">
                <button :disabled="formInvalid" class="btn btn-info add-btn btn-block">
                    <span v-if="isSending" class="fa fa-spinner fa-spin"></span>
                    <span v-else>Save</span>
                </button>
            </div>
        </form>
    </app-main-modal>
</template>
<script>
    import Objective from "../../../models/smps/Objective";
    import {EventBus} from "../../../app";
    import {deepClone} from "../../../utils/helpers";

    export default {
        props: {
            planId: {
                type: Number,
                required: true
            }
        },
        components:{
            TinymceEditor: require('@tinymce/tinymce-vue').default,
        },
        created() {
            EventBus.$on('EDIT_OBJECTIVE', this.editObjective);
        },
        computed: {
            title() {
                return (!!this.objective.id) ? "Edit Strategic Objective" : "Add Strategic Objective";
            },
            formInvalid(){
                return this.isSending || !(!!this.objective.name && !!this.objective.rank);
            }
        },

        data() {
            return {
                objective: new Objective(),
                isEditing: false,
                isSending: false,
            }
        },
        methods: {
            editObjective(objective = null) {
                if (objective) {
                    this.objective = deepClone(objective);
                } else {
                    this.objective = new Objective();
                }
                this.isEditing = true;
            },
            async saveObjective() {
                try {
                    this.isSending = true;
                    if (!!!this.objective.planId) {
                        this.objective.planId = this.planId;
                    }
                    let response = await this.$store.dispatch('SAVE_OBJECTIVE', this.objective);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('OBJECTIVE_SAVED');
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.objective = new Objective();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
