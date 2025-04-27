const app = Vue.createApp({
    data() {
        return {
            almuerzo: [],
            cena: [],
            fechaActual: '',
            totalAlmuerzo: {
                dietasGenerales: 0,
                otrasDietas: 0,
                gelatinas: 0,
                otrosPostres: 0
            },
            totalCena: {
                dietasGenerales: 0,
                otrasDietas: 0,
                gelatinas: 0,
                otrosPostres: 0
            }
        };
    },
    methods: {
        async obtenerDatos() {
            try {
                const response = await axios.get('api/totales.php');
                const datos = response.data;

                // Limpiar las listas antes de agregar nuevos datos
                this.almuerzo = [];
                this.cena = [];

                // Objetos para agrupar por sector
                const agrupadoAlmuerzo = {};
                const agrupadoCena = {};

                // Inicializamos los totales generales
                let totales = {
                    total_dietas_generales: 0,
                    total_otros_dietas: 0,
                    total_gelatinas: 0,
                    total_otros_postres: 0
                };

                datos.forEach(item => {
                    const sectorId = item.sector_id;
                    const sectorNombre = item.sector_nombre;

                    const grupo = {
                        sector_id: sectorId,
                        sector_nombre: sectorNombre,
                        total_dietas_generales: 0,
                        total_otros_dietas: 0,
                        total_gelatinas: 0,
                        total_otros_postres: 0,
                        pacientes: [] // pacientes individuales si querés listar después
                    };

                    // Verificamos comida_id y agrupamos los almuerzos y cenas
                    if (item.comida_id == 1) {
                        if (!agrupadoAlmuerzo[sectorId]) {
                            agrupadoAlmuerzo[sectorId] = { ...grupo };
                        }

                        // Agrupar Dietas
                        if (item.dieta_id == 9) {
                            agrupadoAlmuerzo[sectorId].total_dietas_generales += item.cantidad;
                            totales.total_dietas_generales += item.cantidad; // Acumulando el total general
                        } else if (item.dieta_id != 1) {
                            agrupadoAlmuerzo[sectorId].total_otros_dietas += item.cantidad;
                            totales.total_otros_dietas += item.cantidad; // Acumulando el total general
                        }

                        // Agrupar Postres
                        if (item.postre_id == 2) {
                            agrupadoAlmuerzo[sectorId].total_gelatinas += item.cantidad;
                            totales.total_gelatinas += item.cantidad; // Acumulando el total general
                        } else if (item.postre_id != 1) {
                            agrupadoAlmuerzo[sectorId].total_otros_postres += item.cantidad;
                            totales.total_otros_postres += item.cantidad; // Acumulando el total general
                        }

                        // Si hay acompañante, sumamos una dieta general extra
                        if (item.acompaniante == 1) {
                            agrupadoAlmuerzo[sectorId].total_dietas_generales += item.cantidad;
                            totales.total_dietas_generales += item.cantidad; // Acumulando el total general
                        }

                        agrupadoAlmuerzo[sectorId].pacientes.push(item);
                    } else if (item.comida_id == 2) {
                        if (!agrupadoCena[sectorId]) {
                            agrupadoCena[sectorId] = { ...grupo };
                        }

                        // Agrupar Dietas
                        if (item.dieta_id == 9) {
                            agrupadoCena[sectorId].total_dietas_generales += item.cantidad;
                            totales.total_dietas_generales += item.cantidad; // Acumulando el total general
                        } else if (item.dieta_id != 1) {
                            agrupadoCena[sectorId].total_otros_dietas += item.cantidad;
                            totales.total_otros_dietas += item.cantidad; // Acumulando el total general
                        }

                        // Agrupar Postres
                        if (item.postre_id == 2) {
                            agrupadoCena[sectorId].total_gelatinas += item.cantidad;
                            totales.total_gelatinas += item.cantidad; // Acumulando el total general
                        } else if (item.postre_id != 1) {
                            agrupadoCena[sectorId].total_otros_postres += item.cantidad;
                            totales.total_otros_postres += item.cantidad; // Acumulando el total general
                        }

                        // Si hay acompañante, sumamos una dieta general extra
                        if (item.acompaniante == 1) {
                            agrupadoCena[sectorId].total_dietas_generales += item.cantidad;
                            totales.total_dietas_generales += item.cantidad; // Acumulando el total general
                        }

                        agrupadoCena[sectorId].pacientes.push(item);
                    }
                });

                console.log(agrupadoAlmuerzo); // Muestra los grupos de almuerzo
                console.log(agrupadoCena); // Muestra los grupos de cena
                console.log(totales); // Muestra los totales generales

                // Convertimos los objetos a arrays para que Vue pueda renderizar
                this.almuerzo = Object.values(agrupadoAlmuerzo);
                this.cena = Object.values(agrupadoCena);
                // Calcular los totales después de obtener los datos
                this.calcularTotales();

            } catch (error) {
                console.error('Error al obtener los datos:', error);
            }
        },
        // Método para calcular los totales de las columnas
        calcularTotales() {
            // Calcular los totales para almuerzo
            this.totalAlmuerzo.dietasGenerales = this.almuerzo.reduce((acc, sector) => acc + sector.total_dietas_generales, 0);
            this.totalAlmuerzo.otrasDietas = this.almuerzo.reduce((acc, sector) => acc + sector.total_otros_dietas, 0);
            this.totalAlmuerzo.gelatinas = this.almuerzo.reduce((acc, sector) => acc + sector.total_gelatinas, 0);
            this.totalAlmuerzo.otrosPostres = this.almuerzo.reduce((acc, sector) => acc + sector.total_otros_postres, 0);

            // Calcular los totales para cena
            this.totalCena.dietasGenerales = this.cena.reduce((acc, sector) => acc + sector.total_dietas_generales, 0);
            this.totalCena.otrasDietas = this.cena.reduce((acc, sector) => acc + sector.total_otros_dietas, 0);
            this.totalCena.gelatinas = this.cena.reduce((acc, sector) => acc + sector.total_gelatinas, 0);
            this.totalCena.otrosPostres = this.cena.reduce((acc, sector) => acc + sector.total_otros_postres, 0);
        },


        obtenerFechaActual() {
            const fecha = new Date();
            const dia = String(fecha.getDate()).padStart(2, '0');
            const mes = String(fecha.getMonth() + 1).padStart(2, '0');
            const anio = fecha.getFullYear();
            this.fechaActual = `${dia}/${mes}/${anio}`;
        },

    },

    mounted() {
        this.obtenerFechaActual();
        this.obtenerDatos();

        setInterval(this.obtenerDatos, 120000);
    }
});

app.mount('#app');