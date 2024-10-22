const app = Vue.createApp({
    data() {
        return {
            usuario: '',
            clave: '',
            timeoutID: null,
            tiempoInactividad: 30 * 60 * 1000 // 30 minutos en milisegundos
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
                    this.iniciarTemporizadorInactividad(); // Iniciar temporizador de inactividad
                    window.location.href = 'index.php'; // Redireccionar al dashboard
                } else {
                    Swal.fire({
                        text: response.data.message || "Datos incorrectos",
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    text: "Error al iniciar sesión",
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        },
        iniciarTemporizadorInactividad() {
            this.resetearTemporizador(); // Resetea el temporizador cuando haya actividad
            window.addEventListener('mousemove', this.resetearTemporizador);
            window.addEventListener('keydown', this.resetearTemporizador);
        },
        resetearTemporizador() {
            clearTimeout(this.timeoutID);
            this.timeoutID = setTimeout(this.cerrarSesionPorInactividad, this.tiempoInactividad);
        },
        cerrarSesionPorInactividad() {
            Swal.fire({
                text: "Sesión cerrada por inactividad",
                icon: 'info',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                // Petición para cerrar sesión en el servidor
                axios.post('logout.php').then(() => {
                    window.location.href = 'login.php'; // Redirigir al login
                });
            });
        }
    },
    mounted() {
        if (window.location.href.includes('index.php')) {
            this.iniciarTemporizadorInactividad(); // Iniciar temporizador en el dashboard
        }
    }
});

app.mount('#app');