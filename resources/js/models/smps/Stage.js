export default class Stage {
    constructor() {
        this.id = null;
        this.title = '';
        this.description = '';
        this.startDate = '';
        this.dueDate = '';
        this.endDate = '';
        this.completion = '';
        this.status = '';
        this.workPlanId = '';
        this.workPlan = null;
        this.activityId = '';
        this.activity = null;
        this.tasks = [];
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = '';
        this.updatedAt = '';
    }
}
