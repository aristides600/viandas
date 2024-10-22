const app = Vue.createApp({
    data() {
        return {
            dietas: [],
            nuevaDieta: {
                dieta_id: '',
                internacion_id: 1,  // Suponiendo que este valor ya está asignado
                usuario_id: 1       // Asigna el ID del usuario logueado
            }
        };
    },
    mounted() {
        this.obtenerDietas();
    },
    methods: {
        async obtenerDietas() {
            try {
                const response = await axios.get('api/dietas.php', {
                    params: { internacion_id: this.nuevaDieta.internacion_id }
                });
                this.dietas = response.data;
            } catch (error) {
                console.error('Error al obtener dietas', error);
            }
        },
        async agregarDieta() {
            try {
                const response = await axios.post('api/dietas.php', this.nuevaDieta);
                Swal.fire('Éxito', response.data.message, 'success');
                this.obtenerDietas();
            } catch (error) {
                Swal.fire('Error', 'No se pudo agregar la dieta', 'error');
            }
        },
        async eliminarDieta(id) {
            try {
                const response = await axios.delete('dietas.php', {
                    data: { id }
                });
                Swal.fire('Éxito', response.data.message, 'success');
                this.obtenerDietas();
            } catch (error) {
                Swal.fire('Error', 'No se pudo eliminar la dieta', 'error');
            }
        }
    }
});
app.mount('#app');