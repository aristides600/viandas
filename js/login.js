const app = Vue.createApp({
    data() {
        return {
            usuario: '',
            clave: ''
        };
    },
    methods: {
        login() {
            axios.post('api/login.php', {
                usuario: this.usuario,
                clave: this.clave
            })
                .then(response => {
                    if (response.data.success) {
                        // mostrarMensajeExito("Logeado exitosamente");
                        // Login exitoso, redireccionar a otra página

                        window.location.href = 'index.php';

                    } else {
                        mostrarMensajeError(response.data.message || "Datos incorrectos");
                    }
                })
                .catch(error => {
                    console.error('Error al iniciar sesión: ' + error);
                    // mostrarMensajeError("Error al iniciar sesión");
                });
        }
    }
});

app.mount('#app');
