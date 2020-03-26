<template>
    <div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">

                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <span v-if="isLoading" class="fa fa-spinner fa-spin fa-3x"></span>
        <div v-else class="row">
            <div class="col-md-12">
                <form @submit.prevent="saveSettings" ref="generalSettingsForm" enctype="multipart/form-data">
                    <div class="form-group row" v-for="setting in settings">
                        <label class="col-md-3">{{setting.settingKey.split('_').join(' ').toUpperCase()}}</label>
                        <div class="col-md-9">
                            <input type="text" :name="'settings['+setting.settingKey +']'" :value="setting.settingValue"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        props: ['title'],
        created(){
            this.getGeneralSettings();
        },
        data() {
            return {
                activeLeaveType: {},
                leavePolicies: [],
                breadcrumbItems: [
                    {href: '#', text: this.title, class: 'active'},
                ],
                isLoading: false,
                isFetchingData: false,
                isSending: false,
            };
        },
        computed: {
            ...mapGetters({
                settings: 'getGeneralSettings'
            }),
        },
        methods: {
            async getGeneralSettings() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('getGeneralSettings');
                    this.isLoading = false;
                } catch (error) {
                    console.log(error);
                    await swal(error);
                    this.isLoading = false;
                }
            },
            async saveSettings() {
                let formData = $(this.$refs.generalSettingsForm).serializeArray().map((element)=>{
                    let item = element.name;
                });
                //let data = JSON.parse(formData);
                console.log(formData);
            }
        },
    }
</script>

<style scoped>

</style>
