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
        imprimirEtiqueta(internacion_id) {
            // Buscar la dieta correspondiente al internacion_id
            const dieta = this.dietas.find(d => d.internacion_id === internacion_id);
            if (!dieta) {
                Swal.fire('Error', 'No se encontró la dieta para esta internación.', 'error');
                return;
            }

            // Crear un nuevo documento PDF con el tamaño de página adecuado (55mm x 38mm) y orientación horizontal
            const doc = new jsPDF({
                unit: 'mm',  // Establecer las unidades en milímetros
                format: [63, 44],  // Ancho: 63mm, Alto: 44mm
                orientation: 'l'  // 'l' es para landscape (horizontal)
            });

            // Establecer el color del texto a negro (más oscuro)
            doc.setTextColor(0, 0, 0);  // RGB para color negro

            // Agregar el nombre del sector en el primer renglón (empezar desde 6mm para moverlo 3mm más abajo)
            doc.text(`Sector: ${dieta.nombre_sector}`, 1, 6);  // Ajustar la posición horizontal a 1mm y vertical a 6mm

            // Agregar los demás detalles de la dieta, ajustando las posiciones
            doc.text(`Pac.:${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, 11);  // Ajuste horizontal y vertical
            doc.text(`Dieta: ${dieta.nombre_dieta}`, 1, 16);  // Ajuste horizontal y vertical
            doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, 21);  // Ajuste horizontal y vertical
            doc.text(`Acompañante: ${dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : 'Ninguna'}`, 1, 26);
            // Ajuste horizontal y vertical

            // Mostrar la vista previa del PDF sin descargarlo
            window.open(doc.output('bloburl'), '_blank');  // Abre una nueva ventana con la vista previa
        },
        
        imprimirTodasEtiquetas() {
            if (this.dietas.length === 0) {
                Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
                return;
            }
        
            // Crear un nuevo documento PDF con el tamaño adecuado
            const doc = new jsPDF({
                unit: 'mm',  // Unidades en milímetros
                format: [63, 44],  // Tamaño de página: 63mm x 44mm
                orientation: 'l'  // Landscape
            });
        
            this.dietas.forEach((dieta, index) => {
                // Establecer el color del texto
                doc.setTextColor(0, 0, 0);
        
                // Ajustar la distancia entre líneas
                const lineHeight = 6;  // Puedes modificar este valor para aumentar o reducir el espacio entre las líneas
                let currentY = 6;  // Posición inicial para la primera línea
        
                // Agregar el contenido de cada dieta
                doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                currentY += lineHeight;  // Incrementar la posición Y para la siguiente línea
        
                doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                currentY += lineHeight;
        
                doc.text(`Dieta: ${dieta.nombre_dieta}`, 1, currentY);
                currentY += lineHeight;
        
                doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, currentY);
                currentY += lineHeight;
        
                doc.text(`Acompañante: ${dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : 'Ninguna'}`, 1, currentY);
                currentY += lineHeight;
        
                // Si la dieta tiene acompañante, agregar una nueva página con los datos de la dieta general para el acompañante
                if (dieta.acompaniante === 1) {
                    // Crear una nueva página para los detalles de la dieta general para el acompañante
                    doc.addPage([63, 44], 'l');
                    doc.text('Dieta Gral. Acompañante', 1, 6);  // Título "Dieta General"
                    currentY = 12;  // Ajustar posición Y para los siguientes datos de la dieta general
        
                    // Agregar los mismos datos de la dieta, pero sin el campo "Acompañante"
                    doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                    currentY += lineHeight;
        
                    doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                    currentY += lineHeight;
        
                    doc.text(`Dieta: ${dieta.nombre_dieta}`, 1, currentY);
                    currentY += lineHeight;
        
                    doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, currentY);
                    currentY += lineHeight;
        
                    // Aquí no se imprime el campo "Acompañante"
                }
        
                // Agregar una nueva página para cada etiqueta excepto la última
                if (index < this.dietas.length - 1) {
                    doc.addPage([63, 44], 'l');
                }
            });
        
            // Mostrar la vista previa del PDF con todas las etiquetas
            window.open(doc.output('bloburl'), '_blank');  // Abre la vista previa en una nueva ventana
        },
        
               
        generarPDF() {
            const logoPath = 'img/logo.png';
            const img = new Image();
            img.src = logoPath;
        
            img.onload = () => {
                const doc = new jsPDF();
        
                // Tamaño y posición del logo
                const logoWidth = 20; // Ancho del logo
                const logoHeight = 15; // Alto del logo (ajustado proporcionalmente)
                const logoX = 10; // Posición X del logo
                const logoY = 10; // Posición Y del logo
        
                // Agregar el logo al PDF
                doc.addImage(img, 'PNG', logoX, logoY, logoWidth, logoHeight);
        
                // Título general
                const title = "Listado de dietas por sector";
                doc.setFontSize(14);
        
                // Posición del título (centrado horizontalmente respecto al logo)
                const pageWidth = doc.internal.pageSize.width;
                const textWidth = doc.getTextWidth(title);
                const titleX = logoX + logoWidth + (pageWidth - logoX - logoWidth - textWidth) / 2; // Título a la derecha del logo
                const titleY = logoY + logoHeight / 2 + 4; // Centrado verticalmente respecto al logo
        
                // Agregar el título al PDF
                doc.text(title, titleX, titleY);
        
                let y = logoY + logoHeight + 10; // Posición inicial debajo del logo y título
        
                // Agrupar los pacientes por sector
                const pacientesPorSector = this.pacientesFiltrados.reduce((acc, dieta) => {
                    if (!acc[dieta.nombre_sector]) {
                        acc[dieta.nombre_sector] = [];
                    }
                    acc[dieta.nombre_sector].push(dieta);
                    return acc;
                }, {});
        
                for (const [sector, pacientes] of Object.entries(pacientesPorSector)) {
                    // Agregar el título del sector centrado
                    doc.setFontSize(12);
                    const sectorTextWidth = doc.getTextWidth(sector);
                    const sectorX = (pageWidth - sectorTextWidth) / 2;
                    doc.text(sector, sectorX, y);
                    y += 5;
        
                    // Agregar la tabla con los pacientes del sector
                    doc.autoTable({
                        startY: y,
                        head: [['Apellido', 'Nombre', 'Edad', 'Dieta', 'Observación', 'Fecha Consumo']],
                        body: pacientes.map(dieta => [
                            dieta.apellido_paciente,
                            dieta.nombre_paciente,
                            dieta.edad,
                            dieta.nombre_dieta,
                            dieta.observacion,
                            dieta.fecha_consumo
                        ])
                    });
        
                    // Actualizar la posición Y para evitar solapamiento con la siguiente tabla
                    y = doc.lastAutoTable.finalY + 10;
                }
        
                // Mostrar la vista previa del PDF sin descargarlo
                window.open(doc.output('bloburl'), '_blank'); // Abre una nueva ventana con la vista previa
            };
        
            img.onerror = () => {
                console.error("No se pudo cargar el logo desde la ruta proporcionada.");
            };
        }
        
    }
});

app.mount('#app');
