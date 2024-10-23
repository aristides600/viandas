const app = Vue.createApp({
    data() {
        return {
            pacientes: []
        };
    },
    mounted() {
        this.obtenerPacientes();
    },
    methods: {
        obtenerPacientes() {
            axios.get('api/pacientes_internados.php')
                .then(response => {
                    console.log(response.data); // Verifica que los datos incluyan nombre_sector
                    this.pacientes = response.data;
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error', 'No se pudieron cargar los pacientes internados', 'error');
                });
        },
        generarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            let y = 10; // Posición inicial en Y

            this.pacientes.forEach(paciente => {
                doc.text(`Paciente: ${paciente.nombre_paciente} ${paciente.apellido_paciente}`, 10, y);
                y += 10;
                doc.text(`Dieta: ${paciente.nombre_dieta} (${paciente.codigo_dieta})`, 10, y); // nombre_dieta ahora debería estar disponible
                y += 10;
                doc.text(`Sector: ${paciente.nombre_sector}`, 10, y); // nombre_sector también disponible
                y += 10;
                doc.text(`Observación: ${paciente.observacion || 'Sin observaciones'}`, 10, y);
                y += 20; // Espacio entre pacientes
            });

            doc.save('etiquetas_pacientes.pdf');
        }
    }
});

app.mount('#app');