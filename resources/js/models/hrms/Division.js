class Division {
    constructor(data = {}) {
        this.id = null;
        this.directorateId = data.directorateId || null;
        this.departmentId = data.departmentId || null;
        this.scope = data.scope || null;
        this.title = '';
        this.description = '';
        this.active = null;
        this.directorate = null;
        this.department = null;
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = null;
        this.updatedAt = null;
        this.deletedAt = null;
    }
}

export default Division;
