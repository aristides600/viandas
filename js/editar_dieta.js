const app = Vue.createApp({
    data() {
        return {
            paciente: {
                nombre: '',
                apellido: '',
                dni: '',
                fecha_nacimiento: ''
            },
            internacion: {
                diagnostico: '',
                fecha_ingreso: '',
                fecha_egreso: null
            },
            pacienteDieta: {
                id: null,
                dieta_id: null,
                // fecha_consumo: null,
                acompaniante: null, // Valor inicial
                mensaje: '',
                observacion: '',
                postre_id: null,
                colacion_id: null,
                suplemento_id: null,
            },
            dietas: [],
            postres: [],
            colaciones: [],
            suplementos: []


        };
    },
    mounted() {
        // Obtener el ID de la tabla `pacientes_dietas` desde la URL
        const id = new URLSearchParams(window.location.search).get('id');
        if (id) {
            this.obtenerDatosRelacionados(id);
        }
        this.cargarDietas();
        this.cargarPostres();
        this.obtenerColaciones();
        this.obtenerSuplementos();

        this.pacienteDieta.acompaniante = !!this.pacienteDieta.acompaniante;
    },
    methods: {
        obtenerDatosRelacionados(id) {
            axios.get(`api/obtener_datos_paciente_dieta.php?id=${id}`)
                .then(response => {
                    const datos = response.data;

                    // Datos del paciente
                    this.paciente = {
                        nombre: datos.nombre_paciente,
                        apellido: datos.apellido_paciente,
                        dni: datos.dni_paciente,
                        fecha_nacimiento: datos.fecha_nacimiento
                    };

                    // Datos de la internación
                    this.internacion = {
                        diagnostico: datos.diagnostico,
                        fecha_ingreso: datos.fecha_ingreso,
                        fecha_egreso: datos.fecha_egreso
                    };

                    // Datos de la dieta
                    this.pacienteDieta = {
                        id: datos.id_dieta,
                        dieta_id: datos.dieta_id,
                        fecha_consumo: datos.fecha_consumo,
                        // acompaniante: datos.acompaniante,
                        acompaniante: datos.acompaniante === 1, // Conversión a booleano
                        mensaje: datos.mensaje,
                        observacion: datos.observacion,
                        postre_id: datos.postre_id,
                        colacion_id: datos.colacion_id,
                        suplemento_id: datos.suplemento_id

                    };
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `No se pudieron cargar los datos relacionados: ${error.message}`,
                    });
                });
        },
        cargarDietas() {
            axios.get('api/dietas.php')
                .then(response => {
                    this.dietas = response.data;
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron cargar las dietas.', 'error');
                });
        },
        cargarPostres() {
            axios.get('api/postres.php')
                .then(response => {
                    this.postres = response.data;
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron cargar los postres.', 'error');
                });
        },
        obtenerColaciones() {
            axios.get('api/colaciones.php')
                .then(response => {
                    this.colaciones = response.data;
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron cargar los colaciones.', 'error');
                });
        },
        obtenerSuplementos() {
            axios.get('api/suplementos.php')
                .then(response => {
                    this.suplementos = response.data;
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron cargar los suplementos.', 'error');
                });
        },
        editarDieta() {
            // Verificar campos obligatorios
            if (!this.pacienteDieta.dieta_id) {
                Swal.fire('Error', 'Por favor, complete todos los campos obligatorios.', 'error');
                return;
            }

            axios.put(`api/editar_paciente_dieta.php?id=${this.pacienteDieta.id}`, this.pacienteDieta)
                .then(() => {
                    Swal.fire('Éxito', 'La dieta fue actualizada correctamente.', 'success');
                })
                .catch(error => {
                    Swal.fire('Error', `No se pudo actualizar la dieta: ${error.response.data.message}`, 'error');
                });
        },
        eliminarDieta() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará la dieta del paciente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.delete(`api/eliminar_paciente_dieta.php?id=${this.pacienteDieta.id}`)
                        .then(() => {
                            Swal.fire('Éxito', 'La dieta fue eliminada correctamente.', 'success');
                            window.location.href = 'listado_dietas.php';
                        })
                        .catch(error => {
                            Swal.fire('Error', `No se pudo eliminar la dieta: ${error.response.data.message}`, 'error');
                        });
                }
            });
        }
    }
});

app.mount('#app');
