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
        formatearFecha(fecha) {
            if (!fecha || fecha === '-') return '-'; // Manejar valores nulos, vacíos o "-"

            // Asegurarse de que la fecha sea interpretable por el objeto Date
            const date = new Date(fecha);
            if (isNaN(date)) return '-'; // Si no es válida, retornar "-"

            const dia = date.getDate().toString().padStart(2, '0');
            const mes = (date.getMonth() + 1).toString().padStart(2, '0'); // Los meses son base 0
            const anio = date.getFullYear();

            const horas = date.getHours().toString().padStart(2, '0');
            const minutos = date.getMinutes().toString().padStart(2, '0');
            const segundos = date.getSeconds().toString().padStart(2, '0');

            return `${dia}/${mes}/${anio} ${horas}:${minutos}:${segundos}`;
        },
        detallesInternacion(id) {
            // Lógica para mostrar los detalles de la internación
            console.log(`Mostrando detalles para la internación con ID: ${id}`);
            // Aquí podrías abrir un modal o redirigir a otra página.
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
        // altaInternacion(id) {
        //     fetch('api/alta_internacion.php', {
        //         method: 'PUT',
        //         headers: { 'Content-Type': 'application/json' },
        //         body: JSON.stringify({ id: id })
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             Swal.fire('Éxito', data.success, 'success');
        //         } else {
        //             Swal.fire('Error', data.error || 'Ocurrió un error', 'error');
        //         }
        //     })
        //     .catch(error => {
        //         Swal.fire('Error', 'Error de conexión', 'error');
        //         console.error('Error:', error);
        //     });
        // }
        altaInternacion(id) {
            fetch('api/alta_internacion.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Éxito', data.success, 'success')
                            .then(() => {
                                location.reload();  // Recarga la página
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

    }
}).mount('#app');
