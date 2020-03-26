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
                <div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a @click="editSalaryScale(salaryScale)" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                        <a @click="deleteSalaryScale(salaryScale.id)" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import {EventBus} from "../../../app";
    export default {
        props: {
            salaryScales: Array,
        },
        methods: {
            editSalaryScale(salaryScale) {
                EventBus.$emit('editSalaryScale', salaryScale);
            },
            async deleteSalaryScale(id) {
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
                        let response = await this.$store.dispatch('DELETE_SALARY_SCALE', id);
                        toastr.success(response);
                        EventBus.$emit('salaryScaleDeleted');
                    }
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
