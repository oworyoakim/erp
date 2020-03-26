<template>
    <div class="card profile-box flex-fill">
        <div class="card-body text-lg">
            <h3 class="card-title">
                Leaves
                <button v-if="!!employee.canApplyForLeave" class="btn add-btn btn-sm" data-toggle="modal"
                        data-target="#leaveApplicationModal"><i class="fa fa-plus"></i> Request Leave</button>
            </h3>

        </div>
    </div>
</template>

<script>
    export default {
        props: {
            employee: Object,
        },
        created() {
        },
        data() {
            return {

            }
        },
        methods: {
        }
    }
</script>

<style scoped>
    .bottom-dashed {
        border-bottom: 2px dashed #ccc;
    }
</style>
