<template>
    <div class="salary-scales">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button type="button" class="btn add-btn" @click="editSalaryScale()"><i class="fa fa-plus"></i> New Salary Scale</button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <div class="row">
                <div class="col-md-12">
                    <SalaryScalesList :salary-scales="salaryScales"/>
                    <SalaryScaleForm/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";
    import SalaryScalesList from "./SalaryScalesList";
    import SalaryScaleForm from "./SalaryScaleForm";

    export default {
        props: ['title'],
        components: {SalaryScalesList, SalaryScaleForm},
        created() {
            this.getSalaryScales();
            EventBus.$on(['SALARY_SCALE_SAVED', 'SALARY_SCALE_DELETED'], this.getSalaryScales);
        },
        data() {
            return {
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
            };
        },
        computed: {
            ...mapGetters({
                salaryScales: 'SALARY_SCALES',
            }),
        },
        methods: {
            editSalaryScale(salaryScale = null){
                EventBus.$emit("EDIT_SALARY_SCALE",salaryScale);
            },
            async getSalaryScales() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_SALARY_SCALES');
                    this.isLoading = false;
                    setTimeout(() => {
                        $('.salary-scales-datatable').DataTable();
                    }, 100);
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            },
        }
    }
</script>
