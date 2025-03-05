// Configuración base para la API
const API_BASE_URL = 'http://localhost:8000/api/v1'; // Ajusta esta URL a tu API

// Elementos del DOM
const alertMessage = document.getElementById('alertMessage');
const registerForm = document.getElementById('registerForm');
const loginForm = document.getElementById('loginForm');
const userInfo = document.getElementById('userInfo');
const userName = document.getElementById('userName');
const userEmail = document.getElementById('userEmail');
const userRole = document.getElementById('userRole');
const logoutButton = document.getElementById('logoutButton');

// Función para mostrar mensajes de alerta
function showAlert(message, type = 'danger') {
    alertMessage.textContent = message;
    alertMessage.className = `alert alert-${type}`;
    alertMessage.classList.remove('d-none');
    
    // Ocultar la alerta después de 5 segundos
    setTimeout(() => {
        alertMessage.classList.add('d-none');
    }, 5000);
}

// Función para realizar solicitudes a la API
async function apiRequest(endpoint, method = 'GET', data = null) {
    const url = `${API_BASE_URL}${endpoint}`;
    
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };
    
    // Agregar token de autenticación si existe
    const token = localStorage.getItem('token');
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }
    
    const options = {
        method,
        headers,
        credentials: 'include' // Esto envía cookies con la solicitud
    };
    
    if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
        options.body = JSON.stringify(data);
    }
    
    try {
        const response = await fetch(url, options);
        
        // Si la respuesta no es JSON, maneja el error
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta no es JSON válido');
        }
        
        const responseData = await response.json();
        
        if (!response.ok) {
            throw new Error(responseData.message || 'Ha ocurrido un error');
        }
        
        return responseData;
    } catch (error) {
        console.error('Error en la solicitud API:', error);
        throw error;
    }
}

// Función para registrar un nuevo usuario
async function registerUser(name, email, password, password_confirmation) {
   const role = 'admin';
    try {
        const data = await apiRequest('/register', 'POST', {
            name,
            email,
            password,
            password_confirmation,
            role
        
        });
        
        showAlert('Usuario registrado correctamente. Ahora puedes iniciar sesión.', 'success');
        return data;
    } catch (error) {
        showAlert(`Error al registrar: ${error.message}`);
        throw error;
    }
}

async function logoutUser() {
    try {
        await apiRequest('/logout', 'POST');
        
        // Limpiar datos de sesión
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        
        showAlert('Sesión cerrada correctamente', 'success');
        updateUserInterface();
    } catch (error) {
        showAlert(`Error al cerrar sesión: ${error.message}`);
    }
}

// Función para iniciar sesión
async function loginUser(email, password) {
    try {
        const data = await apiRequest('/login', 'POST', {
            email,
            password
        });
        
        // Guardar el token en localStorage'
        console.log(data.token);
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));
        
        showAlert('Inicio de sesión exitoso', 'success');
        updateUserInterface();
        return data;
    } catch (error) {
        showAlert(`Error al iniciar sesión: ${error.message}`);
        throw error;
    }
}


// Función para obtener información del usuario actual
async function getCurrentUser() {
    try {
        return await apiRequest('/user');
    } catch (error) {
        console.error('Error al obtener información del usuario:', error);
        return null;
    }
}

// Función para actualizar la interfaz según el estado de autenticación
function updateUserInterface() {
    const token = localStorage.getItem('token');
    const userData = localStorage.getItem('user');
    
    if (token && userData) {
        // Usuario autenticado
        const user = JSON.parse(userData);
        
        // Mostrar información del usuario
        userName.textContent = user.name;
        userEmail.textContent = user.email;
        userRole.textContent = user.role || 'Cliente';
        
        // Mostrar sección de usuario y ocultar formularios
        userInfo.classList.remove('d-none');
        registerForm.parentElement.parentElement.classList.add('d-none');
        loginForm.parentElement.parentElement.classList.add('d-none');
    } else {
        // Usuario no autenticado
        userInfo.classList.add('d-none');
        registerForm.parentElement.parentElement.classList.remove('d-none');
        loginForm.parentElement.parentElement.classList.remove('d-none');
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Verificar si el usuario ya está autenticado
    updateUserInterface();
    
    // Manejar envío del formulario de registro
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
        
        if (password !== passwordConfirm) {
            showAlert('Las contraseñas no coinciden');
            return;
        }
        
        try {
            await registerUser(name, email, password, passwordConfirm);
            registerForm.reset();
        } catch (error) {
            console.error('Error en el registro:', error);
        }
    });
    
    // Manejar envío del formulario de inicio de sesión
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        
        try {
            await loginUser(email, password);
            loginForm.reset();
        } catch (error) {
            console.error('Error en el inicio de sesión:', error);
        }
    });
    
    // Manejar cierre de sesión
    logoutButton.addEventListener('click', async () => {
        try {
            await logoutUser();
        } catch (error) {
            console.error('Error al cerrar sesión:', error);
        }
    });
});