import moment from 'moment';
class LeaveApplication {
    constructor() {
        this.id = null;
        this.employee_id = '';
        this.leave_type_id = '';
        this.status = '';
        this.start_date = moment().add(1,'days').format('DD MMM YYYY');
        this.duration = '';
        this.user_id = '';
        this.comment = '';
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default LeaveApplication;
