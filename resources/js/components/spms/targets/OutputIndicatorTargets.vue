<template>
    <div class="payroll-table card">
        <div class="table-responsive">
            <table class="table table-hover table-radius">
                <thead>
                <tr>
                    <th>Indicator</th>
                    <th>Report Period</th>
                    <th>Target</th>
                    <th>Due On</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="target in targets" :key="target.id">
                    <td>
                        <span v-if="target.outputIndicator">{{target.outputIndicator.name}}</span>
                    </td>
                    <td>
                        <span v-if="target.reportPeriod">{{target.reportPeriod.name}}</span>
                    </td>
                    <td>
                        <span v-if="target.outputIndicator && target.outputIndicator.unit === 'percent'">{{target.target}} %</span>
                        <span v-else>{{target.target}}</span>
                    </td>
                    <td>{{$moment(target.dueDate).format('DD MMM YYYY')}}</td>
                    <td class="text-right">
                        <button class="btn btn-info btn-sm" title="Edit" @click="editOutputIndicatorTarget(target)"><i
                            class="fa fa-pencil m-r-5"></i></button>
                        <button class="btn btn-danger btn-sm" title="Delete" @click="deleteOutputIndicatorTarget(target)"><i
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
            targets: {
                type: Array,
                required: true
            }
        },
        methods: {
            editOutputIndicatorTarget(target = null){
                EventBus.$emit("EDIT_OUTPUT_INDICATOR_TARGET",target);
            },
            deleteOutputIndicatorTarget(target){

            }
        },
    }
</script>

<style scoped>

</style>
