<template>
    <div class="payroll-table card">
        <div class="table-responsive">
            <table class="table table-hover table-radius">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Measured As</th>
                    <th>Description</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="indicator in indicators" :key="indicator.id">
                    <th>{{indicator.name}}</th>
                    <th>
                        <span v-if="indicator.unit === 'percent'">Percentage (%)</span>
                        <span v-else>Count</span>
                    </th>
                    <td>{{$stringLimit(indicator.description)}}</td>
                    <td class="text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary">Actions</button>
                            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="sr-only">Actions</span>
                            </button>
                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(72px, -118px, 0px);">
                                <a @click="editOutputIndicator(indicator)" class="dropdown-item" href="javascript:void(0)">
                                    <i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a @click="deleteOutputIndicator(indicator)" class="dropdown-item" href="javascript:void(0)">
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
        props:{
            indicators: {
                type: Array,
                required: true
            }
        },
        methods: {
            editOutputIndicator(indicator = null){

                EventBus.$emit("EDIT_OUTPUT_INDICATOR", indicator);
            },
            deleteOutputIndicator(indicator){

            }
        },
    }
</script>

<style scoped>

</style>
