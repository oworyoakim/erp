class Errors {
    constructor(data) {
        for (let field in data) {
            this[field] = data[field];
        }
    }

    /**
     *
     * @param field {String}
     * @param value {String}
     */
    set(field, value) {
        this[field] = value;
    }

    /**
     *
     * @param field {string}
     * @returns {null|string}
     */
    get(field) {
        return this[field] || null;
    }

    clear(field = null) {
        if (field) {
            this[field] = null;
            return;
        }
        for (let field in this) {
            this[field] = null;
        }
    }

    has(field) {
        return !!this[field];
    }

    any() {
        return Object.keys(this).length > 0;
    }

    validEmail(email){
        let regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        return regex.test(email);
    }
}

export default Errors;
