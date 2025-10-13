function includeHTML(id, file) {
    fetch(file)
        .then(response => {
            if (!response.ok) throw new Error("Erreur de chargement : " + file);
            return response.text();
        })
        .then(data => {
            document.getElementById(id).innerHTML = data;
        })
        .catch(err => {
            console.error(err);
        });
}

includeHTML("header", "header.html");
includeHTML("footer", "footer.html");