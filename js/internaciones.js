const app = Vue.createApp({
    data() {
        return {
            pacientes: [],
            profesionales: [],
            dietas: [],
            sectores: [],
            internacion: {
                paciente_id: '',
                fecha_ingreso: '',
                fecha_egreso: '',
                dieta_id: '',
                profesional_id: '',
                sector_id: '',
                diagnostico: '',
                observacion: ''
            },
            mensaje: ''
        };
    },
    mounted() {
        this.obtenerDatosIniciales();
    },
    methods: {
        obtenerDatosIniciales() {
            axios.get('api/internaciones.php?action=datos_iniciales')
                .then(response => {
                    this.pacientes = response.data.pacientes;
                    this.profesionales = response.data.profesionales;
                    this.dietas = response.data.dietas;
                    this.sectores = response.data.sectores;
                })
                .catch(error => {
                    console.error("Error al obtener los datos iniciales:", error);
                });
        },
        registrarInternacion() {
            axios.post('api/internaciones.php?action=registrar', this.internacion)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire(
                            'Éxito',
                            'Internación registrada exitosamente.',
                            'success'
                        );
                        this.limpiarFormulario();
                    } else {
                        Swal.fire(
                            'Error',
                            response.data.message,
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error("Error al registrar la internación:", error);
                });
        },
        limpiarFormulario() {
            this.internacion = {
                paciente_id: '',
                fecha_ingreso: '',
                fecha_egreso: '',
                dieta_id: '',
                profesional_id: '',
                sector_id: '',
                diagnostico: '',
                observacion: ''
            };
        }
    }
}).mount('#app');
