async function get(url) {
    try {
      const response = await fetch("proxy.php?url=" + url, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      });
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error: " + error.message);
    }
}

async function post(url, formData = null) {
    try {
      const response = await fetch("proxy.php?url=" + url, {
        method: "POST",
        body: formData,
      });
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error: " + error.message);
    }
}

