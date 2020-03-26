<template>
    <div class="card profile-box flex-fill">
        <div class="card-body">
            <h3 class="card-title">Particulars <a @click="editProfileInfo" data-target="#profileModal"
                                                  data-toggle="modal" class="edit-icon"
                                                  href="#"><i class="fa fa-pencil"></i></a></h3>
            <div class="personal-info">
                <div class="row">
                    <div class="col-4 title">NIN.</div>
                    <div class="col-8 text">{{employee.nin}}</div>
                </div>
                <div class="row">
                    <div class="col-4 title">Passport No.</div>
                    <div class="col-8 text">{{employee.passport}}</div>
                </div>
                <div class="row">
                    <div class="col-4 title">Nationality</div>
                    <div class="col-8 text">{{employee.nationality}}</div>
                </div>
                <div class="row">
                    <div class="col-4 title">Religion</div>
                    <div  v-if="employee.religion" class="col-8 text">{{employee.religion.title}}</div>
                </div>
                <div class="row">
                    <div class="col-4 title">Marital status</div>
                    <div v-if="employee.marital_status" class="col-8 text">{{employee.marital_status.title}}</div>
                </div>
                <div class="row">
                    <div class="col-4 title">Gender</div>
                    <div class="col-8 text">{{employee.gender}}</div>
                </div>
                <div class="row">
                    <div class="col-4 title">No. of children</div>
                    <div class="col-8 text"><span>Male: </span>{{employee.number_of_sons}} <span>Female: </span>{{employee.number_of_daughters}} <span>Total: </span>{{employee.number_of_children}}</div>
                </div>
            </div>
            <app-employee-profile-form></app-employee-profile-form>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../../app";

    export default {
        props:{
            employee: Object,
        },
        created() {
        },
        data(){
            return {

            }
        },
        methods:{
            editProfileInfo() {
                EventBus.$emit('updateProfileInfo', this.employee);
            },
        }
    }
</script>

<style scoped>

</style>
