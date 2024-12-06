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
        formatoFecha(fecha) {
            if (!fecha || fecha === '-') return '-'; // Manejar valores nulos, vacíos o "-"

            // Asegurarse de que la fecha sea interpretable por el objeto Date
            const date = new Date(fecha);
            if (isNaN(date)) return '-'; // Si no es válida, retornar "-"

            const dia = date.getDate().toString().padStart(2, '0');
            const mes = (date.getMonth() + 1).toString().padStart(2, '0'); // Los meses son base 0
            const anio = date.getFullYear();

            const horas = date.getHours().toString().padStart(2, '0');
            const minutos = date.getMinutes().toString().padStart(2, '0');
            const segundos = date.getSeconds().toString().padStart(2, '0');

            return `${dia}/${mes}/${anio} ${horas}:${minutos}:${segundos}`;
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
