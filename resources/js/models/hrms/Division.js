class Division {
    constructor(data = {}) {
        this.id = null;
        this.company_id = '';
        this.directorate_id = data.directorate_id || '';
        this.department_id = data.department_id || '';
        this.scope = data.scope || '';
        this.title = '';
        this.description = '';
        this.active = null;
        this.directorate = null;
        this.department = null;
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default Division;
