function get(url) {
    fetch("proxy.php?url=" + url, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        },
    })
    .then(function(response) {
        if (response.status === 200) {
            return response.json();
        } else {
            throw new Error("Request failed");
        }
    })
    .then(function(data) {
        console.log(data);
    })
    .catch(function(error) {
        console.error("Error: " + error.message);
    });
}

function post(url, formData = null) {
    fetch("proxy.php?url=" + url, {
        method: "POST",
        body: formData
    })
    .then(function(response) {
        if (response.status === 200) {
            return response.json();
        } else {
            throw new Error("Request failed");
        }
    })
    .then(function(data) {
        console.log(data);
    })
    .catch(function(error) {
        console.error("Error: " + error.message);
    });
}
