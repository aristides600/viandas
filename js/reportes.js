const { createApp } = Vue;

createApp({
    data() {
        return {
            fechaDesde: '',
            fechaHasta: '',
            reporte: [],
            subtotales: [],
            totalGeneral: 0,
            chart: null  // Referencia al gráfico
        };
    },
    methods: {
        async generarReporte() {
            try {
                const response = await fetch(`api/reporte.php?fecha_desde=${this.fechaDesde}&fecha_hasta=${this.fechaHasta}`);
                if (!response.ok) {
                    throw new Error('Error en la solicitud al servidor');
                }
                const data = await response.json();
                if (data.error) {
                    console.error(data.error);
                } else {
                    this.reporte = data;
                    this.calcularSubtotales();
                    this.generarGrafico();  // Generar gráfico después de obtener los datos
                }
            } catch (error) {
                console.error('Error al obtener los datos:', error);
            }
        },
        
        calcularSubtotales() {
            const subtotales = {};
            let totalGeneral = 0;

            this.reporte.forEach(dieta => {
                if (!subtotales[dieta.sector]) {
                    subtotales[dieta.sector] = 0;
                }
                subtotales[dieta.sector] += parseInt(dieta.cantidad);
                totalGeneral += parseInt(dieta.cantidad);
            });

            this.subtotales = Object.keys(subtotales).map(sector => ({
                sector: sector,
                total: subtotales[sector]
            }));
            this.totalGeneral = totalGeneral;
        },

        generarGrafico() {
            // Destruir el gráfico anterior si existe
            if (this.chart) {
                this.chart.destroy();
            }

            const ctx = document.getElementById('dietaChart').getContext('2d');
            const sectores = this.subtotales.map(item => item.sector);
            const cantidades = this.subtotales.map(item => item.total);

            this.chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sectores,
                    datasets: [{
                        label: 'Cantidad de Dietas',
                        data: cantidades,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        duration: 1000,  // Duración de la animación en ms
                        easing: 'easeOutBounce'  // Efecto de animación
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }
}).mount('#app');
