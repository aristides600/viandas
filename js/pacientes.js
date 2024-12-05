const app = Vue.createApp({
    data() {
        return {
            pacientes: [],
            paciente: {},
        };
    },
    mounted() {
        this.obtenerPacientes();
    },
    methods: {
        obtenerPacientes() {
            axios.get('api/pacientes.php')
                .then(response => {
                    this.pacientes = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        formatearFecha(fecha) {
            if (!fecha) return '';
            const [year, month, day] = fecha.split('-');
            return `${day}-${month}-${year}`;  // Formatea la fecha como dd-mm-yyyy
        },
       
        editarPaciente(id) {
            window.location.href = "editar_paciente.php?id=" + id;
        },

        eliminarPaciente(id) {
            axios.delete(`api/pacientes.php?id=${id}`)
                .then(response => {
                    Swal.fire('Éxito', response.data.message, 'success');
                    this.obtenerPacientes();  // Recargar la lista de pacientes
                })
                .catch(error => {
                    if (error.response && error.response.data) {
                        Swal.fire('Error', error.response.data.message, 'error');
                    }
                });
        },
        
        nuevoPaciente() {
            window.location.href = "nuevo_paciente.php";

        }
    },
});

app.mount('#app');
