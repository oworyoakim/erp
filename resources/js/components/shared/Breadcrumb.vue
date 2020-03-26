<template>
    <ul class="breadcrumb text-lg">
        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
        <li v-for="item in listItems" :class="'breadcrumb-item '+ item.class">
            <a v-if="item.href !== '#'" :href="item.href">{{item.text}}</a>
            <span v-else>{{item.text}}</span>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            listItems: Array,
        }
    }
</script>

<style scoped>

</style>
