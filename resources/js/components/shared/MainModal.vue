<template>
    <div id="mainModal" ref="mainModal" class="modal custom-modal fade" role="dialog"
         tabindex="-1"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{title}}
                    </h5>
                    <button type="button" class="close" @click="closePreview()" data-dismiss="modal" aria-label="Close">
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
            title: String,
        },
        methods: {
            closePreview() {
                $(this.$refs.mainModal).modal('hide');
                $(".modal-backdrop").remove();
                this.$emit('modal-closed');
            }
        }
    }
</script>

<style scoped>

</style>
