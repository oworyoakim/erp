<template>
    <!-- Leave Statistics -->
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6" v-for="stat in stats">
            <DashboardWidget :stat="stat"/>
        </div>
    </div>
    <!-- /Leave Statistics -->
</template>

<script>
    import DashboardWidget from "../../shared/DashboardWidget";
    export default {
        components: {DashboardWidget},
        props: {
            leaveTypes: Array
        },
        computed:{
          stats(){
              return this.leaveTypes.map((leaveType)=>{
                  return {
                      title: leaveType.title,
                      count: leaveType.numOngoing,
                      icon: 'fa fa-cubes',
                      link: "#"
                  };
              });
          },
        },
    }
</script>
