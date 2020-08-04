<template>
    <app-main-modal :title="title" :is-open="isEditing" @modal-closed="resetForm()">
        <form @submit.prevent="saveKeyResultArea">
            <div class="form-group row">
                <label class="col-sm-4">
                    Name
                    <span class="text-danger">*</span>
                </label>
                <div class="col-sm-8">
                    <input type="text" v-model="keyResultArea.name" class="form-control"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
<!--                    <textarea v-model="keyResultArea.description" class="form-control"></textarea>-->
                    <TinymceEditor
                        :api-key="$store.getters.TINYMCE_API_KEY"
                        :init="$store.getters.EDITOR_CONFIG"
                        v-model="keyResultArea.description"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Rank</label>
                <div class="col-sm-8">
                    <select v-model="keyResultArea.rank" class="form-control">
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
    import KeyResultArea from "../../../models/smps/KeyResultArea";
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
            EventBus.$on('EDIT_KEY_RESULT_AREA', this.editKeyResultArea);
        },
        computed: {
            title() {
                return (!!this.keyResultArea.id) ? "Edit Key Result Area" : "Add Key Result Area";
            },
            formInvalid(){
                return this.isSending || !(!!this.keyResultArea.name && !!this.keyResultArea.rank);
            }
        },

        data() {
            return {
                keyResultArea: new KeyResultArea(),
                isEditing: false,
                isSending: false,
            }
        },
        methods: {
            editKeyResultArea(keyResultArea = null) {
                if (keyResultArea) {
                    this.keyResultArea = deepClone(keyResultArea);
                } else {
                    this.keyResultArea = new KeyResultArea();
                }
                this.isEditing = true;
            },
            async saveKeyResultArea() {
                try {
                    this.isSending = true;
                    if (!!!this.keyResultArea.planId) {
                        this.keyResultArea.planId = this.planId;
                    }
                    let response = await this.$store.dispatch('SAVE_KEY_RESULT_AREA', this.keyResultArea);
                    toastr.success(response);
                    this.isSending = false;
                    EventBus.$emit('KEY_RESULT_AREA_SAVED');
                    this.resetForm();
                } catch (error) {
                    let message = document.createElement('div');
                    message.innerHTML = error.trim('"');
                    await swal({content: message, icon: 'error'});
                    this.isSending = false;
                }
            },
            resetForm() {
                this.keyResultArea = new KeyResultArea();
                this.isEditing = false;
                $('.modal-backdrop').remove();
            }
        }
    }
</script>

<style scoped>

</style>
