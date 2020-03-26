<template>
    <select class="form-control select2" ref="selectBox" @change="handleChange" :multiple="!!multiple" :disabled="!!disabled">
        <option value="" v-text="defaultText"></option>
        <option v-for="option in options" :value="option.value" v-text="option.text"></option>
    </select>
</template>

<script>
    export default {
        props: ['options', 'value', 'defaultText','disabled','multiple'],
        mounted() {
            var vm = this;
            setTimeout(() => {
                $(this.$refs.selectBox)
                    .select2({
                        width: "100%",
                    })
                    .val(this.value)
                    .trigger('change')
                    // emit event on change.
                    .on('change', function () {
                        vm.$emit('input', this.value)
                    });
            }, 500);
        },
        methods:{
            handleChange(){
                this.$emit('handleChange');
            }
        },
    }
</script>

<style scoped>

</style>
