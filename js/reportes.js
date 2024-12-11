const app = Vue.createApp({
    data() {
        return {
            fechaDesde: '',
            fechaHasta: '',
            consumos: [],
            chart: null
        };
    },
    methods: {
        // Método para obtener el reporte de consumos
        getConsumos() {
            axios.get(`api/consumos_diarios.php?fecha_desde=${this.fechaDesde}&fecha_hasta=${this.fechaHasta}`)
                .then(response => {
                    console.log(response.data); // Verifica qué datos estás recibiendo
        
                    // Verifica si la respuesta es un array
                    if (Array.isArray(response.data)) {
                        this.consumos = response.data;  // Asigna los consumos si es un array
                        this.updateChart();  // Llama a updateChart para generar el gráfico
                    } else {
                        Swal.fire('Error', 'La respuesta no es un array', 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo generar el informe', 'error');
                });
        },
        
        // Método para actualizar el gráfico
        updateChart() {
            if (Array.isArray(this.consumos) && this.consumos.length > 0) {
                const labels = this.consumos.map(consumo => consumo.sector);
                const data = this.consumos.map(consumo => consumo.cantidad);
        
                if (this.chart) {
                    this.chart.destroy(); // Destruye el gráfico anterior
                }
        
                const ctx = document.getElementById('myChart').getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Cantidad de Dietas',
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                console.error("No hay consumos válidos para procesar.");
            }
        }
        
    }
});

app.mount('#app');
