<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin"></span>
    <div v-else class="mt-2">
        <ul class="nav nav-tabs nav-tabs-bottom">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab_strengths">STRENGTHS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_weaknesses">WEAKNESSES</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_opportunities">OPPORTUNITIES</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_threats">THREATS</a>
            </li>
        </ul>
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Strengths Tab -->
            <div class="tab-pane show active" id="tab_strengths">
                <div class="card" v-if="swots.strengths">
                    <div class="card-header">
                        <button @click="editSwot(swots.strengths)" title="Edit" type="button" class="btn btn-sm btn-info pull-right">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                    <div class="card-body" v-html="swots.strengths.description"></div>
                </div>
                <template v-else>
                    <h4>No strengths entered!</h4>
                </template>
            </div>
            <!-- Weaknesses Tab -->
            <div class="tab-pane" id="tab_weaknesses">
                <div class="card" v-if="swots.weaknesses">
                    <div class="card-header">
                        <button @click="editSwot(swots.weaknesses)" title="Edit" type="button" class="btn btn-sm btn-info pull-right">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                    <div class="card-body" v-html="swots.weaknesses.description"></div>
                </div>
                <template v-else>
                    <h4>No weaknesses entered!</h4>
                </template>
            </div>
            <!-- Opportunities Tab -->
            <div class="tab-pane" id="tab_opportunities">
                <div class="card" v-if="swots.opportunities">
                    <div class="card-header">
                        <button @click="editSwot(swots.opportunities)" title="Edit" type="button" class="btn btn-sm btn-info pull-right">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                    <div class="card-body" v-html="swots.opportunities.description"></div>
                </div>
                <template v-else>
                    <h4>No opportunities entered!</h4>
                </template>
            </div>
            <!-- Threats Tab -->
            <div class="tab-pane" id="tab_threats">
                <div class="card" v-if="swots.threats">
                    <div class="card-header">
                        <button @click="editSwot(swots.threats)" title="Edit" type="button" class="btn btn-sm btn-info pull-right">
                            <i class="fa fa-edit"></i>
                        </button>
                    </div>
                    <div class="card-body" v-html="swots.threats.description"></div>
                </div>
                <template v-else>
                    <h4>No threats entered!</h4>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {EventBus} from "../../../app";

    export default {
        props: {
            planId: {
                type: Number,
                required: true
            }
        },
        computed: {
            ...mapGetters({
                swots: "SWOTS"
            }),
        },
        data() {
            return {
                isLoading: false,
            }
        },
        watch: {
            planId(newValue, oldValue) {
                this.$nextTick(() => {
                    this.getSwots();
                });
            }
        },
        created() {
            this.$nextTick(() => {
                this.getSwots();
            });
            EventBus.$on(['SWOT_SAVED'], this.getSwots);
        },
        methods: {
            async getSwots() {
                try {
                    this.isLoading = true;
                    await this.$store.dispatch('GET_SWOTS', this.planId);
                    this.isLoading = false;
                } catch (error) {
                    this.isLoading = false;
                    console.error(error);
                }
            },
            editSwot(swot = null) {
                EventBus.$emit("EDIT_SWOT", swot);
            },
        }
    }
</script>

<style scoped>

</style>
