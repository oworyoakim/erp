class Designation {
    constructor(data = {}) {
        this.id = null;
        this.title = '';
        this.description = '';
        this.summary = '';
        this.maxHolders = 1;
        this.probational = false;
        this.probationPeriod = 6;
        this.supervisorId = '';
        this.directorateId = data.directorateId || null;
        this.departmentId = data.departmentId || null;
        this.divisionId = data.divisionId || null;
        this.sectionId = data.sectionId || null;
        this.scope = data.scope || null;
        this.salaryScaleId = null;
        this.active = null;
        this.leaveApplicationVerifier = null;
        this.leaveApplicationApprover = null;
        this.leaveApplicationGranter = null;
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = null;
        this.updatedAt = null;
    }
}

export default Designation;
