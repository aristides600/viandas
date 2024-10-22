const { createApp } = Vue;

createApp({
    data() {
        return {
            sectores: [],       // Arreglo para almacenar los sectores
            profesionales: [],  // Arreglo para almacenar los profesionales
            dni: '',            // Inicializado como string vacío
            pacientes: [],      // Inicializado como arreglo vacío
            pacienteSeleccionado: null,  // Inicializado como null
            sinCoincidencias: false,     // Inicializado como falso
            nuevaInternacion: {
                paciente_id: '',
                profesional_id: '',
                sector_id: '',
                diagnostico: ''
                // observacion: ''
            },
            documento: {
                paciente_id: null
            }
        };
    },
    mounted() {
        // Cargar internaciones, sectores y profesionales al montar la aplicación
        this.cargarSectores();
        this.cargarProfesionales();
    },
    methods: {
        buscarPacientes() {
            if (this.dni.length > 0) {
                axios.get('api/pacientes_dni.php', { params: { dni: this.dni } })
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
        
        cargarSectores() {
            fetch('api/sectores.php', { method: 'GET' })
                .then(response => response.json())
                .then(data => { this.sectores = data; })
                .catch(error => console.error(error));
        },
        cargarProfesionales() {
            fetch('api/profesionales.php', { method: 'GET' })
                .then(response => response.json())
                .then(data => { this.profesionales = data; })
                .catch(error => console.error(error));
        },
        agregarInternacion() {
            const nuevaInternacionConPaciente = { ...this.nuevaInternacion, paciente_id: this.documento.paciente_id };
            axios.post('api/internaciones.php', nuevaInternacionConPaciente)
                .then(response => {
                    // this.cargarInternaciones();  // Actualiza la lista de internaciones
                    this.nuevaInternacion = { paciente_id: '', profesional_id: '', sector_id: '', diagnostico: ''};  // Resetea el formulario
                    Swal.fire('¡Éxito!', 'Internación agregada correctamente.', 'success');
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo agregar la internación.', 'error');
                });
        }
    }
}).mount('#app');
