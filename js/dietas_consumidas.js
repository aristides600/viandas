const app = Vue.createApp({
    data() {
        return {
            cambiosDietas: []
        };
    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        const internacionId = urlParams.get('internacion_id');
        console.log(internacionId)

        if (internacionId) {
            this.fetchCambiosDietas(internacionId);
        } else {
            Swal.fire('Error', 'No se proporcionó un internacion_id válido.', 'error');
        }
    },
    methods: {
        async fetchCambiosDietas(internacionId) {
            try {
                const response = await axios.get(`api/dietas_consumidas.php?internacion_id=${internacionId}`);
                this.cambiosDietas = response.data;
                console.log(this.cambiosDietas); // Depuración
            } catch (error) {
                console.error('Error al obtener datos:', error.response ? error.response.data : error.message);
                Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
            }
        }
    }
});

app.mount('#app');