export default class Delegation {
    constructor() {
        this.id = null;
        this.delegatedId = null;
        this.delegated = null;
        this.delegatedDesignationId = null;
        this.delegatedDesignation = null;
        this.substantiveId = null;
        this.substantive = null;
        this.substantiveDesignationId = null;
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
