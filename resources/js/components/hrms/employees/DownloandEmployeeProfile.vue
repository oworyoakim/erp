<template>
    <div v-if="!!employee" class="card">
        <div class="card-body" ref="printArea" id="printArea">
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="row">
                        <div class="col-4">
                            <img
                                class="rounded-circle"
                                :src="employee.avatar"
                                alt
                                style="border: solid 2px red;"
                            />
                        </div>
                        <div class="col-1"></div>
                        <div class="col-7  pl-5">
                            <h2 style="color: red !important;">
                                {{ employee.full_name }}
                            </h2>
                            <h3>{{ employee.designation.title }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 text-justified">
                    <app-profile-item-header
                        title="Profile Summary"
                        icon-class="fa fa-user"
                    >
                        <div class="title">
                            {{ employee.personalStatement }}
                        </div>
                    </app-profile-item-header>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-justified">
                    <app-profile-item-header
                        title="Personal Info"
                        icon-class="fa fa-info-circle"
                    >
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <span class="col-3">Full Name</span>
                                    <span class="col-1">:</span>
                                    <span class="col-8">{{
                                        employee.full_name
                                    }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-3">Employee ID</span>
                                    <span class="col-1">:</span>
                                    <span class="col-8">{{
                                        employee.employee_number
                                    }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-3">Birth Day</span>
                                    <span class="col-1">:</span>
                                    <span class="col-8">
                                        {{
                                            $moment(employee.dob).format(
                                                "MMM D YYYY"
                                            )
                                        }}
                                    </span>
                                </div>

                                <div class="row">
                                    <span class="col-3">Gender</span>
                                    <span class="col-1">:</span>
                                    <span class="col-8">
                                        {{ employee.gender }}
                                    </span>
                                </div>
                                <div class="row">
                                    <span class="col-3">Email</span>
                                    <span class="col-1">:</span>
                                    <span class="col-8">{{
                                        employee.email
                                    }}</span>
                                </div>
                                <div class="row">
                                    <span class="col-3">Phone</span>
                                    <span class="col-1">:</span>
                                    <span class="col-8">
                                        {{ employee.mobile }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <span class="col-3">Address</span>
                                    <span class="col-1">:</span>
                                    <span class="col-8">
                                        {{ employee.address }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </app-profile-item-header>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6 text-justified">
                    <app-profile-item-header
                        title="Work Experience"
                        icon-class="fa fa-briefcase"
                    >
                        <div
                            class="row title mt-1"
                            v-for="experience in experiences"
                            :key="experience.id"
                        >
                            <div class="col-6">
                                <h5>{{ experience.position }}</h5>
                            </div>
                            <div class="col-6">
                                <h5>
                                    [
                                    <span
                                    >{{ experience.start_month }}
                                        {{ experience.start_year }}</span
                                    >
                                    -
                                    <span
                                        v-if="
                                            !!experience.end_month &&
                                                !!experience.end_year
                                        "
                                    >{{ experience.end_month }}
                                        {{ experience.end_year }}</span
                                    >
                                    <span v-else>Present</span>]
                                </h5>
                            </div>
                            <h5 class="col-12 text-muted">
                                {{ experience.company }}
                            </h5>
                        </div>
                    </app-profile-item-header>
                </div>
                <div class="col-6 text-justified">
                    <app-profile-item-header
                        title="Education Background"
                        icon-class="fa fa-graduation-cap"
                    >
                        <div
                            class="row title mt-1"
                            v-for="education in educations"
                            :key="education.id"
                        >
                            <div class="col-6">
                                <h5>{{ education.qualification }}</h5>
                            </div>
                            <div class="col-6">
                                <h5>
                                    [
                                    <span
                                    >{{ education.start_month }}
                                        {{ education.start_year }}</span
                                    >
                                    -
                                    <span
                                        v-if="
                                            !!education.end_month &&
                                                !!education.end_year
                                        "
                                    >{{ education.end_month }}
                                        {{ education.end_year }}</span
                                    >
                                    <span v-else>Present</span>]
                                </h5>
                            </div>
                            <h5 class="col-12 text-muted">
                                {{ education.institution }}
                            </h5>
                        </div>
                    </app-profile-item-header>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-custom" @click="printProfile">
                Download
            </button>
        </div>
    </div>
</template>

<script>
    import Printd from "printd";
    import {mapGetters} from "vuex";
    import {baseUrl} from "../../../app";

    export default {
        props: {
            employee: {type: Object, default: () => null}
        },
        computed: {
            ...mapGetters({
                educations: "GET_EDUCATIONS",
                experiences: "GET_EXPERIENCES"
            })
        },
        methods: {
            printProfile() {
                let printer = new Printd();
                printer.print(
                    this.$refs.printArea,
                    [`${baseUrl}/css/app.css`],
                    [`${baseUrl}/js/app.js`]
                );

                // let mode = "iframe"; //popup
                // let close = mode == "popup";
                // let options = {
                //     mode: mode,
                //     popClose: close
                // };
                // $(this.$refs.printArea).printArea(options);
            }
        }
    };
</script>

<style scoped></style>
