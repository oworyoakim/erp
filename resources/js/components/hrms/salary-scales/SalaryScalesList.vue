<template>
    <table class="table table-striped custom-table mb-0 salary-scales-datatable">
        <thead>
        <tr>
            <th style="width: 30px;">#</th>
            <th>Scale</th>
            <th>Rank</th>
            <th>Description</th>
            <th class="text-right">Action</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="salaryScale in salaryScales">
            <td>{{salaryScale.id}}</td>
            <td>{{salaryScale.scale}}</td>
            <td>{{salaryScale.rank}}</td>
            <td>{{salaryScale.description}}</td>
            <td class="text-right">
                <a @click="editSalaryScale(salaryScale)" title="Edit" class="btn btn-info btn-sm" href="javascript:void(0)"><i
                    class="fa fa-pencil m-r-5"></i></a>
                <a @click="deleteSalaryScale(salaryScale)" title="Delete" class="btn btn-danger btn-sm" href="javascript:void(0)"><i
                    class="fa fa-trash-o m-r-5"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props: {
            salaryScales: {type: Array, required: true},
        },
        methods: {
            editSalaryScale(salaryScale = null) {
                EventBus.$emit("EDIT_SALARY_SCALE", salaryScale);
            },
            async deleteSalaryScale(salaryScale) {
                try {
                    let isConfirm = await swal({
                        title: 'Are you sure?',
                        text: "You will delete this record!",
                        icon: 'warning',
                        buttons: [
                            'No',
                            'Yes'
                        ],
                        closeOnClickOutside: false
                    });
                    console.log(isConfirm);
                    if (isConfirm) {
                        //let response = await this.$store.dispatch('DELETE_SALARY_SCALE', salaryScale.id);
                        //toastr.success(response);
                        //EventBus.$emit('SALARY_SCALE_DELETED');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
