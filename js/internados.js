// Archivo: internados.js
const { createApp } = Vue;

createApp({
    data() {
        return {
            internaciones: [],
            filtro: ''
        };
    },
    mounted() {
        this.cargarInternaciones();
    },
    watch: {
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
        darAlta(id) {
            const url = `api/internados.php`;
            const payload = { id: id, accion: "alta" };
            fetch(url, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                this.cargarInternaciones();
            })
            .catch(error => console.error(error));
        }
    }
}).mount('#app');
