<template>
    <div class="payroll-table card">
        <div class="table-responsive">
            <table class="table table-hover table-radius">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Baseline</th>
                    <th>Measured As</th>
                    <th>Description</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="indicator in indicators" :key="indicator.id">
                    <th>{{indicator.name}}</th>
                    <th>{{indicator.baseline}}</th>
                    <th>
                        <span v-if="indicator.unit === 'percent'">Percentage (%)</span>
                        <span v-else>Count</span>
                    </th>
                    <td>{{$stringLimit(indicator.description)}}</td>
                    <td class="text-right">
                        <button class="btn btn-info btn-sm" title="Edit" @click="editOutcomeIndicator(indicator)"><i
                            class="fa fa-pencil m-r-5"></i></button>
                        <button class="btn btn-danger btn-sm" title="Delete" @click="deleteOutcomeIndicator(indicator)"><i
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
        props:{
            indicators: {
                type: Array,
                required: true
            }
        },
        methods: {
            editOutcomeIndicator(indicator = null){
                EventBus.$emit("EDIT_OUTCOME_INDICATOR",indicator);
            },
            deleteOutcomeIndicator(indicator){

            }
        },
    }
</script>

<style scoped>

</style>
