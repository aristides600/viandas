const app = Vue.createApp({
    data() {
        return {
            paciente: {
                id: null,
                nombre: '',
                apellido: '',
                dni: '',
                fecha_nacimiento: '',
                sexo_id: ''
            },
            sexos: []
        };
    },
    mounted() {
        const id = new URLSearchParams(window.location.search).get('id');
        if (id) {
            this.paciente.id = id;
            this.obtenerPaciente(id);
        } else {
            Swal.fire('Error', 'No se pudo obtener el ID de la internación.', 'error');
        }
        this.obtenerSexos();
    },
    methods: {
        obtenerPaciente(id) {
            axios.get(`api/obtener_paciente.php?id=${id}`)
                .then(response => {
                    this.paciente = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener el paciente:', error);
                    Swal.fire('Error', 'No se pudo obtener los datos del paciente.', 'error');
                });
        },
        obtenerSexos() {
            axios.get('api/sexos.php')
                .then(response => {
                    this.sexos = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener sexos:', error);
                });
        },
        editarPaciente() {
            // Enviar los datos directamente al backend
            axios.put(`api/pacientes.php?id=${this.paciente.id}`, this.paciente)
                .then(response => {
                    Swal.fire('Éxito', 'Los datos del paciente han sido actualizados.', 'success');
                })
                .catch(error => {
                    console.error('Error al guardar los datos del paciente:', error);
                    Swal.fire('Error', 'No se pudo guardar los datos del paciente.', 'error');
                });
        }

    }
});

app.mount('#app');