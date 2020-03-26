class RelatedPerson {
    constructor(){
        this.id = null;
        this.employee_id = '';
        this.relationship_id = '';
        this.title = '';
        this.first_name = '';
        this.last_name = '';
        this.middle_name = '';
        this.dob = '';
        this.gender = '';
        this.nin = '';
        this.email = '';
        this.phone = '';
        this.emergency = false;
        this.dependant = false;
        this.is_next_of_kin = false;
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default RelatedPerson;
