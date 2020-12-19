const getPlugin = async (plugin) => {
    const formData = new FormData();
    formData.append("title", plugin.title);
    formData.append("path", plugin.downloadPath);

    await fetch('http://localhost:5000/admin/plugins/download', {
        method: 'post',
        body: formData
    });
    window.location = "/admin/plugins";
}

fetch("http://localhost:5001/api/plugin").then(response => response.json()).then((data) => {
    const pluginsElm = document.getElementById("plugins");
    data.forEach(plugin => {
        const div = document.createElement("div");

        const h3 = document.createElement("h3");
        h3.innerText = plugin.title;
        div.appendChild(h3);

        const a = document.createElement("a");
        a.href = "#";
        a.addEventListener("click", () => {
            getPlugin(plugin);
        })
        a.innerText = "Download";
        div.appendChild(a);

        pluginsElm.appendChild(div);
    })
})