<template>
    <h2 class="table-avatar">
        <a :href="`/acl/users/profile/${username}`" class="avatar">
            <img alt="" :src="avatar">
        </a>
        <a :href="`/acl/users/profile/${username}`">
            <span class="employee-name">{{name}} <span v-if="!!position">{{position}}</span></span>
        </a>
    </h2>
</template>

<script>
    export default {
        props: {
            name: String,
            username: String,
            position: String | null,
            avatar: String,
        }
    }
</script>

<style scoped>
    .employee-name{
        font-size: 12pt !important;
    }
</style>
