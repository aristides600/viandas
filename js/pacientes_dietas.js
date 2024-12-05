const { jsPDF } = window.jspdf;

const app = Vue.createApp({
    data() {
        return {
            dietas: [],
            filtro: ''
        };
    },
    mounted() {
        this.cargarDietas();
    },
    computed: {
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
        editarDieta(id) {
            window.location.href = "editar_dieta.php?id=" + id;
        },
        verDietas(internacion_id) {
            // window.location.href = `dietas_consumidas.php?internacion_id=${internacion_id}`;
            window.location.href = "dietas_consumidas.php?internacion_id=" + internacion_id;

        },
        formatearFecha(fecha) {
            if (!fecha || fecha === '-') return '-'; // Manejar valores nulos, vacíos o "-"
        
            // Intentar interpretar la fecha de manera segura
            const date = new Date(fecha);
        
            // Verificar si la fecha es válida
            if (isNaN(date.getTime())) return '-'; // Si no es válida, retornar "-"
        
            const dia = date.getDate().toString().padStart(2, '0');
            const mes = (date.getMonth() + 1).toString().padStart(2, '0'); // Los meses son base 0
            const anio = date.getFullYear();
        
            return `${dia}/${mes}/${anio}`;
        },
        

        async eliminarDieta(id) {
            try {
                const confirmacion = await Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'Esta acción marcará la dieta como eliminada.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                });

                if (confirmacion.isConfirmed) {
                    await axios.delete(`api/pacientes_dietas.php?id=${id}`);
                    Swal.fire('Éxito', 'La dieta ha sido eliminada.', 'success');
                    this.cargarDietas();
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo eliminar la dieta.', 'error');
            }
        },
        generarPDF() {
            const doc = new jsPDF();
            doc.text('Reporte de Dietas por Sector', 10, 10);
            doc.autoTable({
                head: [['Sector', 'Apellido', 'Nombre', 'Edad', 'Código de Dieta', 'Observación', 'Comida', 'Fecha Consumo']],
                body: this.pacientesFiltrados.map(dieta => [
                    dieta.nombre_sector,
                    dieta.apellido_paciente,
                    dieta.nombre_paciente,
                    dieta.edad,
                    dieta.codigo_dieta,
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
