export default class Delegation {
    constructor() {
        this.id = null;
        this.delegatedId = '';
        this.delegated = null;
        this.delegatedDesignationId = '';
        this.delegatedDesignation = null;
        this.substantiveId = '';
        this.substantive = null;
        this.substantiveDesignationId = '';
        this.substantiveDesignation = null;
        this.effectiveFrom = 'now';
        this.reason = '';
        this.duration = 0;
        this.permissions = [];
        this.startDate = '';
        this.endDate = '';
        this.isActive = false;
    }
}
