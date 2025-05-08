const { jsPDF } = window.jspdf;

const app = Vue.createApp({
  data() {
    return {
      recargos: [], // Inicializado como un array vacío
      comidas: [],
      filtro: '',
      comidaSeleccionada: null,
      recargo: {
        id: null,
        nombre: '',
        sector: '',
        comida_id: '',
        observacion: '',
        cantidad: 0,
        controlado: 0,
        nombre_comida: ''
      }
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
        (item.sector?.toLowerCase() || '').includes(filtro) ||
        (item.comida_nombre?.toLowerCase() || '').includes(filtro)
      );
    }
  },
  methods: {
    abrirModalComida() {
      this.obtenerRecargos();

      // Mostrar el modal
      const modal = new bootstrap.Modal(document.getElementById('modalComida'));
      modal.show();
    },
    obtenerComidas() {
      axios.get('api/comidas.php')
        .then(res => this.comidas = res.data)
        .catch(() => Swal.fire('Error', 'No se pudieron cargar las comidas.', 'error'));
    },
    async actualizarControlado(recargo) {
      try {
        const nuevoEstado = recargo.controlado === 1 ? 0 : 1; // Asegura que es un número

        console.log(`Enviando ID: ${recargo.id}, Controlado: ${nuevoEstado}`);

        const response = await axios.post('api/actualizar_controlado_recargo.php', {
          id: recargo.id,
          controlado: nuevoEstado
        }, {
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' } // Asegura la correcta codificación
        });

        //console.log(response.data); // Verifica la respuesta del servidor

        if (response.data.success) {
          recargo.controlado = nuevoEstado; // Actualiza localmente el estado
        } else {
          Swal.fire('Error', response.data.error || 'No se pudo actualizar el control.', 'error');
        }
      } catch (error) {
        console.error("Error actualizando controlado:", error);
        Swal.fire('Error', 'Hubo un problema al actualizar.', 'error');
      }
    },

    async procesarTodoRecargo() {
      if (!this.comidaSeleccionada) {
        Swal.fire('Error', 'Por favor, seleccione una comida.', 'error');
        return;
      }

      try {
        const response = await axios.post('api/consumos_recargos.php', {
          comida_id: this.comidaSeleccionada
        });

        const data = response.data;

        if (data.status === 'success') {
          Swal.fire('Éxito', data.message, 'success');
          this.obtenerRecargos(); // Vuelve a cargar los datos actualizados

          const recargosSinControlar = this.recargos.filter(recargo =>
            recargo.comida_id == this.comidaSeleccionada && recargo.controlado === 0
          );

          this.imprimirTodosRecargos(recargosSinControlar);
        } else {
          Swal.fire('Error', data.message, 'error');
        }
      } catch (error) {
        console.error('Error al registrar el consumo:', error);
        Swal.fire('Error', 'Hubo un error al procesar la solicitud.', 'error');
      }
    },

    validarCantidad(event) {
      this.recargo.cantidad = event.target.value.slice(0, 2);
    },

    obtenerRecargos() {
      fetch('api/recargos.php')
        .then(res => {
          if (!res.ok) throw new Error('Error al cargar los recargos');
          return res.json();
        })
        .then(data => {
          this.recargos = Array.isArray(data) ? data : [];
        })
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
        .then(async res => {
          const data = await res.json();

          if (!res.ok) {
            // Mostrar error específico si viene del servidor
            const mensaje = data.error || 'No se pudo guardar el recargo.';
            Swal.fire('Error', mensaje, 'error');
            return;
          }

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
    // imprimirTodosRecargos() {
    //   if (!this.comidaSeleccionada) {
    //     Swal.fire('Error', 'Por favor, seleccione una comida.', 'error');
    //     return;
    //   }
    //   // Filtrar los recargos que corresponden a la comida seleccionada

    //   const recargosFiltrados = this.recargos.filter(recargo => recargo.comida_id == this.comidaSeleccionada);

    //   if (recargosFiltrados.length === 0) {
    //     Swal.fire('Error', 'No hay recargos disponibles para la comida seleccionada.', 'error');
    //     return;
    //   }

    //   const doc = new jsPDF({ unit: 'mm', format: [63, 44], orientation: 'l' });
    //   const lineHeight = 6;
    //   const pageWidth = 63;
    //   let etiquetasAgregadas = 0;

    //   recargosFiltrados.forEach((recargo, recargoIndex) => {
    //     const cantidad = parseInt(recargo.cantidad) || 0;

    //     for (let i = 0; i < cantidad; i++) {
    //       let currentY = 10;
    //       const nombreComida = this.comidas.find(c => c.id == recargo.comida_id)?.nombre || 'Sin comida';
    //       const titulo = nombreComida.toUpperCase();
    //       const tituloWidth = doc.getTextWidth(titulo);
    //       const centeredX = (pageWidth - tituloWidth) / 2;

    //       doc.setFontSize(12);
    //       doc.setFont('helvetica', 'bold');
    //       doc.text(titulo, centeredX, 5);

    //       doc.setFontSize(20);
    //       doc.setFont('helvetica', 'bold');
    //       doc.text(`${recargo.sector}`, 2, currentY);
    //       currentY += lineHeight;
          
    //       doc.setFontSize(11);
    //       doc.text(`Nombre: ${recargo.nombre}`, 2, currentY);
    //       currentY += lineHeight;
    //       doc.setFontSize(11);


    //       doc.text(`Observacion: ${recargo.observacion}`, 2, currentY);
    //       currentY += lineHeight;
    //       etiquetasAgregadas++;
    //       if (i < cantidad - 1 || recargoIndex < recargosFiltrados.length - 1) {
    //         doc.addPage([63, 44], 'l');
    //       }
    //     }
    //   });

    //   if (etiquetasAgregadas > 0) {
    //     window.open(doc.output('bloburl'), '_blank');
    //   } else {
    //     Swal.fire('Error', 'No hay recargos válidos para imprimir.', 'error');
    //   }
    // },
    imprimirTodosRecargos() {
      if (!this.comidaSeleccionada) {
        Swal.fire('Error', 'Por favor, seleccione una comida.', 'error');
        return;
      }
    
      // Filtrar los recargos que corresponden a la comida seleccionada
      const recargosFiltrados = this.recargos.filter(recargo => recargo.comida_id == this.comidaSeleccionada);
    
      if (recargosFiltrados.length === 0) {
        Swal.fire('Error', 'No hay recargos disponibles para la comida seleccionada.', 'error');
        return;
      }
    
      const doc = new jsPDF({ unit: 'mm', format: [63, 44], orientation: 'l' });
      const lineHeight = 6;
      const pageWidth = 63;
      let etiquetasAgregadas = 0;
    
      recargosFiltrados.forEach((recargo, recargoIndex) => {
        const cantidad = parseInt(recargo.cantidad) || 0;
    
        for (let i = 0; i < cantidad; i++) {
          let currentY = 10;
          const nombreComida = this.comidas.find(c => c.id == recargo.comida_id)?.nombre || 'Sin comida';
          const titulo = nombreComida.toUpperCase();
          const tituloWidth = doc.getTextWidth(titulo);
          const centeredTituloX = (pageWidth - tituloWidth) / 2;
    
          // Título (nombre de la comida)
          doc.setFontSize(12);
          doc.setFont('helvetica', 'bold');
          doc.text(titulo, centeredTituloX, currentY);
          currentY += lineHeight + 2; // Aumento el espacio antes del sector
    
          // Sector centrado y enmarcado
          doc.setFontSize(20);
          doc.setFont('helvetica', 'bold');
          const sectorTexto = `${recargo.sector}`;
          const sectorWidth = doc.getTextWidth(sectorTexto);
          const sectorX = (pageWidth - sectorWidth) / 2;
          const rectPadding = 2;
          const rectX = sectorX - rectPadding;
          const rectY = currentY - 6;
          const rectWidth = sectorWidth + rectPadding * 2;
          const rectHeight = lineHeight + 2;
    
          doc.rect(rectX, rectY, rectWidth, rectHeight); // Dibuja el recuadro
          doc.text(sectorTexto, sectorX, currentY); // Texto centrado dentro del recuadro
          currentY += lineHeight + 4;
    
          // Nombre
          doc.setFontSize(11);
          doc.text(`Nombre: ${recargo.nombre}`, 2, currentY);
          currentY += lineHeight;
    
          // Observación
          doc.text(`Observacion: ${recargo.observacion}`, 2, currentY);
          currentY += lineHeight;
    
          etiquetasAgregadas++;
    
          if (i < cantidad - 1 || recargoIndex < recargosFiltrados.length - 1) {
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
      this.recargo = { id: null, nombre: '', sector: '', comida_id: '', observacion: '', cantidad: '' };
    }
  }
});

app.mount('#app');