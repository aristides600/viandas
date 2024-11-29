const { createApp } = Vue;

createApp({
    data() {
        return {
            sectores: [],       // Arreglo para almacenar los sectores
            profesionales: [],  // Arreglo para almacenar los profesionales
            busqueda: '',       // Nuevo campo unificado para búsqueda
            pacientes: [],      // Inicializado como arreglo vacío
            pacienteSeleccionado: null,  // Inicializado como null
            sinCoincidencias: false,     // Inicializado como falso
            nuevaInternacion: {
                paciente_id: '',
                sector_id: '',
                diagnostico: ''
            },
            documento: {
                paciente_id: null
            }
        };
    },
    mounted() {
        // Cargar internaciones, sectores y profesionales al montar la aplicación
        this.cargarSectores();
    },
    methods: {
        buscarPacientes() {
            if (this.busqueda.length > 0) {
                axios.get('api/pacientes_apellido_dni.php', { params: { busqueda: this.busqueda } })
                    .then(response => {
                        this.pacientes = response.data;
                        this.sinCoincidencias = this.pacientes.length === 0;
                    })
                    .catch(error => {
                        console.error(error);
                        this.pacientes = [];
                        this.sinCoincidencias = true;
                    });
            } else {
                this.pacientes = [];
                this.sinCoincidencias = false;
            }
        },
        seleccionarPaciente(paciente) {
            this.documento.paciente_id = paciente.id;
            this.pacienteSeleccionado = paciente;
            this.pacientes = [];
        },
        limpiarPaciente() {
            this.documento.paciente_id = null;  // Reiniciar el ID del paciente
            this.pacienteSeleccionado = null;    // Limpiar la selección del paciente
            this.busqueda = '';                   // Limpiar el campo de búsqueda
            this.pacientes = [];                  // Reiniciar la lista de pacientes
            this.sinCoincidencias = false;        // Reiniciar la bandera de coincidencias
        },

        cargarSectores() {
            fetch('api/sectores.php', { method: 'GET' })
                .then(response => response.json())
                .then(data => { this.sectores = data; })
                .catch(error => console.error(error));
        },

        agregarInternacion() {
            const nuevaInternacionConPaciente = { ...this.nuevaInternacion, paciente_id: this.documento.paciente_id };
            axios.post('api/internados.php', nuevaInternacionConPaciente)
                .then(response => {
                    this.nuevaInternacion = { paciente_id: '', profesional_id: '', sector_id: '', diagnostico: '' };  // Resetea el formulario
                    Swal.fire('¡Éxito!', 'Internación agregada correctamente.', 'success')
                        .then(() => {
                            location.reload();  // Recarga la página
                        });
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'El paciente ya está internado.', 'error');
                });
        }
    }
}).mount('#app');
