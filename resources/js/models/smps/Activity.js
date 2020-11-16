export default class Activity {
    constructor() {
        this.id = null;
        this.title = '';
        this.code = '';
        this.cost = 0;
        this.description = '';
        this.startDate = '';
        this.dueDate = '';
        this.endDate = '';
        this.completion = '';
        this.status = '';
        this.quarter = null;
        this.workPlanId = '';
        this.workPlan = null;
        this.objectiveId = '';
        this.directorateId = '';
        this.departmentId = '';
        this.teamLeaderId = '';
        this.activityBlockId = '';
        this.activityBlock = null;
        this.objective = null;
        this.stages = [];
        this.tasks = [];
        this.outputs = [];
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = '';
        this.updatedAt = '';
    }
}
