const app = Vue.createApp({
    data() {
        return {
            usuarios: [],
            roles: [],
            form: {
                id: null,
                dni: '',
                apellido: '',
                nombre: '',
                usuario: '',
                clave: '',
                rol_id: '',
            },
            mostrarForm: false
        };
    },
    mounted() {
        this.obtenerUsuarios();
        this.obtenerRoles();  // Asegúrate de llamar a este método
        // chequeo_permiso('usuarios');
    },
    methods: {
        obtenerUsuarios() {
            axios.get('api/usuarios.php')
                .then(response => {
                    this.usuarios = response.data;
                })
                .catch(error => {
                    console.error("Error al obtener los usuarios:", error);
                });
        },
        obtenerRoles() {  // Este método no debería estar anidado dentro de otro
            axios.get('api/roles.php')
                .then(response => {
                    this.roles = response.data;
                })
                .catch(error => {
                    console.error("Error al obtener los roles:", error);
                });
        },
      
        guardarUsuario() {
            let endpoint = this.form.id ? `api/usuarios.php?id=${this.form.id}` : 'api/usuarios.php';
            let method = this.form.id ? 'put' : 'post';

            axios({
                method: method,
                url: endpoint,
                data: this.form,
                headers: { 'Content-Type': 'application/json' }
            })
                .then(() => {
                    this.obtenerUsuarios(); // Refrescar la lista de usuarios
                    this.mostrarForm = false; // Ocultar el formulario
                    Swal.fire('Éxito', 'Usuario guardado con éxito', 'success'); // Mostrar éxito
                })
                .catch(error => {
                    if (error.response && error.response.status === 409) {
                        // Si el servidor devuelve un conflicto (DNI o usuario duplicado)
                        Swal.fire('Error', error.response.data.error, 'error'); // Mostrar el mensaje de error
                    } else {
                        console.error("Error al guardar el usuario:", error.response.data || error); // Mostrar error
                        Swal.fire('Error', 'No se pudo guardar el usuario', 'error'); // Alerta de error genérica
                    }
                });
        },


        editarUsuario(usuario) {
            this.form = { ...usuario };
            this.form.rol_id = usuario.rol_id;
            this.mostrarForm = true;
        },
        eliminarUsuario(id) {
            axios.delete(`api/usuarios.php?id=${id}`)
                .then(() => {
                    this.obtenerUsuarios();
                    Swal.fire('Éxito', 'Usuario eliminado con éxito', 'success');
                })
                .catch(error => {
                    console.error("Error al eliminar el usuario:", error);
                    Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
                });
        },
        mostrarFormulario() {
            this.form = {
                id: null,
                dni: '',
                apellido: '',
                nombre: '',
                usuario: '',
                clave: '',
                rol_id: '',
                estado: 1
            };
            this.mostrarForm = true;
        }
    }
});

app.mount('#app');
