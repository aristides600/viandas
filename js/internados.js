const { createApp } = Vue;

createApp({
    data() {
        return {
            internaciones: [],
            filtro: '',
            filtroEstado: 'pendiente', // Filtro por estado (pendiente o cerrada)
            filtroRevisado: 'todas',  // Filtro por revisado (todas, revisadas, no revisadas)
            intervalId: null, // ID del intervalo
        };
    },
    mounted() {
        // this.cargarInternaciones();
        this.intervalId = setInterval(() => {
            this.cargarInternaciones();
        }, 1000); // 1000 ms = 1 segundo
        this.inicializarTooltips();
    },
    watch: {
        filtro() {
            this.cargarInternaciones();
        },
        filtroEstado() {
            this.cargarInternaciones();
        },
        filtroRevisado() {
            this.cargarInternaciones();
        },
    },
    methods: {
        cargarInternaciones() {
            console.log("Cargando internaciones con filtros:", {
                filtro: this.filtro,
                estado: this.filtroEstado,
                revisado: this.filtroRevisado,
            });
            const url = `api/internados.php?search=${this.filtro}&estado=${this.filtroEstado}&revisado=${this.filtroRevisado}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    this.internaciones = data;
                })
                .catch(error => console.error("Error al cargar internaciones:", error));
        },
        formatearFecha(fecha) {
            if (!fecha || fecha === '-') return '-';
            const date = new Date(fecha);
            if (isNaN(date)) return '-';
            const dia = date.getDate().toString().padStart(2, '0');
            const mes = (date.getMonth() + 1).toString().padStart(2, '0');
            const anio = date.getFullYear();
            const horas = date.getHours().toString().padStart(2, '0');
            const minutos = date.getMinutes().toString().padStart(2, '0');
            const segundos = date.getSeconds().toString().padStart(2, '0');
            return `${dia}/${mes}/${anio} ${horas}:${minutos}:${segundos}`;
        },
        detallesInternacion(id) {
            console.log(`Mostrando detalles para la internación con ID: ${id}`);
        },
        marcarRevisado(id, revisado) {
            const url = 'api/marcar_revisado.php';
            const data = { id: id, revisado: revisado ? 1 : 0 };

            fetch(url, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Éxito', 'Internación marcada como revisada.', 'success');
                        this.cargarInternaciones();
                    } else {
                        Swal.fire('Error', data.error || 'Ocurrió un error', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Error de conexión', 'error');
                    console.error('Error:', error);
                });
        },
        nuevaInternacion() {
            window.location.href = "nueva_internacion.php";
        },
        editarInternacion(id) {
            window.location.href = `editar_internacion.php?id=${id}`;
        },
        dietaInternacion(id) {
            window.location.href = `dieta_internacion.php?id=${id}`;
        },
        altaInternacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('api/alta_internacion.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Éxito', data.success, 'success').then(() => {
                                    this.cargarInternaciones();
                                });
                            } else {
                                Swal.fire('Error', data.error || 'Ocurrió un error', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Error de conexión', 'error');
                            console.error('Error:', error);
                        });
                }
            });
        },
        inicializarTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        },
    },
}).mount('#app');
