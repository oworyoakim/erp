class LeaveSetting {
    constructor() {
        this.id = null;
        this.leave_type_id = '';
        this.duration = '';
        this.with_weekend = false;
        this.earned_leave = true;
        this.carry_forward = false;
        this.max_carry_forward_duration = 0;
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export default LeaveSetting;
