const { jsPDF } = window.jspdf;

const app = Vue.createApp({
    data() {
        return {
            dietas: [],
            filtro: '' // Valor del filtro para la búsqueda
        };
    },
    mounted() {
        this.cargarDietas();
    },
    computed: {
        // Método computado para filtrar dietas por DNI o Apellido
        pacientesFiltrados() {
            return this.dietas.filter(dieta => {
                const apellido = dieta.apellido_paciente.toLowerCase();
                const dni = dieta.dni ? dieta.dni.toString() : '';
                const filtroLowerCase = this.filtro.toLowerCase();
                return apellido.includes(filtroLowerCase) || dni.includes(filtroLowerCase);
            });
        }
    },
    methods: {
        async cargarDietas() {
            try {
                const response = await axios.get('api/pacientes_dietas.php');
                this.dietas = response.data;
            } catch (error) {
                Swal.fire('Error', 'No se pudieron cargar las dietas.', 'error');
            }
        },
        generatePDF() {
            const doc = new jsPDF();
            doc.text('Reporte de Dietas por Sector', 10, 10);
            doc.autoTable({
                head: [['Sector', 'Apellido', 'Nombre', 'Edad', 'Código de Dieta', 'Observación', 'Comida', 'Fecha Consumo']],
                body: this.pacientesFiltrados.map(dieta => [
                    dieta.nombre_sector,
                    dieta.apellido_paciente,
                    dieta.nombre_paciente,
                    dieta.edad,
                    dieta.codigo_dieta, // Código de la dieta
                    dieta.observacion,
                    dieta.nombre_comida,
                    dieta.fecha_consumo
                ])
            });
            doc.save('reporte_dietas.pdf');
        }
    }
});

app.mount('#app');
