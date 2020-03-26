<template>
    <table class="table table-bordered">
        <thead class="thead-light">
        <tr>
            <th>Period</th>
            <th>Delegator</th>
            <th>Delegated</th>
            <th>Permissions</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="delegation in delegations" :key="delegation.id">
            <td>
                <span>{{delegation.startDate}}</span>
                -
                <span>{{delegation.endDate}}</span>
            </td>
            <td>{{delegation.substantive.name}}</td>
            <td>{{delegation.delegated.name}}</td>
            <td>{{delegation.permissions}}</td>
            <td>{{delegation.status}}</td>
            <td>
                <a href="javascript:void(0)" class="edit-icon" data-toggle="modal"
                   @click="editDelegation(delegation)"><i class="fa fa-pencil"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            delegations: Array,
        },
        methods: {
            editDelegation(delegation) {
                EventBus.$emit('editDelegation', delegation);
            },
        },
    }
</script>

<style scoped>

</style>
