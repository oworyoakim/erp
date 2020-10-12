<template>
    <div class="card profile-box flex-fill">
        <div class="card-title">
            <h3>Next of Kin</h3>
        </div>
        <div class="card-body">
            <template v-if="nextOfKin">

            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        nextOfKin: {type: Object, default: () => null},
    }
}
</script>

<style scoped>

</style>
