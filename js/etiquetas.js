const app = Vue.createApp({
    data() {
        return {
            pacientes: [],
            filtro: '' // Valor del filtro
        };
    },
    computed: {
        // Método computado para filtrar pacientes por DNI o Apellido
        pacientesFiltrados() {
            return this.pacientes.filter(paciente => {
                const apellido = paciente.apellido_paciente.toLowerCase();
                const dni = paciente.dni ? paciente.dni.toString() : '';
                const filtroLowerCase = this.filtro.toLowerCase();
                
                return apellido.includes(filtroLowerCase) || dni.includes(filtroLowerCase);
            });
        }
    },
    mounted() {
        this.obtenerPacientes();
    },
    methods: {
        obtenerPacientes() {
            axios.get('api/pacientes_internados.php')
                .then(response => {
                    this.pacientes = response.data;
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudieron cargar los pacientes internados', 'error');
                });
        },
        // Generar PDF de todas las etiquetas de pacientes
        generarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            let y = 10;

            this.pacientes.forEach(paciente => {
                doc.text(`Paciente: ${paciente.nombre_paciente} ${paciente.apellido_paciente}`, 10, y);
                y += 10;
                doc.text(`Dieta: ${paciente.nombre_dieta} (${paciente.codigo_dieta})`, 10, y);
                y += 10;
                doc.text(`Sector: ${paciente.nombre_sector}`, 10, y);
                y += 10;
                doc.text(`Observación: ${paciente.observacion || 'Sin observaciones'}`, 10, y);
                y += 20;

                if (paciente.acompaniante == 1) {
                    doc.text(`Acompañante de ${paciente.nombre_paciente} ${paciente.apellido_paciente}`, 10, y);
                    y += 10;
                    doc.text('Dieta: General', 10, y);
                    y += 10;
                    doc.text(`Sector: ${paciente.nombre_sector}`, 10, y);
                    y += 10;
                    doc.text(`Observación: ${paciente.observacion || 'Sin observaciones'}`, 10, y);
                    y += 20;
                }
            });

            doc.save('etiquetas_pacientes.pdf');
        },
        // Generar etiqueta de un paciente y su acompañante (si existe)
        generarEtiquetaIndividual(paciente) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            let y = 10;

            // Etiqueta del paciente
            doc.text(`Paciente: ${paciente.nombre_paciente} ${paciente.apellido_paciente}`, 10, y);
            y += 10;
            doc.text(`Dieta: ${paciente.nombre_dieta} (${paciente.codigo_dieta})`, 10, y);
            y += 10;
            doc.text(`Sector: ${paciente.nombre_sector}`, 10, y);
            y += 10;
            doc.text(`Observación: ${paciente.observacion || 'Sin observaciones'}`, 10, y);
            y += 20;

            // Etiqueta del acompañante (si tiene)
            if (paciente.acompaniante == 1) {
                doc.text(`Acompañante de ${paciente.nombre_paciente} ${paciente.apellido_paciente}`, 10, y);
                y += 10;
                doc.text('Dieta: General', 10, y);
                y += 10;
                doc.text(`Sector: ${paciente.nombre_sector}`, 10, y);
                y += 10;
                doc.text(`Observación: ${paciente.observacion || 'Sin observaciones'}`, 10, y);
            }

            doc.save(`etiqueta_${paciente.nombre_paciente}_${paciente.apellido_paciente}.pdf`);
        }
    }
});

app.mount('#app');
