const app = Vue.createApp({
    data() {
        return {
            pacientes: [], // Resultados de la búsqueda
            filtro: '', // Filtro de búsqueda (en tiempo real)
            cargando: false, // Indicador de carga mientras se realiza la búsqueda
        };
    },
    computed: {
        pacientesFiltrados() {
            if (!this.filtro) {
                return this.pacientes; // Si no hay filtro, mostrar todos los pacientes
            }
            // Filtrar pacientes por DNI, apellido o nombre
            return this.pacientes.filter(paciente => {
                // Asegúrate de que paciente.dni sea una cadena antes de usar includes
                const dni = paciente.dni ? String(paciente.dni) : ''; // Convertir a cadena si es necesario
                const apellido = paciente.apellido ? paciente.apellido.toUpperCase() : '';
                const nombre = paciente.nombre ? paciente.nombre.toUpperCase() : '';
    
                return dni.includes(this.filtro.toUpperCase()) ||
                    apellido.includes(this.filtro.toUpperCase()) ||
                    nombre.includes(this.filtro.toUpperCase());
            });
        }
    },
    
    watch: {
        filtro(newFiltro) {
            // Llamar a la API cada vez que cambie el filtro
            this.buscarPacientes(newFiltro);
        }
    },
    methods: {
        obtenerPacientes() {
            // Cargar todos los pacientes inicialmente
            axios.get('api/pacientes.php')
                .then(response => {
                    this.pacientes = response.data; // Guardar los pacientes en el arreglo
                })
                .catch(error => {
                    console.error('Error al obtener pacientes:', error);
                });
        },
        formatearFecha(fecha) {
            if (!fecha) return '';
            const [year, month, day] = fecha.split('-');
            return `${day}-${month}-${year}`;  // Formatea la fecha como dd-mm-yyyy
        },
        buscarPacientes(filtro) {
            // Evitar buscar si el filtro está vacío
            if (filtro.trim() === '') {
                this.pacientes = []; // Limpiar resultados si el filtro está vacío
                return;
            }

            this.cargando = true; // Mostrar indicador de carga
            axios.get('api/pacientes.php', {
                params: { buscar: filtro } // Enviar el filtro como parámetro
            })
                .then(response => {
                    this.pacientes = response.data; // Actualizar resultados
                })
                .catch(error => {
                    console.error('Error al buscar pacientes:', error);
                })
                .finally(() => {
                    this.cargando = false; // Ocultar indicador de carga
                });
        },
        editarPaciente(id) {
            window.location.href = `editar_paciente.php?id=${id}`;
        },
        eliminarPaciente(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: '¿Desea eliminar este paciente?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`api/pacientes.php?id=${id}`)
                        .then(response => {
                            Swal.fire('Éxito', response.data.message, 'success');
                            this.buscarPacientes(this.filtro); // Recargar resultados de búsqueda
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error',
                                error.response?.data?.message || 'Ocurrió un error',
                                'error'
                            );
                        });
                }
            });
        },
        nuevoPaciente() {
            window.location.href = 'nuevo_paciente.php';
        }
    },
    mounted() {
        this.obtenerPacientes(); // Cargar lista inicial de pacientes
    }
});

app.mount('#app');
