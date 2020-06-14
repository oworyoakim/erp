<template>
    <span v-if="isLoading" class="fa fa-spinner fa-spin"></span>
    <div v-else class="faq-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <a class="collapsed" data-toggle="collapse" href="#strengths">Strengths</a>
                </h4>
            </div>
            <div id="strengths" class="card-collapse collapse">
                <div class="card-body">
                    <div class="mb-2" v-for="category in strengths">
                        <h4>{{category.name}}</h4>
                        <ul>
                            <li v-for="swot in category.swots" :key="swot.id">
                                {{swot.description}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <a class="collapsed" data-toggle="collapse" href="#weaknesses">Weaknesses</a>
                </h4>
            </div>
            <div id="weaknesses" class="card-collapse collapse">
                <div class="card-body">
                    <div class="mb-2" v-for="category in weaknesses">
                        <h4>{{category.name}}</h4>
                        <ul>
                            <li v-for="swot in category.swots" :key="swot.id">
                                {{swot.description}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <a class="collapsed" data-toggle="collapse" href="#opportunities">Opportunities</a>
                </h4>
            </div>
            <div id="opportunities" class="card-collapse collapse">
                <div class="card-body">
                    <div class="mb-2" v-for="category in opportunities">
                        <h4>{{category.name}}</h4>
                        <ul>
                            <li v-for="swot in category.swots" :key="swot.id">
                                {{swot.description}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <a class="collapsed" data-toggle="collapse" href="#threats">Threats</a>
                </h4>
            </div>
            <div id="threats" class="card-collapse collapse">
                <div class="card-body">
                    <div class="mb-2" v-for="category in threats">
                        <h4>{{category.name}}</h4>
                        <ul>
                            <li v-for="swot in category.swots" :key="swot.id">
                                {{swot.description}}
                            </li>
                        </ul>
                    </div>
                </div>
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
            strengths() {
                //return this.swots.filter(swot => swot.type === 'strengths');
                return this.swots.strengths;
            },
            weaknesses() {
                //return this.swots.filter(swot => swot.type === 'weaknesses');
                return this.swots.weaknesses;
            },
            opportunities() {
                //return this.swots.filter(swot => swot.type === 'opportunities');
                return this.swots.opportunities;
            },
            threats() {
                //return this.swots.filter(swot => swot.type === 'threats');
                return this.swots.threats;
            },
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
        }
    }
</script>

<style scoped>

</style>
