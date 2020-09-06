export default class Activity {
    constructor() {
        this.id = null;
        this.title = '';
        this.description = '';
        this.startDate = '';
        this.dueDate = '';
        this.endDate = '';
        this.completion = '';
        this.status = '';
        this.quarter = null;
        this.workPlanId = '';
        this.workPlan = null;
        this.interventionId = '';
        this.departmentId = '';
        this.teamLeaderId = '';
        this.intervention = null;
        this.stages = [];
        this.tasks = [];
        this.outputs = [];
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = '';
        this.updatedAt = '';
    }
}
