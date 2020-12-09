<template>
    <div class="modal-backdrop">
        <div :id="`staticMainModal` + Math.random()" ref="staticMainModal" class="modal custom-modal fade show" role="dialog"
             tabindex="-1" data-backdrop="static" data-keyboard="false" aria-modal="true" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" v-bind:class="'modal-' + (size || 'lg')" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{title}}</h5>
                        <button type="button"
                                class="close"
                                @click="$emit('modal-closed')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
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
        size: {
            type: String,
            default: 'lg'
        },
    },
}
</script>

<style scoped>

</style>
