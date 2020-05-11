class Department {
    constructor(data={}) {
        this.id = null;
        this.directorateId = data.directorateId || null;
        this.scope = data.scope || null;
        this.title = '';
        this.description = '';
        this.active = null;
        this.directorate = null;
        this.createdBy = null;
        this.updatedBy = null;
        this.createdAt = null;
        this.updatedAt = null;
    }
}

export default Department;
