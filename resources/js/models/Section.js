class Section {
    constructor(data = {}) {
        this.id = null;
        this.title = '';
        this.description = '';
        this.company_id = '';
        this.directorate_id = data.directorate_id || '';
        this.department_id = data.department_id || '';
        this.division_id = data.division_id || '';
        this.scope = data.scope || '';
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default Section;
