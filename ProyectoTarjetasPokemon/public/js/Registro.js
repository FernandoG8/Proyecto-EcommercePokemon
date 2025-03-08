// Configuración base para la API
const API_BASE_URL = 'http://localhost:8000/api/v1'; // Ajusta esta URL a tu API

// Elementos del DOM
const alertMessage = document.getElementById('alertMessage');
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    const logoutButton = document.getElementById('logoutButton');
    const welcomeMessage = document.getElementById('welcomeMessage');
    const authButton = document.getElementById('authButton');
    const showLoginLink = document.getElementById('showLogin');
    const showRegisterLink = document.getElementById('showRegister');
    const registerDiv = document.getElementById('registerDiv');
    const loginDiv = document.getElementById('loginDiv');

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
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
    };
    
    const token = localStorage.getItem('token');
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }
    
    try {
        const response = await fetch(url, {
            method,
            headers,
            body: data ? JSON.stringify(data) : null,
            credentials: 'include'
        });
        
        const responseData = await response.json();
        
        if (!response.ok) {
            if (response.status === 422) {
                const errorMessages = Object.values(responseData.errors).flat().join('\n');
                throw new Error(errorMessages);
            }
            throw new Error(responseData.message || 'Ha ocurrido un error');
        }
        
        return responseData;
    } catch (error) {
        console.error('Error en la solicitud API:', error);
        throw error;
    }
}

// Función para registrar un nuevo usuario
async function registerUserCustomer(name, email, password, password_confirmation, phone) {
    try {
        const data = await apiRequest('/register', 'POST', {
            name,
            email,
            password,
            password_confirmation,
            phone: phone || '',
            role: 'customer'
        });
        
        if (data.token) {
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));
        }
        
        showAlert('Usuario registrado correctamente', 'success');
        
        // Cerrar el modal y recargar la página
        const authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
        if (authModal) {
            authModal.hide();
        }
        
        // Pequeño delay para que se vea el mensaje de éxito
        setTimeout(() => {
            window.location.reload();
        }, 1000);
        
        return data;
    } catch (error) {
        showAlert(`Error al registrar: ${error.message}`);
        throw error;
    }
}


async function registerUserAdmin(name, email, password, password_confirmation) {
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
        const token = localStorage.getItem('token');
        if (!token) {
            throw new Error('No hay sesión activa');
        }

        const response = await fetch(`${API_BASE_URL}/logout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            credentials: 'include'
        });

        if (!response.ok) {
            throw new Error('Error al cerrar sesión');
        }

        localStorage.clear();
        showAlert('Sesión cerrada correctamente', 'success');
        
        // Recargar después de un pequeño delay
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (error) {
        console.error('Error al cerrar sesión:', error);
        showAlert(`Error al cerrar sesión: ${error.message}`);
        localStorage.clear();
        
        // Recargar incluso si hay error
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
}

// Función para iniciar sesión
async function loginUser(email, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        if (!response.ok) {
            throw new Error('Error en la autenticación');
        }

        // Usar la nueva función handleLogin
        await handleLogin(response);
        
        showAlert('Inicio de sesión exitoso', 'success');
        
        // Cerrar el modal y recargar la página
        const authModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
        if (authModal) {
            authModal.hide();
        }
        
        setTimeout(() => {
            window.location.reload();
        }, 1000);

    } catch (error) {
        showAlert(`Error al iniciar sesión: ${error.message}`);
        throw error;
    }
}


async function handleLogin(response) {
    const data = await response.json();
    if (data.token) {
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));
        updateUserInterface(); // Actualizar la UI después de guardar el token
    } else {
        throw new Error('No se recibió token de autenticación');
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
    
    console.log('Token:', token);
    console.log('UserData:', userData);
    
    if (token && userData) {
        const user = JSON.parse(userData);
        console.log('Usuario autenticado:', user);
        if (welcomeMessage) welcomeMessage.textContent = `¡Bienvenido, ${user.name}!`;
        if (welcomeMessage) welcomeMessage.style.display = 'inline';
        if (authButton) authButton.style.display = 'none';
        if (logoutButton) logoutButton.style.display = 'inline';
    } else {
        console.log('No hay sesión activa');
        if (welcomeMessage) welcomeMessage.style.display = 'none';
        if (authButton) authButton.style.display = 'inline';
        if (logoutButton) logoutButton.style.display = 'none';
    }
}

// Toggle entre formularios
if (showLoginLink) {
    showLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        registerDiv.style.display = 'none';
        loginDiv.style.display = 'block';
    });
}

if (showRegisterLink) {
    showRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        loginDiv.style.display = 'none';
        registerDiv.style.display = 'block';
    });
}

if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const name = document.getElementById('registerName').value;
        const email = document.getElementById('registerEmail').value;
        const phone = document.getElementById('registerPhone').value;
        const password = document.getElementById('registerPassword').value;
        const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
        
        if (password !== passwordConfirm) {
            showAlert('Las contraseñas no coinciden');
            return;
        }
        
        try {
            await registerUserCustomer(name, email, password, passwordConfirm);
            registerForm.reset();
            // Mostrar el formulario de login después del registro exitoso
            if (loginDiv && registerDiv) {
                registerDiv.style.display = 'none';
                loginDiv.style.display = 'block';
            }
        } catch (error) {
            console.error('Error en el registro:', error);
        }
    });
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
        const phone = document.getElementById('registerPhone').value;
        const password = document.getElementById('registerPassword').value;
        const passwordConfirm = document.getElementById('registerPasswordConfirm').value;
        
        if (password !== passwordConfirm) {
            showAlert('Las contraseñas no coinciden');
            return;
        }
        
        try {
            await registerUserAdmin(name, email, password, passwordConfirm);
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