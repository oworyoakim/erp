class LeaveSetting {
    constructor() {
        this.id = null;
        this.leaveTypeId = '';
        this.duration = '';
        this.withWeekend = false;
        this.earnedLeave = true;
        this.carryForward = false;
        this.maxCarryForwardDuration = 0;
        this.createdAt = null;
        this.updatedAt = null;
    }
}

export default LeaveSetting;
