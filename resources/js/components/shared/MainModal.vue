<template>
    <div :id="`mainModal-` + Math.random()" ref="mainModal" class="modal custom-modal fade" role="dialog"
         tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" v-bind:class="'modal-' + (size || 'lg')" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{title}}
                    </h5>
                    <button type="button"
                            class="close"
                            @click="closeModal()"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            title: {
                type: String,
                required: true
            },
            isOpen: {
                type: Boolean,
                required: true
            },
            size: {
                type: String,
                default: 'lg'
            },
        },
        watch: {
            isOpen(newVal, oldVal) {
                if (!!newVal) {
                    $(this.$refs.mainModal).modal('show');
                } else {
                    this.closeModal();
                }
            }
        },
        methods: {
            closeModal() {
                $(this.$refs.mainModal).modal('hide');
                $(".modal-backdrop").remove();
                this.$emit('modal-closed');
            }
        }
    }
</script>

<style scoped>

</style>
