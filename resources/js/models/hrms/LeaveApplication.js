import moment from 'moment';
class LeaveApplication {
    constructor() {
        this.id = null;
        this.employeeId = '';
        this.leaveTypeId = '';
        this.status = '';
        this.startDate = moment().add(1,'days').format('YYYY-MM-DD');
        this.duration = '';
        this.userId = '';
        this.comment = '';
        this.createdAt = null;
        this.updatedAt = null;
    }
}

export default LeaveApplication;
