import LeaveSetting from "./LeaveSetting";

class LeaveType {
    contructor() {
        this.id = null;
        this.title = '';
        this.description = '';
        this.active = null;
        this.earnedLeave = true;
        this.setting = new LeaveSetting();
        this.numOngoing = 0;
        this.createdAt = null;
        this.updatedAt = null;
    }
}

export default LeaveType;
