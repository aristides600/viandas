const app = Vue.createApp({
    data() {
        return {
            comidaSeleccionada: null,
            mensaje: null,
            comidas: []
        };
    },
    mounted() {
        this.obtenerComidas();
    },
    methods: {
        obtenerComidas() {
            axios.get('api/comidas.php')
                .then(response => {
                    this.comidas = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener comidas:', error);
                    this.mensaje = { texto: 'Error al cargar las comidas.', clase: 'alert-danger' };
                });
        },
        async consumoDiario() {
            try {
                const response = await axios.post('api/consumos_diarios.php', {
                    comida_id: this.comidaSeleccionada
                });
                const data = response.data;

                this.mensaje = {
                    texto: data.message,
                    clase: data.status === 'success' ? 'alert-success' : 'alert-danger'
                };
            } catch (error) {
                console.error('Error:', error);
                this.mensaje = {
                    texto: 'Hubo un error al procesar la solicitud.',
                    clase: 'alert-danger'
                };
            }
        }
        // async consumoDiario() {
        //     console.log('Comida seleccionada:', this.comidaSeleccionada); // Verifica el valor aquí
        //     if (!this.comidaSeleccionada) {
        //         this.mensaje = { texto: 'Por favor, selecciona una comida.', clase: 'alert-danger' };
        //         return;
        //     }
        
        //     try {
        //         const response = await axios.post('api/consumos_diarios.php', {
        //             comida_id: this.comidaSeleccionada
        //         });
        //         // Resto del código...
        //     } catch (error) {
        //         console.error('Error:', error);
        //         this.mensaje = {
        //             texto: 'Hubo un error al procesar la solicitud.',
        //             clase: 'alert-danger'
        //         };
        //     }
        // }
    }
});

app.mount('#app');
