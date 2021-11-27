<template>
    <div class="outputs-and-targets">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <app-breadcrumb :list-items="breadcrumbItems"></app-breadcrumb>
                </div>
                <div class="col-auto float-right ml-auto">
                    <button v-if="$store.getters.HAS_ANY_ACCESS(['outputs-and-targets.create'])"  type="button" class="btn add-btn" @click="editOutputsAndTargets()"><i class="fa fa-plus"></i> New
                        Outputs and Targets
                    </button>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <app-spinner v-if="isLoading"/>
        <template v-else>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <h3>OUTPUTS AND TARGETS AGREED FOR THE GIVEN ASSESSMENT PERIOD</h3>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: ['title'],
    data(){
        return {
            breadcrumbItems: [
                {href: '#', text: "Performance", class: ''},
                {href: '#', text: this.title, class: 'active'},
            ],
            isLoading: false,
        }
    },
}
</script>

<style scoped>

</style>
