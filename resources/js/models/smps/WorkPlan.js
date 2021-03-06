export default class WorkPlan {
    constructor() {
        this.id = null;
        this.title = '';
        this.description = '';
        this.theme = '';
        this.financialYear = '';
        this.planId = '';
        this.plan = null;
        this.status = '';
        this.startDate = null;
        this.endDate = null;
        this.planningDeadline = '';
        this.activities = [];
        this.stages = [];
        this.tasks = [];
        this.reportPeriods = [];
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = '';
        this.updatedAt = '';
    }
}
