export default class Activity {
    constructor() {
        this.id = null;
        this.title = '';
        this.description = '';
        this.startDate = '';
        this.dueDate = '';
        this.endDate = '';
        this.workPlanId = '';
        this.workPlan = null;
        this.interventionId = '';
        this.intervention = null;
        this.stages = [];
        this.tasks = [];
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = '';
        this.updatedAt = '';
    }
}
