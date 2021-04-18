class LeavePolicy {
    constructor() {
        this.id = null;
        this.leaveTypeId = '';
        this.gender = null;
        this.title = '';
        this.description = '';
        this.duration = '';
        this.withWeekend = '';
        this.carryForward = false;
        this.active = false;
        this.maxCarryForwardDuration = '';
        this.leaveType = null;
        this.salaryScaleIds = [];
        this.salaryScales = [];
    }
}

export default LeavePolicy;
