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
                <tr v-for="output in outputs" :key="output.id">
                    <th>{{output.name}}</th>
                    <td>{{$stringLimit(output.description)}}</td>
                    <td class="text-right">
                        <button class="btn btn-info btn-sm" title="Edit" @click="editOutput(output)"><i
                            class="fa fa-pencil m-r-5"></i></button>
                        <button class="btn btn-danger btn-sm" title="Delete" @click="deleteOutput(output)"><i
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
            objectiveId: Number,
            outputs: {
                type: Array,
                required: true
            },
        },
        methods: {
            editOutput(output = null){
                EventBus.$emit("EDIT_OUTPUT",output);
            },
            deleteOutput(output){

            }
        },
    }
</script>

<style scoped>

</style>
