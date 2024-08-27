const app = Vue.createApp({
    data() {
        return {
            pacientes: [],
            paciente: {
                id: null,
                nombre: '',
                apellido: '',
                dni: '',
                fecha_nacimiento: '',
                telefono: '',
                direccion: ''
            },
            editando: false
        };
    },
    methods: {
        obtenerPacientes() {
            axios.get('api/pacientes.php', { params: { action: 'read' } })
                .then(response => {
                    this.pacientes = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener pacientes:', error);
                });
        },
        guardarPaciente() {
            const action = this.editando ? 'update' : 'create';
            const datos = { ...this.paciente, action };
            console.log(datos); // Verifica en la consola que 'action' esté presente
            axios.post('api/pacientes.php', datos)
                .then(response => {
                    Swal.fire(response.data.message);
                    if (response.data.success) {
                        this.obtenerPacientes();
                        this.resetFormulario();
                    }
                })
                .catch(error => {
                    console.error('Error al guardar paciente:', error);
                });
        },
        
        

        editarPaciente(paciente) {
            this.paciente = { ...paciente };
            this.editando = true;
        },
        eliminarPaciente(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esta acción.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.post('api/pacientes.php', { id, action: 'delete' })
                        .then(response => {
                            Swal.fire(response.data.message);
                            if (response.data.success) {
                                this.obtenerPacientes();
                            }
                        })
                        .catch(error => {
                            console.error('Error al eliminar paciente:', error);
                        });
                }
            });
        },

        resetFormulario() {
            this.paciente = {
                id: null,
                nombre: '',
                apellido: '',
                dni: '',
                fecha_nacimiento: '',
                telefono: '',
                direccion: ''
            };
            this.editando = false;
        }
    },
    mounted() {
        this.obtenerPacientes();
    }
});

app.mount('#app');
