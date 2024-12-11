const app = Vue.createApp({
    data() {
        return {
            fechaDesde: '',  // Fecha de inicio del informe
            fechaHasta: '',  // Fecha de fin del informe
            reporte: [],     // Datos del informe
            subtotales: [],  // Subtotales por sector
            totalGeneral: 0, // Total general de dietas
            dietaChart: null // Nueva propiedad para el gráfico
        };
    },
    methods: {
        generarReporte() {
            // Obtener los datos del reporte
            axios.get(`api/reportes.php?fecha_desde=${this.fechaDesde}&fecha_hasta=${this.fechaHasta}`)
                .then(response => {
                    this.reporte = response.data;

                    // Calcular subtotales y total general
                    this.calcularSubtotales();
                    this.totalGeneral = this.reporte.reduce((total, dieta) => total + dieta.cantidad, 0);

                    // Generar gráfico
                    this.generarGrafico();
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo generar el informe', 'error');
                });
        },
        calcularSubtotales() {
            this.subtotales = [];
            const sectores = {};

            // Calcular subtotales por sector
            this.reporte.forEach(dieta => {
                if (!sectores[dieta.sector]) {
                    sectores[dieta.sector] = 0;
                }
                sectores[dieta.sector] += dieta.cantidad;
            });

            for (const sector in sectores) {
                this.subtotales.push({ sector, total: sectores[sector] });
            }
        },
        generarGrafico() {
            // Limpiar el gráfico existente
            if (this.dietaChart) {
                this.dietaChart.destroy();
            }

            // Obtener datos para el gráfico
            const labels = this.reporte.map(dieta => dieta.dieta);
            const data = this.reporte.map(dieta => dieta.cantidad);

            const ctx = document.getElementById('dietaChart').getContext('2d');

            this.dietaChart = new Chart(ctx, {
                type: 'bar', // Tipo de gráfico
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad de Dietas',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        },
        generarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
        
            // Cargar la imagen
            const logoPath = '/vianda/img/logo.png'; // Asegúrate de que esta ruta sea correcta
            const img = new Image();
            img.src = logoPath;
            
            img.onload = () => {
                doc.addImage(img, 'PNG', 10, 10, 30, 30); // Posición (x, y) y tamaño (ancho, alto)
        
                // Título
                doc.setFontSize(22);
                doc.text('Informe de Internaciones', doc.internal.pageSize.getWidth() / 2, 20, { align: 'center' });
        
                // Espacio después del título
                doc.setFontSize(12);
                doc.text('Fecha Desde: ' + this.fechaDesde, 10, 40);
                doc.text('Fecha Hasta: ' + this.fechaHasta, 10, 50);
                doc.text('Total General: ' + this.totalGeneral + ' dietas', 10, 60);
        
                // Tabla
                const tableData = this.reporte.map(dieta => [dieta.sector, dieta.dieta, dieta.cantidad]);
                doc.autoTable({
                    startY: 70,
                    head: [['Sector', 'Dieta', 'Cantidad']],
                    body: tableData
                });

                doc.save('informe_internaciones.pdf');
            };
        }
    }
});

app.mount("#app");
