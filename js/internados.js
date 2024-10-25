const { createApp } = Vue;

createApp({
    data() {
        return {
            internaciones: [],  // Arreglo para almacenar las internaciones
            filtro: '' // Valor del filtro para la búsqueda
        };
    },
    mounted() {
        // Cargar internaciones al montar la aplicación
        this.cargarInternaciones();
    },
    watch: {
        // Verificar cambios en el filtro para actualizar la lista de internaciones
        filtro() {
            this.cargarInternaciones();
        }
    },
    methods: {
        cargarInternaciones() {
            const url = `api/internados.php?search=${this.filtro}`;
            fetch(url, { method: 'GET' })
                .then(response => response.json())
                .then(data => { this.internaciones = data; })
                .catch(error => console.error(error));
        },
       
        nuevaInternacion() {
            window.location.href = "nueva_internacion.php";
        },
        dietaInternacion(id) {
            window.location.href = "dieta_internacion.php?id=" + id;
        },
    }
}).mount('#app');
