<template>
    <div class="payroll-table card">
        <div class="table-responsive">
            <table class="table table-hover table-radius">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Rank</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="intervention in interventions" :key="intervention.id">
                    <th>{{intervention.name}}</th>
                    <td>{{intervention.description}}</td>
                    <td>{{intervention.rank}}</td>
                    <td class="text-right">
                        <button class="btn btn-info btn-sm" title="Edit" @click="editIntervention(intervention)"><i
                            class="fa fa-pencil m-r-5"></i></button>
                        <button class="btn btn-danger btn-sm" title="Delete" @click="deleteIntervention(intervention)"><i
                            class="fa fa-trash-o m-r-5"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            interventions: {
                type: Array,
                required: true
            },
        },
        methods: {
            editIntervention(intervention = null){
                EventBus.$emit("EDIT_INTERVENTION",intervention);
            },
            deleteIntervention(intervention){

            }
        },
    }
</script>

<style scoped>

</style>
