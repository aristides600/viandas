const app = Vue.createApp({
    data() {
        return {
            fechaDesde: '',  // Fecha de inicio del informe
            fechaHasta: '',  // Fecha de fin del informe
            reporte: [],     // Datos del informe
            subtotales: [],  // Subtotales por sector
            dietasTotales: [], // Totales por dieta
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
            // Subtotales por sector
            this.subtotales = [];
            const sectores = {};

            this.reporte.forEach(dieta => {
                if (!sectores[dieta.sector]) {
                    sectores[dieta.sector] = 0;
                }
                sectores[dieta.sector] += dieta.cantidad;
            });

            for (const sector in sectores) {
                this.subtotales.push({ sector, total: sectores[sector] });
            }

            // Totales por dieta
            this.dietasTotales = [];
            const dietas = {};

            this.reporte.forEach(dieta => {
                if (!dietas[dieta.dieta]) {
                    dietas[dieta.dieta] = 0;
                }
                dietas[dieta.dieta] += dieta.cantidad;
            });

            for (const dieta in dietas) {
                this.dietasTotales.push({ dieta, total: dietas[dieta] });
            }
        },
        generarGrafico() {
            // Limpiar el gráfico existente
            if (this.dietaChart) {
                this.dietaChart.destroy();
            }

            // Obtener datos para el gráfico
            const labels = this.dietasTotales.map(d => d.dieta);
            const data = this.dietasTotales.map(d => d.total);

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
            const { jsPDF } = window.jspdf; // Asegúrate de que jsPDF esté definido
            const logoPath = '/vianda/img/logo.png'; // Ruta al logo
            const img = new Image();
            img.src = logoPath;

            img.onload = () => {
                const doc = new jsPDF();

                // Tamaño y posición del logo
                const logoWidth = 20;
                const logoHeight = 15;
                const logoX = 14;
                const logoY = 10;

                // Agregar el logo al PDF
                doc.addImage(img, 'PNG', logoX, logoY, logoWidth, logoHeight);

                // Formatear las fechas del rango
                const fechaDesdeFormateada = this.formatearFecha(this.fechaDesde);
                const fechaHastaFormateada = this.formatearFecha(this.fechaHasta);

                // Título general y texto de rango de fechas
                const title = "Informe de Dietas";
                const dateRangeText = `Desde: ${fechaDesdeFormateada} | Hasta: ${fechaHastaFormateada}`;
                const combinedText = `${title} - ${dateRangeText}`;
                doc.setFontSize(14);

                // Coordenadas para centrar el texto verticalmente con el logo
                const combinedTextX = logoX + logoWidth + 5; // Espaciado a la derecha del logo
                const combinedTextY = logoY + logoHeight / 2 + 2; // Centrado verticalmente respecto al logo

                // Agregar el texto combinado al PDF
                doc.text(combinedText, combinedTextX, combinedTextY);

                let y = logoY + logoHeight + 10; // Posición inicial ajustada para los elementos

                // Agrupar los datos por sector
                const datosPorSector = this.reporte.reduce((acc, item) => {
                    if (!acc[item.sector]) {
                        acc[item.sector] = [];
                    }
                    acc[item.sector].push({
                        dieta: item.dieta,
                        cantidad: item.cantidad
                    });
                    return acc;
                }, {});

                // Iterar por cada sector y agregar datos al PDF
                for (const [sector, datos] of Object.entries(datosPorSector)) {
                    // Agregar el título del sector (centralizado)
                    doc.setFontSize(12);
                    const sectorText = `${sector}`;
                    const sectorTextX = (doc.internal.pageSize.width - doc.getTextWidth(sectorText)) / 2;
                    doc.text(sectorText, sectorTextX, y);
                    y += 5; // Reducir espacio para que la grilla esté más pegada

                    // Agregar la tabla de dietas y cantidades
                    const tableData = datos.map(d => [
                        d.dieta,
                        d.cantidad
                    ]);
                    doc.autoTable({
                        startY: y,
                        head: [['Dieta', 'Cantidad']],
                        body: tableData,
                        headStyles: {
                            fontSize: 10,
                            cellPadding: 2,
                        },
                        margin: { left: 14, right: 14 }, // Opcional: Ajustar márgenes si es necesario
                        columnStyles: {
                            1: { cellWidth: 30 }, // Ajustar ancho si es necesario
                        },
                    });

                    // Actualizar la posición Y para el siguiente sector
                    y = doc.lastAutoTable.finalY + 7; // Reducir espacio entre tablas
                }

                // Mostrar la vista previa del PDF sin descargarlo
                window.open(doc.output('bloburl'), '_blank');
            };

            img.onerror = () => {
                console.error("No se pudo cargar el logo desde la ruta proporcionada.");
            };
        },
        formatearFecha(fecha) {
            if (!fecha || fecha === '-') return '-'; // Manejar valores nulos, vacíos o "-"
            const date = new Date(fecha);
            if (isNaN(date.getTime())) return '-'; // Si no es válida, retornar "-"
            const dia = date.getDate().toString().padStart(2, '0');
            const mes = (date.getMonth() + 1).toString().padStart(2, '0'); // Los meses son base 0
            const anio = date.getFullYear();
            return `${dia}/${mes}/${anio}`;
        }


    }
});

app.mount("#app");
