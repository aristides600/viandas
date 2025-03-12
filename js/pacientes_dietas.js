const { jsPDF } = window.jspdf;

const app = Vue.createApp({
    data() {
        return {
            dietas: [],
            filtro: '',
            comidaSeleccionada: null,
            comidas: [],
            internacionSeleccionada: null, // ID de la internación actual
            intervalId: null, // ID del intervalo

        };
    },
    mounted() {
        // this.cargarDietas();
        // Actualizar datos automáticamente cada 1 segundo
        this.intervalId = setInterval(() => {
            this.cargarDietas();
        }, 1000); // 1000 ms = 1 segundo
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

        async actualizarControlado(dieta) {
            try {
                const nuevoEstado = dieta.controlado === 1 ? 0 : 1; // Asegura que es un número

                console.log(`Enviando ID: ${dieta.id}, Controlado: ${nuevoEstado}`);

                const response = await axios.post('api/actualizar_controlado.php', {
                    id: dieta.id,
                    controlado: nuevoEstado
                }, {
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' } // Asegura la correcta codificación
                });

                console.log(response.data); // Verifica la respuesta del servidor

                if (response.data.success) {
                    dieta.controlado = nuevoEstado; // Actualiza localmente el estado
                } else {
                    Swal.fire('Error', response.data.error || 'No se pudo actualizar el control.', 'error');
                }
            } catch (error) {
                console.error("Error actualizando controlado:", error);
                Swal.fire('Error', 'Hubo un problema al actualizar.', 'error');
            }
        },
        // async destildarTodo() {
        //     this.dietas.forEach(dieta => dieta.controlado = 0); // Usar this.dietas en lugar de this.pacientes

        //     try {
        //         await axios.post('api/destildar_todo.php');
        //     } catch (error) {
        //         console.error('Error al destildar todo:', error);
        //     }
        // },
        async destildarTodo() {
            const confirmacion = await Swal.fire({
                title: "¿Estás seguro?",
                text: "Se destildarán todas las dietas.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, destildar",
                cancelButtonText: "Cancelar"
            });
        
            if (confirmacion.isConfirmed) {
                this.dietas.forEach(dieta => dieta.controlado = 0);
        
                try {
                    await axios.post('api/destildar_todo.php');
                    Swal.fire("Éxito", "Todas las dietas han sido destildadas.", "success");

                } catch (error) {
                    console.error("Error al destildar todo:", error);
                    Swal.fire("Error", "Hubo un problema al destildar.", "error");
                }
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
            this.cargarDietas();

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
        async procesarColacion() {
            try {
                // Enviar solicitud al backend
                const response = await axios.post('api/consumos_colaciones.php');

                const data = response.data;

                if (data.status === 'success') {
                    Swal.fire('Éxito', data.message, 'success');
                    // Lógica para imprimir todas las etiquetas (si es necesario)
                    this.imprimirColaciones();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                console.error('Error al registrar el consumo:', error);
                Swal.fire('Error', 'Hubo un error al procesar la solicitud.', 'error');
            }
        },

        async procesarSuplemento() {
            try {
                // Enviar solicitud al backend
                const response = await axios.post('api/consumos_suplementos.php');

                const data = response.data;

                if (data.status === 'success') {
                    Swal.fire('Éxito', data.message, 'success');
                    // Lógica para imprimir todas las etiquetas (si es necesario)
                    this.imprimirSuplementos();
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
            const dieta = this.dietas.find(d => d.internacion_id === internacion_id);
            if (!dieta) {
                Swal.fire('Error', 'No se encontró la dieta para esta internación.', 'error');
                return;
            }

            const doc = new jsPDF({
                unit: 'mm',
                format: [63, 44],
                orientation: 'l',
            });

            const comida = this.comidas.find(c => c.id === this.comidaSeleccionada);
            const nombreComida = comida ? comida.nombre : 'No seleccionada';

            doc.setFontSize(11);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(0, 0, 0);

            const lineHeight = 5;
            let currentY = 5;

            const pageWidth = 63;
            const textWidth = doc.getTextWidth(nombreComida);
            const centeredX = (pageWidth - textWidth) / 2;

            doc.text(nombreComida, centeredX, currentY);
            currentY += lineHeight;

            const sectorYCama = `Sector: ${dieta.nombre_sector} Cama: ${dieta.cama}`;
            doc.text(sectorYCama, 1, currentY);
            currentY += lineHeight;

            doc.text(`${dieta.apellido_paciente}, ${dieta.nombre_paciente}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Dieta: ${dieta.codigo_dieta} - ${dieta.nombre_dieta}`, 1, currentY);
            currentY += lineHeight;

            doc.text(`Postre: ${dieta.nombre_postre}`, 1, currentY);
            currentY += lineHeight;

            const acompanianteText = dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : 'Ninguna';
            doc.text(`Acompañante: ${acompanianteText}`, 1, currentY);
            currentY += lineHeight;

            doc.setFontSize(8);
            const observacion = dieta.observacion || 'Ninguna';
            const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61);
            observacionLineas.forEach(linea => {
                doc.text(linea, 1, currentY);
                currentY += lineHeight;
            });
            doc.setFontSize(11);

            if (dieta.acompaniante === 1) {
                doc.addPage([63, 44], 'l');
                currentY = 5;

                doc.text(nombreComida, centeredX, currentY);
                currentY += lineHeight;

                doc.text('Acompañante', 1, currentY);
                currentY += lineHeight;

                doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Cama: ${dieta.cama}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Dieta General`, 1, currentY);
                currentY += lineHeight;

                doc.setFontSize(9);
                const observacionLineasAcompaniante = doc.splitTextToSize(`Obs.: ${observacion}`, 61);
                observacionLineasAcompaniante.forEach(linea => {
                    doc.text(linea, 1, currentY);
                    currentY += lineHeight;
                });
                doc.setFontSize(11);
            }

            window.open(doc.output('bloburl'), '_blank');
        },

        // --------------NO IMPRIME ACOMPAÑANTES CON DIETA NADA VIA ORAL---------------------//
        imprimirTodasEtiquetas() {
            if (this.dietas.length === 0) {
                Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
                return;
            }

            const doc = new jsPDF({
                unit: 'mm',
                format: [63, 44],
                orientation: 'l',
            });

            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');

            const dietasValidas = this.dietas.filter(dieta => dieta.dieta_id !== 1);

            if (dietasValidas.length === 0) {
                Swal.fire('Error', 'No hay dietas válidas para imprimir.', 'error');
                return;
            }

            dietasValidas.forEach((dieta, index) => {
                const comida = this.comidas.find(c => c.id === this.comidaSeleccionada);
                const nombreComida = comida ? comida.nombre : 'No seleccionada';

                doc.setTextColor(0, 0, 0);

                const lineHeight = 5;
                let currentY = 5;

                // Centrar el nombre de la comida y la cama en la misma línea
                doc.setFontSize(14); // Cambiar tamaño de fuente a 14
                const textoComidaCama = `${nombreComida} | Cama: ${dieta.cama}`;
                const pageWidth = 63;
                const anchoTextoComidaCama = doc.getTextWidth(textoComidaCama);
                const centroTextoComidaCama = (pageWidth - anchoTextoComidaCama) / 2;
                doc.text(textoComidaCama, centroTextoComidaCama, currentY);
                currentY += lineHeight;

                // Imprimir Sector y Cama en la misma línea
                doc.setFontSize(12); // Restablecer el tamaño de fuente
                const sectorYCama = `Sector: ${dieta.nombre_sector}`;
                doc.text(sectorYCama, 1, currentY);
                currentY += lineHeight;

                // Imprimir otros datos
                doc.text(`${dieta.apellido_paciente}, ${dieta.nombre_paciente}`, 1, currentY);
                currentY += lineHeight;

                doc.setFontSize(11); // Restablecer el tamaño de fuente

                doc.text(`Dieta: ${dieta.codigo_dieta} - ${dieta.nombre_dieta}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Postre: ${dieta.nombre_postre}`, 1, currentY);
                currentY += lineHeight;

                // Imprimir Acompañante después de Postre
                const acompanianteText = dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : 'Ninguna';
                doc.text(`Acompañante: ${acompanianteText}`, 1, currentY);
                currentY += lineHeight;

                // Dividir texto de observación en líneas si excede el ancho
                doc.setFontSize(10);
                const observacion = dieta.observacion || 'Ninguna';
                const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61); // 61mm ancho disponible
                observacionLineas.forEach(linea => {
                    doc.text(linea, 1, currentY);
                    currentY += lineHeight;
                });
                doc.setFontSize(12);

                if (dieta.acompaniante === 1) {
                    doc.addPage([63, 44], 'l');
                    currentY = 5;

                    doc.setFontSize(14);
                    doc.text(textoComidaCama, centroTextoComidaCama, currentY);
                    currentY += lineHeight;

                    doc.setFontSize(12);
                    doc.text('Acompañante', 1, currentY);
                    currentY += lineHeight;

                    doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                    currentY += lineHeight;

                    // doc.text(`Cama: ${dieta.cama}`, 1, currentY);
                    // currentY += lineHeight;

                    doc.text(`${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                    currentY += lineHeight;

                    doc.text(`Dieta General`, 1, currentY);
                    currentY += lineHeight;

                    doc.setFontSize(10);
                    const observacionLineasAcompaniante = doc.splitTextToSize(`Obs.: ${observacion}`, 61);
                    observacionLineasAcompaniante.forEach(linea => {
                        doc.text(linea, 1, currentY);
                        currentY += lineHeight;
                    });
                    doc.setFontSize(12);
                }

                if (index < dietasValidas.length - 1) {
                    doc.addPage([63, 44], 'l');
                }
            });

            window.open(doc.output('bloburl'), '_blank');
        },

        // --------------NO IMPRIME PACIENTES QUE COMEN---------------------//
        // imprimirTodasEtiquetas() {
        //     if (this.dietas.length === 0) {
        //         Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
        //         return;
        //     }

        //     const doc = new jsPDF({
        //         unit: 'mm',
        //         format: [63, 44],
        //         orientation: 'l',
        //     });

        //     doc.setFontSize(11);
        //     doc.setFont('helvetica', 'bold');

        //     // Filtrar dietas válidas
        //     const dietasValidas = this.dietas.filter(
        //         dieta => dieta.dieta_id !== 1 || dieta.acompaniante === 1
        //     );

        //     if (dietasValidas.length === 0) {
        //         Swal.fire('Error', 'No hay dietas válidas para imprimir.', 'error');
        //         return;
        //     }

        //     dietasValidas.forEach((dieta, index) => {
        //         const comida = this.comidas.find(c => c.id === this.comidaSeleccionada);
        //         const nombreComida = comida ? comida.nombre : 'No seleccionada';

        //         doc.setTextColor(0, 0, 0);

        //         const lineHeight = 5;
        //         let currentY = 5;

        //         const pageWidth = 63;

        //         if (dieta.dieta_id !== 1) {
        //             // Imprimir etiqueta normal
        //             const textWidth = doc.getTextWidth(nombreComida);
        //             const centeredX = (pageWidth - textWidth) / 2;

        //             doc.text(nombreComida, centeredX, currentY);
        //             currentY += lineHeight;

        //             const sectorYCama = `Sector: ${dieta.nombre_sector} Cama: ${dieta.cama}`;
        //             doc.text(sectorYCama, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`${dieta.apellido_paciente}, ${dieta.nombre_paciente}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Dieta: ${dieta.codigo_dieta} - ${dieta.nombre_dieta}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Postre: ${dieta.nombre_postre}`, 1, currentY);
        //             currentY += lineHeight;

        //             const observacion = dieta.observacion || 'Ninguna';
        //             const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61);
        //             observacionLineas.forEach(linea => {
        //                 doc.text(linea, 1, currentY);
        //                 currentY += lineHeight;
        //             });
        //         } else if (dieta.acompaniante === 1) {
        //             // Imprimir etiqueta del acompañante
        //             const textWidth = doc.getTextWidth(nombreComida);
        //             const centeredX = (pageWidth - textWidth) / 2;

        //             doc.text(nombreComida, centeredX, currentY);
        //             currentY += lineHeight;

        //             doc.text('Acompañante', 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Cama: ${dieta.cama}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
        //             currentY += lineHeight;

        //             doc.text(`Dieta General`, 1, currentY);
        //             currentY += lineHeight;

        //             const observacion = dieta.observacion || 'Ninguna';
        //             const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61);
        //             observacionLineas.forEach(linea => {
        //                 doc.text(linea, 1, currentY);
        //                 currentY += lineHeight;
        //             });
        //         }

        //         if (index < dietasValidas.length - 1) {
        //             doc.addPage([63, 44], 'l');
        //         }
        //     });

        //     window.open(doc.output('bloburl'), '_blank');
        // },


        // imprimirColaciones() {
        //     if (this.dietas.length === 0) {
        //         Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
        //         return;
        //     }

        //     const doc = new jsPDF({
        //         unit: 'mm',
        //         format: [63, 44],
        //         orientation: 'l',
        //     });

        //     doc.setFontSize(11);
        //     doc.setFont('helvetica', 'bold');

        //     let hasValidDietas = false; // Variable para verificar si hay dietas válidas

        //     // Filtrar solo las dietas válidas
        //     const dietasValidas = this.dietas.filter(
        //         dieta => dieta.id_colacion !== 1 && dieta.id_colacion !== null
        //     );

        //     dietasValidas.forEach((dieta, index) => {
        //         hasValidDietas = true; // Hay al menos una dieta válida

        //         const lineHeight = 6;  // Espacio entre las líneas
        //         let currentY = 10;  // Posición inicial para el contenido (después del título)

        //         // Centrar y agregar el título "COLACIÓN" en la parte superior de cada página
        //         const pageWidth = 63;  // Ancho de la página en milímetros
        //         const titleText = "COLACIÓN";
        //         const titleWidth = doc.getTextWidth(titleText);
        //         const centeredX = (pageWidth - titleWidth) / 2;

        //         doc.text(titleText, centeredX, 5); // Título centrado en Y=5 mm

        //         // Establecer la fuente para todo el texto en Helvetica normal
        //         doc.setFont('helvetica', 'normal');
        //         doc.setFontSize(11);

        //         // Imprimir "Sector" y "Cama" en el mismo renglón
        //         const sectorYCama = `Sector: ${dieta.nombre_sector} Cama: ${dieta.cama}`;
        //         doc.text(sectorYCama, 1, currentY);
        //         currentY += lineHeight;

        //         // Agregar los otros detalles
        //         doc.text(`${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Col.: ${dieta.nombre_colacion}`, 1, currentY);  // Imprimir si nombre_colacion no es null
        //         currentY += lineHeight;

        //         // Establecer el tamaño de fuente para Observación (tamaño 9)
        //         doc.setFontSize(9);
        //         const observacion = dieta.observacion || 'Ninguna';
        //         const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61); // 61mm ancho disponible
        //         observacionLineas.forEach(linea => {
        //             doc.text(linea, 1, currentY);
        //             currentY += lineHeight;
        //         });

        //         // Restablecer el tamaño de fuente para el resto del texto
        //         doc.setFontSize(11);

        //         // Agregar una nueva página solo si no es la última dieta válida
        //         if (index < dietasValidas.length - 1) {
        //             doc.addPage([63, 44], 'l'); // Crear una nueva página
        //             currentY = 10; // Reiniciar la posición Y para la nueva página
        //         }
        //     });

        //     // Solo mostrar la vista previa del PDF si hay dietas válidas
        //     if (hasValidDietas) {
        //         window.open(doc.output('bloburl'), '_blank');  // Abre la vista previa en una nueva ventana
        //     } else {
        //         Swal.fire('Error', 'No hay dietas válidas para imprimir.', 'error');
        //     }
        // },
        imprimirColaciones() {
            if (this.dietas.length === 0) {
                Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
                return;
            }

            const doc = new jsPDF({
                unit: 'mm',
                format: [63, 44],
                orientation: 'l',
            });

            // Configurar fuente en negrita de manera global
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');

            let hasValidDietas = false; // Variable para verificar si hay dietas válidas

            // Filtrar solo las dietas válidas
            const dietasValidas = this.dietas.filter(
                dieta => dieta.id_colacion !== 1 && dieta.id_colacion !== null
            );

            dietasValidas.forEach((dieta, index) => {
                hasValidDietas = true; // Hay al menos una dieta válida

                const lineHeight = 6;  // Espacio entre las líneas
                let currentY = 10;  // Posición inicial para el contenido (después del título)

                // Centrar y agregar el título "COLACIÓN" en la parte superior de cada página
                const pageWidth = 63;  // Ancho de la página en milímetros
                const titleText = `COLACIÓN | Cama: ${dieta.cama}`; // Corregido el uso del template literal
                const titleWidth = doc.getTextWidth(titleText);
                const centeredX = (pageWidth - titleWidth) / 2;

                doc.text(titleText, centeredX, 5); // Título centrado en Y=5 mm

                // Imprimir "Sector"
                doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                currentY += lineHeight;

                // Imprimir nombre del paciente
                doc.text(`${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                currentY += lineHeight;

                // Imprimir "Col." y su valor
                doc.text(`Col.: ${dieta.nombre_colacion}`, 1, currentY);
                currentY += lineHeight;

                // Imprimir "Obs." y su valor (observación)
                doc.setFontSize(10); // Reducir tamaño de fuente para observaciones
                const observacion = dieta.observacion || 'Ninguna';
                const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61); // Ancho disponible
                observacionLineas.forEach(linea => {
                    doc.text(linea, 1, currentY);
                    currentY += lineHeight;
                });

                doc.setFontSize(12); // Restablecer tamaño de fuente para el resto del texto

                // Agregar una nueva página solo si no es la última dieta válida
                if (index < dietasValidas.length - 1) {
                    doc.addPage([63, 44], 'l'); // Crear una nueva página
                    currentY = 10; // Reiniciar la posición Y para la nueva página
                }
            });

            // Solo mostrar la vista previa del PDF si hay dietas válidas
            if (hasValidDietas) {
                window.open(doc.output('bloburl'), '_blank');  // Abre la vista previa en una nueva ventana
            } else {
                Swal.fire('Error', 'No hay dietas válidas para imprimir.', 'error');
            }
        },


        // imprimirSuplementos() {
        //     if (this.dietas.length === 0) {
        //         Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
        //         return;
        //     }

        //     // Crear un nuevo documento PDF con el tamaño adecuado
        //     const doc = new jsPDF({
        //         unit: 'mm',  // Unidades en milímetros
        //         format: [63, 44],  // Tamaño de página: 63mm x 44mm
        //         orientation: 'l'  // Horizontal
        //     });

        //     let hasValidDietas = false; // Bandera para verificar si hay dietas válidas

        //     this.dietas.forEach((dieta, index) => {
        //         // Omitir dietas con id_suplemento = 1 o id_suplemento igual a null
        //         if (dieta.id_suplemento === 1 || dieta.id_suplemento === null) {
        //             return;
        //         }

        //         hasValidDietas = true; // Hay al menos una dieta válida

        //         // Ajustar la distancia entre líneas
        //         const lineHeight = 6;  // Espacio entre líneas
        //         let currentY = 12;  // Posición inicial para el contenido
        //         const pageWidth = 63;  // Ancho de la página
        //         const title = "SUPLEMENTO";

        //         // Centrar el título
        //         doc.setFontSize(12);
        //         const titleWidth = doc.getTextWidth(title);
        //         const centeredX = (pageWidth - titleWidth) / 2;

        //         // Agregar título en la parte superior
        //         doc.text(title, centeredX, 6);

        //         // Establecer la fuente para todo el texto en Helvetica normal
        //         doc.setFont('helvetica', 'normal');
        //         doc.setFontSize(11);

        //         // Imprimir "Sector" y "Cama" en el mismo renglón
        //         const sectorYCama = `Sector: ${dieta.nombre_sector} Cama: ${dieta.cama}`;
        //         doc.text(sectorYCama, 1, currentY);
        //         currentY += lineHeight;

        //         // Agregar los detalles de la dieta
        //         doc.text(`${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
        //         currentY += lineHeight;

        //         doc.text(`Sup.: ${dieta.nombre_suplemento}`, 1, currentY);
        //         currentY += lineHeight;

        //         // Establecer el tamaño de fuente para Observación (tamaño 9)
        //         doc.setFontSize(9);
        //         const observacion = dieta.observacion || 'Ninguna';
        //         const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61); // 61mm ancho disponible
        //         observacionLineas.forEach(linea => {
        //             doc.text(linea, 1, currentY);
        //             currentY += lineHeight;
        //         });

        //         // Restablecer el tamaño de fuente para el resto del texto
        //         doc.setFontSize(12);

        //         // Si no es la última dieta válida, agregar una nueva página
        //         if (index < this.dietas.length - 1) {
        //             // Buscar próximas dietas válidas
        //             const hasMoreValidDietas = this.dietas.slice(index + 1).some(
        //                 (nextDieta) => nextDieta.id_suplemento !== 1 && nextDieta.id_suplemento !== null
        //             );

        //             if (hasMoreValidDietas) {
        //                 doc.addPage([63, 44], 'l'); // Crear una nueva página solo si hay más dietas válidas
        //             }
        //         }
        //     });

        //     // Solo mostrar el PDF si hay dietas válidas
        //     if (hasValidDietas) {
        //         window.open(doc.output('bloburl'), '_blank'); // Abre la vista previa en una nueva ventana
        //     } else {
        //         Swal.fire('Error', 'No hay dietas válidas para imprimir.', 'error');
        //     }
        // },
        imprimirSuplementos() {
            if (this.dietas.length === 0) {
                Swal.fire('Error', 'No hay dietas disponibles para imprimir.', 'error');
                return;
            }

            // Crear un nuevo documento PDF con el tamaño adecuado
            const doc = new jsPDF({
                unit: 'mm',  // Unidades en milímetros
                format: [63, 44],  // Tamaño de página: 63mm x 44mm
                orientation: 'l'  // Horizontal
            });

            let hasValidDietas = false; // Bandera para verificar si hay dietas válidas

            // Filtrar solo las dietas válidas (id_suplemento distinto de 1 y no null)
            const dietasValidas = this.dietas.filter(
                dieta => dieta.id_suplemento !== 1 && dieta.id_suplemento !== null
            );

            dietasValidas.forEach((dieta, index) => {
                hasValidDietas = true; // Hay al menos una dieta válida

                // Configuración de espacio y posición
                const lineHeight = 6;  // Espacio entre líneas
                let currentY = 12;  // Posición inicial para el contenido
                const pageWidth = 63;  // Ancho de la página

                // Título "SUPLEMENTO" con Cama
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                const title = `SUPLEMENTO | Cama: ${dieta.cama}`;
                const titleWidth = doc.getTextWidth(title);
                const centeredX = (pageWidth - titleWidth) / 2;
                doc.text(title, centeredX, 6);

                // Detalles del suplemento
                doc.setFont('helvetica', 'bold');
                doc.setFontSize(12);
                doc.text(`Sector: ${dieta.nombre_sector}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`${dieta.apellido_paciente} ${dieta.nombre_paciente}`, 1, currentY);
                currentY += lineHeight;

                doc.text(`Sup.: ${dieta.nombre_suplemento}`, 1, currentY);
                currentY += lineHeight;

                // Observación
                doc.setFontSize(10);
                const observacion = dieta.observacion || 'Ninguna';
                const observacionLineas = doc.splitTextToSize(`Obs.: ${observacion}`, 61); // Ancho disponible 61mm
                observacionLineas.forEach(linea => {
                    doc.text(linea, 1, currentY);
                    currentY += lineHeight;
                });

                // Restablecer tamaño de fuente para el resto del texto
                doc.setFontSize(12);

                // Agregar nueva página si no es la última dieta válida
                if (index < dietasValidas.length - 1) {
                    doc.addPage([63, 44], 'l'); // Crear nueva página
                }
            });

            // Mostrar el PDF solo si hay dietas válidas
            if (hasValidDietas) {
                window.open(doc.output('bloburl'), '_blank'); // Abrir en nueva ventana
            } else {
                Swal.fire('Error', 'No hay dietas válidas para imprimir.', 'error');
            }
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
                const logoX = 14;
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
                doc.setFontSize(10);
                doc.text(dateTimeText, dateTimeX, logoY + logoHeight / 2);

                // Título general
                const title = "Listado de dietas por sector";
                doc.setFontSize(14);
                const titleX = (pageWidth - doc.getTextWidth(title)) / 2;
                const titleY = logoY + logoHeight / 2;

                // Agregar el título al PDF
                doc.text(title, titleX, titleY);

                let y = logoY + logoHeight + 5; // Posición inicial ajustada para elementos

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
                        head: [['Cama', 'Apellido', 'Nombre', 'Edad', 'Ac.', 'Dieta', 'Diagnóstico', 'Observación']],
                        body: pacientes.map(dieta => [
                            dieta.cama,
                            dieta.apellido_paciente,
                            dieta.nombre_paciente,
                            dieta.edad,
                            dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : '-',
                            dieta.nombre_dieta,
                            dieta.diagnostico,
                            dieta.observacion
                        ]),
                        headStyles: {
                            fontSize: 10, // Tamaño de fuente más pequeño para evitar quiebres
                            cellPadding: 2 // Menos padding para más espacio
                        },
                        columnStyles: {
                            0: { cellWidth: 15 }, // Ancho fijo para la columna 'Cama'
                            3: { cellWidth: 15 }, // Ancho fijo para la columna 'Edad'
                        }
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
                const logoX = 14;
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
                        head: [['Cama', 'Apellido', 'Nombre', 'Ac.', 'Cod.', 'Dieta', 'Colación', 'Suplemento', 'Mensaje']],
                        body: pacientes.map(dieta => [
                            dieta.cama,
                            dieta.apellido_paciente,
                            dieta.nombre_paciente,
                            dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : '-',

                            // dieta.edad,
                            dieta.codigo_dieta,
                            dieta.nombre_dieta,
                            dieta.nombre_colacion,
                            dieta.nombre_suplemento,
                            dieta.mensaje,
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
