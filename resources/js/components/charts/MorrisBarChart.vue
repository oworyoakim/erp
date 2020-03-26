<template>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">
                {{title}}
                <button @click="$emit('reload-data')" type="button" class="btn btn-sm btn-dark pull-right"><i class="fa fa-refresh"></i></button>
            </h3>
            <div ref="morrisBarChart"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title: String,
            config: {
                type: Object,
                required: true,
            },
        },
        mounted() {
            this.$nextTick(() => {
                Morris.Bar({
                    element: this.$refs.morrisBarChart,
                    ...this.config,
                });
            });
        },
    }
</script>

<style scoped>

</style>
