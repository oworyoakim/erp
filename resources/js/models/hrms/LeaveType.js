import LeaveSetting from "../LeaveSetting";

class LeaveType {
    contructor() {
        this.id = null;
        this.title = '';
        this.description = '';
        this.active = null;
        this.setting = new LeaveSetting();
        this.numOngoing = 0;
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default LeaveType;
