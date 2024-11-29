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
                fecha_consumo: null,
                acompaniante: false,
                observacion: '',
                postre_id: null,
            },
            dietas: [],
            postres: []
        };
    },
    mounted() {
        const id = new URLSearchParams(window.location.search).get('id');
        if (id) {
            this.obtenerDatosInternacion(id);
            this.obtenerPacienteDieta(id);


        }
        this.cargarDietas();
        this.cargarPostres();
    },
    methods: {
        obtenerDatosInternacion(id) {
            axios.get(`api/obtener_datos_internacion.php?id=${id}`)
                .then(response => {
                    const datos = response.data;
                    this.paciente = {
                        nombre: datos.nombre,
                        apellido: datos.apellido,
                        dni: datos.dni,
                        fecha_nacimiento: datos.fecha_nacimiento
                    };
                    this.internacion = {
                        diagnostico: datos.diagnostico,
                        fecha_ingreso: datos.fecha_ingreso,
                        fecha_egreso: datos.fecha_egreso
                    };
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `No se pudieron cargar los datos de la internación: ${error.message}`
                    });
                });
        },
        obtenerPacienteDieta(id) {
            axios.get(`api/obtener_paciente_dieta.php?id=${id}`)
                .then(response => {
                    this.pacienteDieta = response.data;
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `No se pudo cargar la dieta del paciente: ${error.message}`,
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
        guardarCambios() {
            axios.put('api/editar_paciente_dieta.php', this.pacienteDieta)
                .then(() => {
                    Swal.fire('Éxito', 'Los cambios fueron guardados correctamente.', 'success');
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron guardar los cambios.', 'error');
                });
        }
    }
});

app.mount('#app');
