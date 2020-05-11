class RelatedPerson {
    constructor(){
        this.id = null;
        this.employeeId = '';
        this.relationshipId = null;
        this.relationship = null;
        this.title = '';
        this.firstName = '';
        this.lastName = '';
        this.fullName = '';
        this.middleName = '';
        this.dob = '';
        this.gender = '';
        this.nin = '';
        this.email = '';
        this.phone = '';
        this.emergency = false;
        this.dependant = false;
        this.isNextOfKin = false;
        this.createdBy = null;
        this.createdAt = null;
        this.updatedBy = null;
        this.updatedAt = null;
    }
}

export default RelatedPerson;
