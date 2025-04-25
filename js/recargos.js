const { jsPDF } = window.jspdf;

const app = Vue.createApp({
    data() {
        return {
            recargos: [],
            comidas: [],
            filtro: '',
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
            return this.recargos.filter(item =>
                (item.nombre?.toLowerCase() || '').includes(filtro) ||
                (item.sector?.toLowerCase() || '').includes(filtro)
            );
        }
    },
    methods: {
        // Llamar al backend para obtener los recargos y comidas
        async cargarRecargosYComidas() {
            try {
                const recargosResponse = await axios.get('api/recargos.php', { params: { filtro: this.filtro } });
                this.recargos = recargosResponse.data;
                const comidasResponse = await axios.get('comidas.php'); // Suponiendo que este archivo proporciona la lista de comidas
                this.comidas = comidasResponse.data;
            } catch (error) {
                console.error('Error al cargar los recargos y comidas', error);
            }
        },
        obtenerComidas() {
            axios.get('api/comidas.php')
                .then(res => this.comidas = res.data)
                .catch(() => Swal.fire('Error', 'No se pudieron cargar las comidas.', 'error'));
        },
        obtenerRecargos() {
            fetch('api/recargos.php')
                .then(res => res.json())
                .then(data => this.recargos = Array.isArray(data) ? data : [])
                .catch(() => Swal.fire('Error', 'No se pudieron cargar los recargos.', 'error'));
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
        actualizarCantidad(id, nuevaCantidad) {
            fetch(`api/recargos.php?id=${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cantidad: nuevaCantidad })
            })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Actualizado', data.mensaje, 'success');
                    this.obtenerRecargos();
                })
                .catch(() => Swal.fire('Error', 'No se pudo actualizar la cantidad.', 'error'));
        },
        eliminarRecargo(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el recargo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`api/recargos.php?id=${id}`, { method: 'DELETE' })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire('Eliminado', data.mensaje, 'success');
                            this.obtenerRecargos();
                        })
                        .catch(() => Swal.fire('Error', 'No se pudo eliminar.', 'error'));
                }
            });
        },
        imprimirRecargos() {
            if (this.recargos.length === 0) {
                Swal.fire('Error', 'No hay recargos disponibles para imprimir.', 'error');
                return;
            }

            const doc = new jsPDF({ unit: 'mm', format: [63, 44], orientation: 'l' });
            const lineHeight = 6;
            const pageWidth = 63;
            let etiquetasAgregadas = 0;

            this.recargos.forEach((recargo, recargoIndex) => {
                const cantidad = parseInt(recargo.cantidad) || 0;

                for (let i = 0; i < cantidad; i++) {
                    let currentY = 10;
                    const nombreComida = this.comidas.find(c => c.id == recargo.comida_id)?.nombre || 'Sin comida';
                    const titulo = nombreComida.toUpperCase();
                    const tituloWidth = doc.getTextWidth(titulo);
                    const centeredX = (pageWidth - tituloWidth) / 2;

                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'bold');
                    doc.text(titulo, centeredX, 5);

                    doc.setFontSize(11);
                    doc.text(`Sector: ${recargo.sector}`, 2, currentY);
                    currentY += lineHeight;
                    doc.text(`Nombre: ${recargo.nombre}`, 2, currentY);
                    currentY += lineHeight;

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
        reiniciarFormulario() {
            this.recargo = { id: null, nombre: '', sector: '', comida_id: '', cantidad: '' };
        }
    }
});

app.mount('#app');