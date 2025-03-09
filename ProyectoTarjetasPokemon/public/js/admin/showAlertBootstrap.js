function showAlert(message, type = 'danger') {
    let alertContainer = document.getElementById('alert-container');

    // If the alert container doesn't exist, create it
    if (!alertContainer) {
        alertContainer = document.createElement('div');
        alertContainer.id = 'alert-container';
        alertContainer.style.position = 'fixed';
        alertContainer.style.top = '10px';
        alertContainer.style.right = '10px';
        alertContainer.style.zIndex = '9999';
        document.body.appendChild(alertContainer);
    }

    // Create the alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.role = 'alert';
    alert.innerText = message;

    // Append the alert to the container
    alertContainer.appendChild(alert);

    // Remove the alert after a few seconds
    setTimeout(() => {
        alert.remove();
    }, 3000);
}