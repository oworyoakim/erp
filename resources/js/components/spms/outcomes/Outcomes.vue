<template>
    <div class="payroll-table card">
        <div class="table-responsive">
            <table class="table table-hover table-radius">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="outcome in outcomes" :key="outcome.id">
                    <th>{{outcome.name}}</th>
                    <td>{{$stringLimit(outcome.description)}}</td>
                    <td class="text-right">
                        <button class="btn btn-info btn-sm" title="Edit" @click="editOutcome(outcome)"><i
                            class="fa fa-pencil m-r-5"></i></button>
                        <button class="btn btn-danger btn-sm" title="Delete" @click="deleteOutcome(outcome)"><i
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
            keyResultAreaId: Number,
            outcomes: {
                type: Array,
                required: true
            },
        },
        methods: {
            editOutcome(outcome = null){
                EventBus.$emit("EDIT_OUTCOME",outcome);
            },
            deleteOutcome(outcome){

            }
        },
    }
</script>

<style scoped>

</style>
