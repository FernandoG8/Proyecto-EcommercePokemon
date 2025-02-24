document.addEventListener("DOMContentLoaded", function () {
    const municipiosMexico = [
        "Aguascalientes",
        "Mexicali",
        "La Paz",
        "Campeche",
        "Saltillo",
        "Colima",
        "Tuxtla Gutiérrez",
        "Chihuahua",
        "Ciudad de México",
        "Durango",
        "Guanajuato",
        "Chilpancingo",
        "Pachuca",
        "Guadalajara",
        "Toluca",
        "Morelia",
        "Cuernavaca",
        "Tepic",
        "Monterrey",
        "Oaxaca",
        "Puebla",
        "Querétaro",
        "Chetumal",
        "San Luis Potosí",
        "Culiacán",
        "Hermosillo",
        "Villahermosa",
        "Ciudad Victoria",
        "Tlaxcala",
        "Xalapa",
        "Mérida",
        "Zacatecas"
    ];

    // Cargar municipios en el select
    const municipioSelect = document.getElementById("municipio");
    municipiosMexico.forEach(municipio => {
        const option = document.createElement("option");
        option.value = municipio;
        option.textContent = municipio;
        municipioSelect.appendChild(option);
    });

    // Manejar el envío del formulario
    const paymentForm = document.getElementById("payment-form");
    paymentForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario

        // Obtener los datos del formulario
        const formData = {
            nombre: document.getElementById("nombre").value,
            apellido: document.getElementById("apellido").value,
            celular: document.getElementById("celular").value,
            sexo: document.getElementById("sexo").value,
            codigoPostal: document.getElementById("codigo-postal").value,
            municipio: document.getElementById("municipio").value,
            tarjeta: {
                numero: document.getElementById("numero-tarjeta").value,
                expiracion: document.getElementById("fecha-expiracion").value,
                cvv: document.getElementById("cvv").value
            }
        };

        // Guardar los datos en un archivo JSON (simulado)
        saveFormData(formData);
    });

    // Función para guardar los datos en JSON
    function saveFormData(formData) {
        const jsonData = JSON.stringify(formData, null, 2);
        console.log("Datos guardados:", jsonData);

        // Aquí puedes enviar los datos a un servidor o guardarlos en localStorage
        localStorage.setItem("paymentData", jsonData);

        // Redirigir a una página de confirmación (opcional)
        window.location.href = "confirmacion.html";
    }
});

