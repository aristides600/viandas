const { createApp } = Vue;

createApp({
    data() {
        return {
            internaciones: [],
            filtro: '',
            filtroEstado: 'pendiente', // Filtro por estado (pendiente o cerrada)
        };
    },
    mounted() {
        this.cargarInternaciones();
    },
    watch: {
        filtro() {
            this.cargarInternaciones();
        },
        filtroEstado() {
            this.cargarInternaciones();
        }
    },
    
    methods: {
        cargarInternaciones() {
            console.log("Filtro Estado:", this.filtroEstado);
            const url = `api/internados.php?search=${this.filtro}&estado=${this.filtroEstado}`;
            fetch(url, { method: 'GET' })
                .then(response => response.json())
                .then(data => { this.internaciones = data; })
                .catch(error => console.error(error));
        },
        
        nuevaInternacion() {
            window.location.href = "nueva_internacion.php";
        },
        editarInternacion(id) {
            window.location.href = "editar_internacion.php?id=" + id;
        },
        dietaInternacion(id) {
            window.location.href = "dieta_internacion.php?id=" + id;
        },
        altaInternacion(id) {
            fetch('api/alta_internacion.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', data.success, 'success');
                } else {
                    Swal.fire('Error', data.error || 'Ocurrió un error', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error de conexión', 'error');
                console.error('Error:', error);
            });
        }
    }
}).mount('#app');
