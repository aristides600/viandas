const { createApp } = Vue;

createApp({
    data() {
        return {
            sectores: [],  // Almacenará los sectores con los totales por comida
            totalesGenerales: [],  // Almacenará los totales generales
            error: ''  // Manejo de errores
        };
    },
    mounted() {
        this.obtenerDatos();
    },
    methods: {
        async obtenerDatos() {
            try {
                const response = await fetch('api/totales.php');
                const data = await response.json();
                if (data.error) {
                    this.error = `Error al cargar los datos: ${data.error}`;
                    return;
                }
                this.sectores = data;
                this.totalesGenerales = data['totales_generales'] || [];
            } catch (err) {
                this.error = `Error al cargar los datos: ${err.message}`;
            }
        }
    }
}).mount('#app');
