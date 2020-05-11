class Section {
    constructor(data = {}) {
        this.id = null;
        this.title = '';
        this.description = '';
        this.directorateId = data.directorateId || null;
        this.departmentId = data.departmentId || null;
        this.divisionId = data.divisionId || null;
        this.scope = data.scope || null;
        this.directorate = null;
        this.department = null;
        this.division = null;
        this.createdAt = null;
        this.updatedAt = null;
        this.createdBy = null;
        this.updatedBy = null;
    }
}

export default Section;
