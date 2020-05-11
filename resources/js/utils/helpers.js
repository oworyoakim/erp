export function deepClone(object) {
    return JSON.parse(JSON.stringify(object));
}

export function prepareQueryParams(payload) {
    let params = [];
    for (let filter in payload) {
        if (!!payload[filter]) {
            params.push(`${filter}=${payload[filter]}`);
        }
    }
    let queryParams = '';
    if (params.length > 0) {
        queryParams = '?' + params.join('&');
    }
    return queryParams;
}

export function resolveError(error) {
    if(!!error.response){
        return error.response.data;
    }
    return error.message;
}
