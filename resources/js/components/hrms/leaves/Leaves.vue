<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin fa-5x"></span>
    <div v-else>
        <app-leave-statistics :leaveTypes="leaveTypes"></app-leave-statistics>
        <div class="row">
            <div class="col-md-12 table-responsive">
                <app-leaves-list :leaves="leaves"></app-leaves-list>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        computed: {
            ...mapGetters({
                leaveTypes: 'GET_LEAVE_TYPES',
                leaves: 'GET_LEAVES',
            }),
        },
        created() {
            this.getLeaves().then(()=>{
                this.getLeaveTypes();
            });
        },
        data() {
            return {
                isLoading: true,
                filters: {
                    employee_id: '',
                    leave_type_id: '',
                },
            };
        },
        methods: {
            async getLeaves() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_LEAVES',this.filters);
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                    this.isLoading = false;
                }
            },
            async getLeaveTypes() {
                try {
                    await this.$store.dispatch('GET_LEAVE_TYPES');
                } catch (error) {
                    console.log(error);
                    toastr.error(error);
                }
            }
        }
    }
</script>
