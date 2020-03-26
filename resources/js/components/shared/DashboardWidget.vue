<template>
    <div class="card dash-widget">
        <div class="card-body">
            <span class="dash-widget-icon"><i :class="stat.icon"></i></span>
            <div class="dash-widget-info">
                <h3><a :href="stat.link">{{stat.count}}</a></h3>
                <span>{{stat.title}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            stat: Object,
        }
    }
</script>

<style scoped>

</style>
