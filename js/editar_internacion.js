const app = Vue.createApp({
    data() {
        return {
            paciente: {
                nombre: '',
                apellido: '',
                dni: '',
                fecha_nacimiento: ''
            },
            internacion: {
                id: null,  // Asegúrate de que el ID esté presente en la internación
                diagnostico: '',
                cama: '',
                fecha_ingreso: '',
                fecha_egreso: null,
                sector_id: null
            },
            sectores: [],
            sectorNombre: ''
        };
    },
    mounted() {
        const id = new URLSearchParams(window.location.search).get('id');
        if (id) {
            this.obtenerInternacion(id);
            console.log("HOLA");
        } else {
            Swal.fire('Error', 'No se pudo obtener el ID de la internación.', 'error');
        }
        this.cargarSectores();
    },
    methods: {
        obtenerInternacion(id) {
            axios.get(`api/obtener_internacion.php?id=${id}`)
                .then(response => {
                    const datos = response.data;
                    this.paciente = {
                        nombre: datos.nombre_paciente,
                        apellido: datos.apellido_paciente,
                        dni: datos.dni_paciente,
                        fecha_nacimiento: datos.fecha_nacimiento
                    };
                    this.internacion = {
                        id: datos.id,  // Asignar el ID de la internación
                        diagnostico: datos.diagnostico,
                        cama: datos.cama,
                        fecha_ingreso: datos.fecha_ingreso,
                        fecha_egreso: datos.fecha_egreso,
                        sector_id: datos.sector_id
                    };
                    this.sectorNombre = datos.nombre_sector;
                    console.log(this.internacion);
                })
                .catch(error => {
                    Swal.fire('Error', `No se pudieron cargar los datos de la internación: ${error.message}`, 'error');
                });
        },
        cargarSectores() {
            axios.get('api/sectores.php')
                .then(response => {
                    this.sectores = response.data;
                })
                .catch(() => {
                    Swal.fire('Error', 'No se pudieron cargar los sectores.', 'error');
                });
        },
        editarInternacion() {
            // Verificar si el ID de la internación está disponible
            console.log(this.internacion.id);
            if (!this.internacion.id) {
                Swal.fire('Error', 'El ID de la internación no está disponible.', 'error');
                return;
            }

            const data = {
                id: this.internacion.id, // Asegúrate de que el ID esté correctamente asignado
                sector_id: this.internacion.sector_id,
                cama: this.internacion.cama,
                diagnostico: this.internacion.diagnostico
            };
            console.log(data)

            // Cambiar la URL a la correcta para el backend
            axios.put(`api/internados.php`, data)
                .then(response => {
                    // Verificar que la respuesta esté bien, y mostrar mensaje de éxito
                    if (response.data.message) {
                        Swal.fire('Éxito', response.data.message, 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo actualizar la internación.', 'error');
                    }
                })
                .catch(error => {
                    // Manejo de errores en caso de que la solicitud falle
                    console.error('Error en el frontend:', error);
                    Swal.fire('Error', `No se pudo actualizar la internación: ${error.response?.data?.error || error.message}`, 'error');
                });
        }


    }
});

app.mount('#app');
