export default class Plan {
    constructor() {
        this.id = null;
        this.name = '';
        this.theme = '';
        this.mission = '';
        this.vision = '';
        this.values = '';
        this.frequency = 'quarterly';
        this.startDate = '';
        this.endDate = '';
        this.financialYears = [];
        this.reportPeriods = [];
        this.previousReportPeriod = null;
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = '';
        this.updatedAt = '';
    }
}
