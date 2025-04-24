const { jsPDF } = window.jspdf;
const app = Vue.createApp({
    data() {
        return {
            recargos: [], // ✅ asegurado como array
            comidas: [],
            filtro: '',   // ✅ corrección de error anterior
            recargo: { id: null, nombre: '', sector: '', comida_id: '', cantidad: '' }
        };
    },
    mounted() {
        this.obtenerRecargos();
        this.obtenerComidas();
    },
    computed: {
        recargosFiltrados() {
            const filtro = this.filtro.toLowerCase();
            return this.recargos.filter(item => {
                const nombre = item.nombre?.toLowerCase() || '';
                const sector = item.sector?.toLowerCase() || '';
                return nombre.includes(filtro) || sector.includes(filtro);
            });
        }
    },
    
    methods: {
        obtenerComidas() {
            axios.get('api/comidas.php')
                .then(res => this.comidas = res.data)
                .catch(() => Swal.fire('Error', 'No se pudieron cargar las comidas.', 'error'));
        },
        obtenerRecargos() {
            fetch('api/recargos.php')
                .then(res => res.json())
                .then(data => {
                    this.recargos = Array.isArray(data) ? data : []; // ✅ asegurar array
                })
                .catch(() => {
                    this.recargos = []; // ✅ evitar errores si falla
                    Swal.fire('Error', 'No se pudieron cargar los recargos.', 'error');
                });
        },
        
        guardarRecargo() {
            const metodo = this.recargo.id ? 'PUT' : 'POST';
            const url = 'api/recargos.php' + (this.recargo.id ? `?id=${this.recargo.id}` : '');
            const datos = { ...this.recargo };

            fetch(url, {
                method: metodo,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire('Éxito', data.mensaje, 'success');
                this.obtenerRecargos();
                this.reiniciarFormulario();
            })
            .catch(() => Swal.fire('Error', 'No se pudo guardar el recargo.', 'error'));
        },
        editarRecargo(item) {
            this.recargo = { ...item };
        },
        async procesarRecargo() {
            try {
                // Enviar solicitud al backend
                const response = await axios.post('api/consumos_recargos.php');

                const data = response.data;

                if (data.status === 'success') {
                    Swal.fire('Éxito', data.message, 'success');
                    // Lógica para imprimir todas las etiquetas (si es necesario)
                    this.imprimirRecargos();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                console.error('Error al registrar el consumo:', error);
                Swal.fire('Error', 'Hubo un error al procesar la solicitud.', 'error');
            }
        },
        imprimirRecargos() {
            if (this.recargos.length === 0) {
                Swal.fire('Error', 'No hay recargos disponibles para imprimir.', 'error');
                return;
            }
        
            const doc = new jsPDF({
                unit: 'mm',
                format: [63, 44],
                orientation: 'l',
            });
        
            const lineHeight = 6;
            const pageWidth = 63;
            let etiquetasAgregadas = 0;
        
            this.recargos.forEach((recargo, recargoIndex) => {
                const cantidad = parseInt(recargo.cantidad) || 0;
        
                for (let i = 0; i < cantidad; i++) {
                    let currentY = 10;
        
                    // Título: nombre de la comida
                    const nombreComida = this.comidas.find(c => c.id == recargo.comida_id)?.nombre || 'Sin comida';
                    const titulo = nombreComida.toUpperCase();
                    const tituloWidth = doc.getTextWidth(titulo);
                    const centeredX = (pageWidth - tituloWidth) / 2;
        
                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'bold');
                    doc.text(titulo, centeredX, 5);
        
                    // Texto: Sector
                    doc.setFontSize(11);
                    doc.text(`Sector: ${recargo.sector}`, 2, currentY);
                    currentY += lineHeight;
        
                    // Texto: Nombre
                    doc.text(`Nombre: ${recargo.nombre}`, 2, currentY);
                    currentY += lineHeight;
        
                    // Si hay más etiquetas por imprimir, agregar página nueva
                    etiquetasAgregadas++;
                    if (i < cantidad - 1 || recargoIndex < this.recargos.length - 1) {
                        doc.addPage([63, 44], 'l');
                    }
                }
            });
        
            if (etiquetasAgregadas > 0) {
                window.open(doc.output('bloburl'), '_blank');
            } else {
                Swal.fire('Error', 'No hay recargos válidos para imprimir.', 'error');
            }
        },
        
        eliminarRecargo(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción desactivará el recargo.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`api/recargos.php?id=${id}`, { method: 'DELETE' })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire('Éxito', data.mensaje, 'success');
                            this.obtenerRecargos();
                        })
                        .catch(() => Swal.fire('Error', 'No se pudo eliminar el recargo.', 'error'));
                }
            });
        },
        actualizarCantidad(id, nuevaCantidad) {
            const recargo = this.recargos.find(r => r.id === id);
            if (recargo) {
                recargo.cantidad = nuevaCantidad;
                this.recargo = { ...recargo };
                this.guardarRecargo();
            }
        },
        reiniciarFormulario() {
            this.recargo = { id: null, nombre: '', sector: '', comida_id: '', cantidad: '' };
        }
    }
});
app.mount('#app');
