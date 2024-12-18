const { jsPDF } = window.jspdf;

const app = Vue.createApp({
    data() {
        return {
            dietas: [],
            filtro: '',
            comidaSeleccionada: null,
            comidas: [],

            internacionSeleccionada: null, // ID de la internación actual
        };
    },
    mounted() {
        this.cargarDietas();
        this.obtenerComidas();

    },
    computed: {

        pacientesFiltrados() {
            return this.dietas.filter(dieta => {
                const apellido = dieta.apellido_paciente.toLowerCase();
                const dni = dieta.dni ? dieta.dni.toString() : '';
                const nombreSector = dieta.nombre_sector ? dieta.nombre_sector.toLowerCase() : '';

                const filtroLowerCase = this.filtro.toLowerCase();

                return apellido.includes(filtroLowerCase) ||
                    dni.includes(filtroLowerCase) ||
                    nombreSector.includes(filtroLowerCase);
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
        obtenerComidas() {
            axios.get('api/comidas.php')
                .then(response => {
                    this.comidas = response.data;
                })
                .catch(error => {
                    console.error('Error al obtener comidas:', error);
                    this.mensaje = { texto: 'Error al cargar las comidas.', clase: 'alert-danger' };
                });
        },
        // seleccionarComida(internacionId) {
        //     // Guarda el ID de internación del paciente seleccionado (si es necesario)
        //     this.internacionSeleccionada = internacionId;

        //     // Abre el modal para seleccionar la comida
        //     this.abrirModalComida();
        // },
        seleccionarUnaComida(internacionId) {
            // Guarda el ID de internación del paciente seleccionado (si es necesario)
            this.internacionSeleccionada = internacionId;

            // Abre el modal para seleccionar la comida
            this.abrirModalUnaComida();
        },
        abrirModalUnaComida() {
            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalUnaComida'));
            modal.show();
        },

        abrirModalComida() {
            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalComida'));
            modal.show();
        },
        async procesarTodoConsumo() {
            if (!this.comidaSeleccionada) {
                Swal.fire('Error', 'Por favor, seleccione una comida.', 'error');
                return;
            }

            try {
                // Enviar el ID de la comida seleccionada al backend
                const response = await axios.post('api/consumos_diarios.php', {
                    comida_id: this.comidaSeleccionada
                });

                const data = response.data;

                if (data.status === 'success') {
                    Swal.fire('Éxito', data.message, 'success');
                    // Ejecutar la lógica para imprimir todas las etiquetas (si es necesario)
                    this.imprimirTodasEtiquetas();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                console.error('Error al registrar el consumo:', error);
                Swal.fire('Error', 'Hubo un error al procesar la solicitud.', 'error');
            }
        },
        async procesarUnConsumo() {
            if (!this.comidaSeleccionada) {
                Swal.fire('Error', 'Por favor, seleccione una comida.', 'error');
                return;
            }

            try {
                // Enviar el ID de la comida seleccionada y el internacion_id al backend
                console.log(this.internacionSeleccionada);
                const response = await axios.post('api/consumo_individual.php', {
                    comida_id: this.comidaSeleccionada,
                    internacion_id: this.internacionSeleccionada
                });

                const data = response.data;
                console.log(data);

                if (data.status === 'success') {
                    Swal.fire('Éxito', data.message, 'success');
                    // Ejecutar la lógica para imprimir una etiqueta
                    this.imprimirUnaEtiqueta(this.internacionSeleccionada);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                console.error('Error al registrar el consumo:', error);
                Swal.fire('Error', 'Hubo un error al procesar la solicitud.', 'error');
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

        imprimirUnaEtiqueta(internacion_id) {
            // Buscar la dieta correspondiente al internacion_id
            const dieta = this.dietas.find(d => d.internacion_id === internacion_id);
            if (!dieta) {
                Swal.fire('Error', 'No se encontró la dieta para esta internación.', 'error');
                return;
            }

            // Crear un nuevo documento PDF con el tamaño adecuado
            const doc = new jsPDF({
                unit: 'mm',  // Unidades en milímetros
                format: [63, 44],  // Tamaño de página: 63mm x 44mm
                orientation: 'l'  // Landscape
            });

            const comida = this.comidas.find(c => c.id === this.comidaSeleccionada);
            const nombreComida = comida ? comida.nombre : 'No seleccionada';

            // Establecer el color del texto
            doc.setTextColor(0, 0, 0);

            // Ajustar la distancia entre líneas
            const lineHeight = 6;  // Puedes modificar este valor para aumentar o reducir el espacio entre las líneas
            let currentY = 6;  // Posición inicial para la primera línea

            // Centrar el texto de nombreComida
            const pageWidth = 63;  // Ancho de la página en milímetros
            const textWidth = doc.getTextWidth(nombreComida);  // Ancho del texto
            const centeredX = (pageWidth - textWidth) / 2;  // Calcular la posición X centrada

            doc.text(nombreComida, centeredX, currentY); // Texto centrado
            currentY += lineHeight;  // Incrementar la posición Y para la siguiente línea

            // Agregar el contenido de la dieta
            doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Cama: ${dieta.cama}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Dieta: ${dieta.nombre_dieta}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Postre: ${dieta.nombre_postre}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Acompañante: ${dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : 'Ninguna'}`, 1, currentY);
            currentY += lineHeight;

            // Si la dieta tiene acompañante, agregar los datos de la dieta general para el acompañante
            if (dieta.acompaniante === 1) {
                doc.addPage([63, 44], 'l'); // Crear una nueva página para los detalles de la dieta general para el acompañante
                currentY = 6; // Reiniciar la posición Y para la nueva página

                // Agregar el contenido de la dieta para el acompañante
                doc.text(nombreComida, centeredX, currentY); // Texto centrado
                currentY += lineHeight;

                doc.text('Acompañante', 1, currentY); // Título "Acompañante"
                currentY += lineHeight;

                // Agregar los mismos datos de la dieta, pero sin el campo "Acompañante"
                doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Cama: ${dieta.cama}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Dieta General`, 1, currentY); // Título "Dieta General"
                currentY += lineHeight;

                doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, currentY);
                currentY += lineHeight;

                // Aquí no se imprime el campo "Acompañante"
            }

            // Mostrar la vista previa del PDF sin descargarlo
            window.open(doc.output('bloburl'), '_blank');  // Abre una nueva ventana con la vista previa
        },


        // imprimirTodasEtiquetas() {
        //     if (this.dietas.length === 0) {
        //         Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
        //         return;
        //     }

        //     // Crear un nuevo documento PDF con el tamaño adecuado
        //     const doc = new jsPDF({
        //         unit: 'mm',  // Unidades en milímetros
        //         format: [63, 44],  // Tamaño de página: 63mm x 44mm
        //         orientation: 'l'  // Landscape
        //     });

        //     this.dietas.forEach((dieta, index) => {
        //         const comida = this.comidas.find(c => c.id === this.comidaSeleccionada);
        //         const nombreComida = comida ? comida.nombre : 'No seleccionada';

        //         // Establecer el color del texto
        //         doc.setTextColor(0, 0, 0);

        //         // Ajustar la distancia entre líneas
        //         const lineHeight = 6;  // Puedes modificar este valor para aumentar o reducir el espacio entre las líneas
        //         let currentY = 6;  // Posición inicial para la primera línea

        //         // Centrar el texto de nombreComida
        //         const pageWidth = 63;  // Ancho de la página en milímetros
        //         const textWidth = doc.getTextWidth(nombreComida);  // Ancho del texto
        //         const centeredX = (pageWidth - textWidth) / 2;  // Calcular la posición X centrada

        //         doc.text(nombreComida, centeredX, currentY); // Texto centrado
        //         currentY += lineHeight;  // Incrementar la posición Y para la siguiente línea

        //         // Agregar el contenido de cada dieta
        //         doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Cama: ${dieta.cama}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Dieta: ${dieta.nombre_dieta}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Postre: ${dieta.nombre_postre}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Acompañante: ${dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : 'Ninguna'}`, 1, currentY);
        //         currentY += lineHeight;

        //         // Si la dieta tiene acompañante, agregar una nueva página con los datos de la dieta general para el acompañante
        //         if (dieta.acompaniante === 1) {
        //             doc.addPage([63, 44], 'l'); // Crear una nueva página para los detalles de la dieta general para el acompañante
        //             currentY = 6; // Reiniciar la posición Y para la nueva página

        //             // Agregar el contenido de la dieta para el acompañante
        //             doc.text(nombreComida, centeredX, currentY); // Texto centrado
        //             currentY += lineHeight;

        //             doc.text('Acompañante', 1, currentY); // Título "Acompañante"
        //             currentY += lineHeight;

        //             // Agregar los mismos datos de la dieta, pero sin el campo "Acompañante"
        //             doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Cama: ${dieta.cama}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Dieta General`, 1, currentY); // Título "Dieta General"
        //             currentY += lineHeight;

        //             doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, currentY);
        //             currentY += lineHeight;

        //             // Aquí no se imprime el campo "Acompañante"
        //         }

        //         // Agregar una nueva página para cada etiqueta excepto la última
        //         if (index < this.dietas.length - 1) {
        //             doc.addPage([63, 44], 'l');
        //         }
        //     });

        //     // Mostrar la vista previa del PDF con todas las etiquetas
        //     window.open(doc.output('bloburl'), '_blank');  // Abre la vista previa en una nueva ventana
        // },
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
                // Skip dietas with id 2 (No printing for them)
                if (dieta.id === 1) {
                    return;
                }

                const comida = this.comidas.find(c => c.id === this.comidaSeleccionada);
                const nombreComida = comida ? comida.nombre : 'No seleccionada';

                // Establecer el color del texto
                doc.setTextColor(0, 0, 0);

                // Ajustar la distancia entre líneas
                const lineHeight = 6;  // Puedes modificar este valor para aumentar o reducir el espacio entre las líneas
                let currentY = 6;  // Posición inicial para la primera línea

                // Centrar el texto de nombreComida
                const pageWidth = 63;  // Ancho de la página en milímetros
                const textWidth = doc.getTextWidth(nombreComida);  // Ancho del texto
                const centeredX = (pageWidth - textWidth) / 2;  // Calcular la posición X centrada

                doc.text(nombreComida, centeredX, currentY); // Texto centrado
                currentY += lineHeight;  // Incrementar la posición Y para la siguiente línea

                // Agregar el contenido de cada dieta
                doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Cama: ${dieta.cama}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Dieta: ${dieta.nombre_dieta}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Postre: ${dieta.nombre_postre}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Obs.: ${dieta.observacion || 'Ninguna'}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Acompañante: ${dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : 'Ninguna'}`, 1, currentY);
                currentY += lineHeight;

                // Si la dieta tiene acompañante, agregar una nueva página con los datos de la dieta general para el acompañante
                if (dieta.acompaniante === 1) {
                    doc.addPage([63, 44], 'l'); // Crear una nueva página para los detalles de la dieta general para el acompañante
                    currentY = 6; // Reiniciar la posición Y para la nueva página

                    // Agregar el contenido de la dieta para el acompañante
                    doc.text(nombreComida, centeredX, currentY); // Texto centrado
                    currentY += lineHeight;

                    doc.text('Acompañante', 1, currentY); // Título "Acompañante"
                    currentY += lineHeight;

                    // Agregar los mismos datos de la dieta, pero sin el campo "Acompañante"
                    doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                    currentY += lineHeight;

                    doc.text(`Cama: ${dieta.cama}`, 1, currentY);
                    currentY += lineHeight;

                    doc.text(`Pac.: ${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                    currentY += lineHeight;

                    doc.text(`Dieta General`, 1, currentY); // Título "Dieta General"
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


        nutricionPDF() {
            const logoPath = 'img/logo.png';
            const img = new Image();
            img.src = logoPath;

            img.onload = () => {
                const doc = new jsPDF();

                // Tamaño y posición del logo
                const logoWidth = 20;
                const logoHeight = 15;
                const logoX = 10;
                const logoY = 10;

                // Agregar el logo al PDF
                doc.addImage(img, 'PNG', logoX, logoY, logoWidth, logoHeight);

                // Fecha y hora actuales
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString();
                const formattedTime = currentDate.toLocaleTimeString();
                const dateTimeText = `${formattedDate} ${formattedTime}`;

                // Posición de la fecha y hora
                const pageWidth = doc.internal.pageSize.width;
                const dateTimeX = pageWidth - doc.getTextWidth(dateTimeText) - 10;
                const dateTimeY = logoY + logoHeight / 2 + 2;
                doc.setFontSize(10);
                doc.text(dateTimeText, dateTimeX, logoY + logoHeight / 2);

                // Título general
                const title = "Listado de dietas por sector";
                doc.setFontSize(14);
                const textWidth = doc.getTextWidth(title);
                const titleX = (pageWidth - textWidth) / 2;
                const titleY = logoY + logoHeight / 2; // Título en su posición original

                // Agregar el título al PDF
                doc.text(title, titleX, titleY);

                let y = logoY + logoHeight + 5; // Posición inicial ajustada para elementos 0.5 cm más arriba

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
                    y += 3; // Más pegado a la tabla

                    // Agregar la tabla con los pacientes del sector
                    doc.autoTable({
                        startY: y,
                        head: [['Cama', 'Apellido', 'Nombre', 'Edad', 'Dieta', 'Diagnostico', 'Observación']],
                        body: pacientes.map(dieta => [
                            dieta.cama,
                            dieta.apellido_paciente,
                            dieta.nombre_paciente,
                            dieta.edad,
                            dieta.nombre_dieta,
                            dieta.diagnostico,
                            dieta.observacion,
                            // formatFechaConsumo(dieta.fecha_consumo)
                        ])
                    });

                    // Actualizar la posición Y para evitar solapamiento con la siguiente tabla
                    y = doc.lastAutoTable.finalY + 5; // Reduce el espacio entre tablas
                }

                // Mostrar la vista previa del PDF sin descargarlo
                window.open(doc.output('bloburl'), '_blank');
            };

            img.onerror = () => {
                console.error("No se pudo cargar el logo desde la ruta proporcionada.");
            };

        },
        camareroPDF() {
            const logoPath = 'img/logo.png';
            const img = new Image();
            img.src = logoPath;

            img.onload = () => {
                const doc = new jsPDF();

                // Tamaño y posición del logo
                const logoWidth = 20;
                const logoHeight = 15;
                const logoX = 10;
                const logoY = 10;

                // Agregar el logo al PDF
                doc.addImage(img, 'PNG', logoX, logoY, logoWidth, logoHeight);

                // Fecha y hora actuales
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString();
                const formattedTime = currentDate.toLocaleTimeString();
                const dateTimeText = `${formattedDate} ${formattedTime}`;

                // Posición de la fecha y hora
                const pageWidth = doc.internal.pageSize.width;
                const dateTimeX = pageWidth - doc.getTextWidth(dateTimeText) - 10;
                const dateTimeY = logoY + logoHeight / 2 + 2;
                doc.setFontSize(10);
                doc.text(dateTimeText, dateTimeX, logoY + logoHeight / 2);

                // Título general
                const title = "Listado de dietas por sector";
                doc.setFontSize(14);
                const textWidth = doc.getTextWidth(title);
                const titleX = (pageWidth - textWidth) / 2;
                const titleY = logoY + logoHeight / 2; // Título en su posición original

                // Agregar el título al PDF
                doc.text(title, titleX, titleY);

                let y = logoY + logoHeight + 5; // Posición inicial ajustada para elementos 0.5 cm más arriba

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
                    y += 3; // Más pegado a la tabla

                    // Agregar la tabla con los pacientes del sector
                    doc.autoTable({
                        startY: y,
                        head: [['Cama', 'Apellido', 'Nombre', 'Edad', 'Dieta', 'Observación']],
                        body: pacientes.map(dieta => [
                            dieta.cama,
                            dieta.apellido_paciente,
                            dieta.nombre_paciente,
                            dieta.edad,
                            dieta.nombre_dieta,
                            dieta.observacion,
                            // formatFechaConsumo(dieta.fecha_consumo)
                        ])
                    });

                    // Actualizar la posición Y para evitar solapamiento con la siguiente tabla
                    y = doc.lastAutoTable.finalY + 5; // Reduce el espacio entre tablas
                }

                // Mostrar la vista previa del PDF sin descargarlo
                window.open(doc.output('bloburl'), '_blank');
            };

            img.onerror = () => {
                console.error("No se pudo cargar el logo desde la ruta proporcionada.");
            };

        }


    }
});

app.mount('#app');
