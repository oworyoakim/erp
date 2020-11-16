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
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary">Actions</button>
                            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="sr-only">Actions</span>
                            </button>
                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(72px, -118px, 0px);">
                                <a @click="editOutput(output)" class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a @click="deleteOutput(output)" class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-trash-o m-r-5"></i> Delete
                                </a>
                                <div class="dropdown-divider"></div>
                            </div>
                        </div>
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
