const { createApp } = Vue;

createApp({
    data() {
        return {
            internaciones: [],  // Arreglo para almacenar las internaciones
      
        };
    },
    mounted() {
        // Cargar internaciones, sectores y profesionales al montar la aplicación
        this.cargarInternaciones();
    
    },
    methods: {
       
        cargarInternaciones() {
            fetch('api/internaciones.php', { method: 'GET' })
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
    }
}).mount('#app');
