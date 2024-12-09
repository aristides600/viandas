const app = Vue.createApp({
    data() {
        return {
            currentPassword: '',
            newPassword: '',
            confirmPassword: ''
        };
    },
    methods: {
        changePassword() {
            if (this.newPassword !== this.confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden.'
                });
                return;
            }

            fetch('api/cambiar_contrasena.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    currentPassword: this.currentPassword,
                    newPassword: this.newPassword
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: 'Contraseña cambiada con éxito.'
                        }).then(() => {
                            fetch('logout.php', { method: 'POST' })
                                .then(() => {
                                    window.location.href = 'login.php';
                                });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Error al cambiar la contraseña.'
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error. Inténtelo de nuevo.'
                    });
                });
        }

    }

});

app.mount('#app');
