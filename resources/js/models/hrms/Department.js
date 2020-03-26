class Department {
    constructor(data={}) {
        this.id = null;
        this.company_id = '';
        this.directorate_id = data.scope || '';
        this.scope = data.scope || '';
        this.title = '';
        this.description = '';
        this.active = null;
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default Department;
