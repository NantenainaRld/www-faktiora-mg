//asset
function asset(path){
    return BASE_URL  + path;
}
//api
function apiUrl(api){
    return SITE_URL + api;
}
//api request
async function apiRequest(url, options ={}){
    try{
        const response = await fetch(url,{
            method: options.method ?? 'GET',
            headers: {'Content-Type': 'application/json'},
            body: options.body ? JSON.stringify(options.body) : null
        });
        if(!response.ok) throw new Error(`HTTP ${response.status}`);
        //return data from api
        return await response.json();
    }
    catch(error){
        throw error;
    }
}
