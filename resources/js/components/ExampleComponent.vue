<template>
<div>
    <div class="m-b-30 table-responsive">
        <ul class="nav nav-tabs nav-tabs-bottom">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab"
                   href="#tab_activities">Activities</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_stages">Stages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_tasks">Tasks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_outputs">Outputs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_indicators">Indicators</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_targets_and_achievements">Targets &
                    Achievements</a>
            </li>
        </ul>
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Activities Tab -->
            <div class="tab-pane show active" id="tab_activities">
                <!-- activities -->
                <Activities/>
                <!-- /activities -->
            </div>
            <!-- /Activities Tab -->
            <!-- Activity Stages Tab -->
            <div class="tab-pane" id="tab_stages">
                <!--  stages   -->
                <app-stages/>
                <!--/stages   -->
            </div>
            <!-- /Activity Stages Tab -->
            <!-- Stage Tasks Tab -->
            <div class="tab-pane" id="tab_tasks">
                <!--  tasks   -->
                <app-tasks/>
                <!--//tasks    -->
            </div>
            <!-- /Stage Tasks Tab -->
            <!-- Activity Outputs Tab -->
            <div class="tab-pane" id="tab_outputs">
                <!--  Activity Outputs   -->
                <ActivityOutputs/>
                <!--//Activity Outputs    -->
            </div>
            <!-- /Activity Outputs Tab -->
            <!-- Activity Output Indicators Tab -->
            <div class="tab-pane" id="tab_indicators">
                <!--  Activity Output Indicators   -->
                <ActivityOutputIndicators/>
                <!--//Activity Output Indicators    -->
            </div>
            <!-- /Activity Output Indicators Tab -->
            <!-- Activity Output Indicator Targets Tab -->
            <div class="tab-pane" id="tab_targets_and_achievements">
                <!--  Activity Output Indicator Targets  -->
                <ActivityOutputIndicatorTargetsAndAchievements/>
                <!--//Activity Output Indicator Targets    -->
            </div>
            <!-- /Activity Output Indicator Targets Tab -->
        </div>
    </div>

</div>
</template>

<script>
export default {

}
</script>

<style scoped>

</style>
