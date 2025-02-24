document.getElementById('menuLink').addEventListener('click', function (event) {
    event.preventDefault(); // Evita que el enlace navegue a otra página

    // Hace una solicitud a la API para obtener el contenido del menú
    fetch('/api/menu')
        .then(response => response.json())
        .then(data => {
            // Inserta el contenido en el cuerpo de la página
            document.body.innerHTML = data.content;
        })
        .catch(error => {
            console.error('Error al cargar el menú:', error);
        });
});

