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
                sexo_id: ''
            },
            sexos: [],  // Aquí se almacenarán los sexos
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
        obtenerSexos() {
            axios.get('api/sexos.php', { params: { action: 'read' } })
                .then(response => {
                    this.sexos = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener sexos:', error);
                });
        },
        obtenerNombreSexo(sexo_id) {
            const sexo = this.sexos.find(s => s.id === sexo_id);
            return sexo ? sexo.nombre : 'Desconocido';  // Devuelve el nombre del sexo o 'Desconocido' si no se encuentra
        },
        formatearFecha(fecha) {
            if (!fecha) return '';
            const [year, month, day] = fecha.split('-');
            return `${day}-${month}-${year}`;  // Formatea la fecha como dd-mm-yyyy
        },
       
        guardarPaciente() {
           
            axios.post('api/pacientes.php', datos)
                .then(response => {
                    Swal.fire(response.data.message);
                    
                    if (response.data.success) {
                        
                        this.obtenerPacientes();
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

       
    },
    mounted() {
        this.obtenerPacientes();
        this.obtenerSexos();
    }
});

app.mount('#app');
