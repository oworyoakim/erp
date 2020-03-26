<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <div class="row">
            <div class="col-md-12">
                <app-salary-scales-list :salary-scales="salaryScales"></app-salary-scales-list>
                <app-salary-scale-form></app-salary-scale-form>
            </div>
        </div>
    </div>
</template>

<script>
    import SalaryScale from "../../../models/hrms/SalaryScale";
    import {EventBus} from "../../../app";
    import {mapGetters} from "vuex";

    export default {
        created() {
            this.getSalaryScales();
            EventBus.$on('salaryScaleDeleted', this.getSalaryScales);
            EventBus.$on('salaryScaleSaved', this.getSalaryScales);
        },
        data() {
            return {
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                salaryScales: 'SALARY_SCALES',
            }),
        },
        methods: {
            async getSalaryScales() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_SALARY_SCALES');
                    this.isLoading = false;
                    setTimeout(()=>{$('.salary-scales-datatable').DataTable();},100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
