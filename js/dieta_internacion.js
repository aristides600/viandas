const app = Vue.createApp({
    data() {
        return {
            paciente: null,
            internacion: null,
            dietas: [],
            postres: [],
            dietaInternacion: {
                paciente_id: '',
                dieta_id: '',
                internacion_id: '',
                observacion: '',
                acompaniante: false,
                postre_id: null,
            },
        };
    },
    mounted() {
        const id = new URLSearchParams(window.location.search).get('id');
        if (id) {
            this.dietaInternacion.internacion_id = id;
            this.obtenerInternacion(id);
            this.obtenerDietas();
            this.obtenerPostres();
        }
    },
    methods: {
        obtenerInternacion(id) {
            if (!id) {
                Swal.fire('Error', 'El ID de la internación es inválido.', 'error');
                return;
            }
            axios.get(`api/dieta_internacion.php?internacion_id=${id}`)
                .then(response => {
                    const data = response.data;
                    if (data.paciente && data.internacion) {
                        this.paciente = data.paciente;
                        this.internacion = data.internacion;
                        this.dietaInternacion.paciente_id = data.paciente.id;
                    } else {
                        Swal.fire('Error', 'No se encontró la información del paciente.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error.response ? error.response.data : error);
                    Swal.fire('Error', 'No se pudo cargar la información del paciente.', 'error');
                });
        },
        obtenerDietas() {
            axios.get('api/dietas.php')
                .then(response => {
                    this.dietas = response.data;
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron cargar las dietas.', 'error');
                });
        },
        obtenerPostres() {
            axios.get('api/postres.php')
                .then(response => {
                    this.postres = response.data;
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron cargar los postres.', 'error');
                });
        },
        guardarDieta() {
            if (!this.dietaInternacion.paciente_id || !this.dietaInternacion.dieta_id || !this.dietaInternacion.internacion_id) {
                Swal.fire('Error', 'Todos los campos obligatorios deben completarse.', 'error');
                return;
            }

            // Convertir datos a los formatos correctos
            this.dietaInternacion.paciente_id = parseInt(this.dietaInternacion.paciente_id);
            this.dietaInternacion.dieta_id = parseInt(this.dietaInternacion.dieta_id);
            this.dietaInternacion.internacion_id = parseInt(this.dietaInternacion.internacion_id);

            // Enviar datos al servidor
            axios.post('api/pacientes_dietas.php', this.dietaInternacion)
                .then(() => {
                    Swal.fire('Éxito', 'Dieta asignada correctamente.', 'success');
                    this.reiniciarFormulario();
                })
                .catch((error) => {
                    const mensaje = error.response?.data?.error || 'No se pudo guardar la dieta.';
                    Swal.fire('Error', mensaje, 'error');
                });
        },


        reiniciarFormulario() {
            this.dietaInternacion = {
                paciente_id: '',
                dieta_id: '',
                internacion_id: '',
                observacion: '',
                acompaniante: false,
                postre_id: null,
            };
        },
    },
});
app.mount('#app');
