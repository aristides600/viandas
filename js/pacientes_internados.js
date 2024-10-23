const { jsPDF } = window.jspdf;

const app = Vue.createApp({
    data() {
        return {
            dietas: []
        };
    },
    methods: {
        async cargarDietas() {
            try {
                const response = await axios.get('api/pacientes_internados.php');
                console.log(response.data); // Verifica los datos en la consola
                this.dietas = response.data;
            } catch (error) {
                Swal.fire('Error', 'No se pudieron cargar las dietas.', 'error');
            }
        },
        generatePDF() {
            const doc = new jsPDF();
            doc.text('Reporte de Dietas por Sector', 10, 10);
            doc.autoTable({
                head: [['Sector', 'Apellido', 'Nombre', 'Código de Dieta', 'Observación', 'Comida', 'Fecha Consumo']],
                body: this.dietas.map(dieta => [
                    dieta.nombre_sector,
                    dieta.apellido_paciente,
                    dieta.nombre_paciente,
                    dieta.codigo_dieta,
                    dieta.observacion,
                    dieta.nombre_comida,
                    dieta.fecha_consumo
                ])
            });
            doc.save('reporte_dietas.pdf');
        }
    },
    mounted() {
        this.cargarDietas();
    }
});

app.mount('#app');