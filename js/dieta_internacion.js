const app = Vue.createApp({
    data() {
        return {
            comidas: [],
            dietas: [],

            form: {
                paciente_id: '',
                dieta_id: '',
                internacion_id: '',
                comida_id: '',
                fecha_consumo: '',
                observacion: '',
                acompaniante: false,
                estado: 1
            }
        };
    },
    mounted() {
        const id = new URLSearchParams(window.location.search).get('id'); // Obtener el ID de la internación
        this.form.internacion_id = id;
        this.obtenerDatosInternacion(id);
        this.obtenerComidas();
        this.obtenerDietas();

    },
    methods: {
        obtenerDatosInternacion(id) {
            axios.get(`api/obtener_internacion.php?id=${id}`)
                .then(response => {
                    this.internacion = response.data;
                    this.paciente = response.data; // Asignar datos del paciente e internación
                    this.form.paciente_id = response.data.paciente_id; // Rellenar el campo del formulario
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudieron cargar los datos del paciente', 'error');
                });
        },
        obtenerComidas() {
            axios.get('api/comidas.php')
                .then(response => {
                    this.comidas = response.data;
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudieron cargar los tipos de comida', 'error');
                });
        },
        obtenerDietas() {
            axios.get('api/dietas.php')
                .then(response => {
                    this.dietas = response.data;
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudieron cargar los tipos de dieta', 'error');
                });
        },
        guardarDieta() {
            axios.post('api/pacientes_dietas.php', this.form)
                .then(response => {
                    Swal.fire('Éxito', 'Dieta guardada correctamente', 'success');
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo guardar la dieta', 'error');
                });
        }
    }
});
app.mount('#app');