const app = Vue.createApp({
    data() {
        return {
            almuerzo: [],
            cena: [],
            fechaActual: ''
        };
    },
    methods: {
        // Método para obtener los datos desde el servidor
        obtenerDatos() {
            axios.get('api/totales.php') // Cambia por la ruta a tu archivo PHP
                .then(response => {
                    // Asignar datos a las variables almuerzo y cena
                    this.almuerzo = [response.data.por_sector.almuerzo.grupo_1, response.data.por_sector.almuerzo.grupo_2];
                    this.cena = [response.data.por_sector.cena.grupo_1, response.data.por_sector.cena.grupo_2];
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        },

        // Método para obtener la fecha actual
        obtenerFechaActual() {
            const fecha = new Date();
            const dia = String(fecha.getDate()).padStart(2, '0');
            const mes = String(fecha.getMonth() + 1).padStart(2, '0'); // Los meses son 0-indexados
            const anio = fecha.getFullYear();
            this.fechaActual = `${dia}/${mes}/${anio}`;
        },

        // Método para calcular el total de cada columna
        calcularTotales(grupo) {
            let totalFlan = 0;
            let totalGelatina = 0;
            let totalDietasGenerales = 0;
            let totalOtrasDietas = 0;

            grupo.forEach(data => {
                totalFlan += parseFloat(data.total_flan) || 0;
                totalGelatina += parseFloat(data.total_gelatina) || 0;
                totalDietasGenerales += parseFloat(data.total_dietas_generales) || 0;
                totalOtrasDietas += parseFloat(data.total_otras_dietas) || 0;
            });

            return {
                totalFlan,
                totalGelatina,
                totalDietasGenerales,
                totalOtrasDietas
            };
        }
    },

    mounted() {
        this.obtenerFechaActual(); // Obtener la fecha actual al cargar el componente
        this.obtenerDatos(); // Obtener los datos al cargar el componente

        // Actualizar cada 2 minutos
        setInterval(() => {
            this.obtenerDatos();
        }, 120000); // 120000 milisegundos = 2 minutos
    }
});

app.mount('#app');
