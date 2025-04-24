const app = Vue.createApp({
    data() {
        return {
            almuerzo: [],
            cena: [],
            fechaActual: ''
        };
    },
    methods: {
        async obtenerDatos() {
            try {
                const response = await axios.get('api/totales.php');

                const almuerzo = response.data?.por_sector?.almuerzo || {};
                const cena = response.data?.por_sector?.cena || {};

                this.almuerzo = [
                    almuerzo.grupo_1 || { sector: "Grupo 1", total_flan: 0, total_gelatina: 0, total_dietas_generales: 0, total_otras_dietas: 0 },
                    almuerzo.grupo_2 || { sector: "Grupo 2", total_flan: 0, total_gelatina: 0, total_dietas_generales: 0, total_otras_dietas: 0 }
                ];

                this.cena = [
                    cena.grupo_1 || { sector: "Grupo 1", total_flan: 0, total_gelatina: 0, total_dietas_generales: 0, total_otras_dietas: 0 },
                    cena.grupo_2 || { sector: "Grupo 2", total_flan: 0, total_gelatina: 0, total_dietas_generales: 0, total_otras_dietas: 0 }
                ];
            } catch (error) {
                console.error('Error al obtener los datos:', error);
            }
        },

        obtenerFechaActual() {
            const fecha = new Date();
            const dia = String(fecha.getDate()).padStart(2, '0');
            const mes = String(fecha.getMonth() + 1).padStart(2, '0');
            const anio = fecha.getFullYear();
            this.fechaActual = `${dia}/${mes}/${anio}`;
        },

        calcularTotales(grupo) {
            return grupo.reduce((totales, item) => {
                totales.totalFlan += parseFloat(item.total_flan) || 0;
                totales.totalGelatina += parseFloat(item.total_gelatina) || 0;
                totales.totalDietasGenerales += parseFloat(item.total_dietas_generales) || 0;
                totales.totalOtrasDietas += parseFloat(item.total_otras_dietas) || 0;
                return totales;
            }, {
                totalFlan: 0,
                totalGelatina: 0,
                totalDietasGenerales: 0,
                totalOtrasDietas: 0
            });
        }
    },

    mounted() {
        this.obtenerFechaActual();
        this.obtenerDatos();

        setInterval(this.obtenerDatos, 120000);
    }
});

app.mount('#app');
