const app = Vue.createApp({
    data() {
        return {
            paciente: {
                dni: '',
                nombre: '',
                apellido: '',
                fecha_nacimiento: '',
                sexo_id: ''
            },
            sexos: []
        };
    },
    methods: {
        obtenerSexos() {
            axios.get('api/sexos.php')
                .then(response => {
                    this.sexos = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener sexos:', error);
                });
        },
        guardarPaciente() {
            const datos = { ...this.paciente };
        
            axios.post('api/pacientes.php', datos)
                .then(response => {
                    Swal.fire('Éxito', response.data.message, 'success')
                        .then(() => {
                            if (response.data.success) {
                                location.reload(); // Recargar la página
                            }
                        });
                })
                .catch(error => {
                    Swal.fire('Error', error.response.data.message, 'error');
                    console.error('Error al guardar paciente:', error);
                });
        },
        
        
    },
    mounted() {
        this.obtenerSexos();
    }
});

app.mount('#app');
