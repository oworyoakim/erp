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
        if (error.response && error.response.status === 401) {
            console.error(error.response.data);
            if(location.pathname !== '/login'){
                location.reload();
            }
        }
        return error.response.data;
    }
    return error.message;
}

export function displayErrorMessage(message) {
    let messageElement = document.createElement('div');
    //messageElement.innerHTML = message.trim('"');
    messageElement.innerHTML = message;
    return swal({content: messageElement, icon: 'error'});
}
