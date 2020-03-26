class Designation {
    constructor(data = {}) {
        this.id = null;
        this.title = '';
        this.description = '';
        this.summary = '';
        this.max_holders = 1;
        this.probational = false;
        this.probation_period = 6;
        this.supervisor_id = '';
        this.company_id = '';
        this.directorate_id = data.directorate_id || '';
        this.department_id = data.department_id || '';
        this.division_id = data.division_id || '';
        this.section_id = data.section_id || '';
        this.scope = data.scope || '';
        this.salary_scale_id = '';
        this.active = null;
        this.leaveApplicationVerifier = null;
        this.leaveApplicationApprover = null;
        this.leaveApplicationGranter = null;
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default Designation;
