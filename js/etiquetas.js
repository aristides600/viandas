const app = Vue.createApp({
    data() {
        return {
            sectores: [],
            internaciones: [],
            filtro: {
                sector_id: '',
                fecha_desde: '',
                fecha_hasta: ''
            }
        };
    },
    mounted() {
        this.obtenerSectores();
    },
    methods: {
        obtenerSectores() {
            axios.get('api/etiquetas.php?action=obtener_sectores')
                .then(response => {
                    this.sectores = response.data;
                })
                .catch(error => {
                    console.error("Error al obtener los sectores:", error);
                });
        },
        buscarInternaciones() {
            axios.post('api/etiquetas.php?action=buscar_internaciones', this.filtro)
                .then(response => {
                    this.internaciones = response.data;
                })
                .catch(error => {
                    console.error("Error al buscar internaciones:", error);
                });
        },
        imprimirEtiqueta(internacion) {
            const zpl = `
                ^XA
                ^CI28          // Establece la codificación de caracteres UTF-8
                ^MD30           // Aumenta la oscuridad de la impresión
                ^FO290,50^A0N,50,50^FD${internacion.paciente_nombre} ${internacion.paciente_apellido}^FS
                ^FO290,110^A0N,30,30^FD${internacion.dieta_descripcion}^FS
                ^FO290,150^A0N,30,30^FD${internacion.profesional_nombre} ${internacion.profesional_apellido}^FS
                ^FO290,190^A0N,30,30^FD${internacion.observacion}^FS
                ^XZ
            `;

            axios.post('http://192.168.2.21:9100', zpl, {
                headers: {
                    'Content-Type': 'text/plain; charset=UTF-8'
                }
            })
            .then(() => {
                console.log('Etiqueta enviada a la impresora');
            })
            .catch(error => {
                console.error('Error al enviar la etiqueta:', error);
            });
        },
        imprimirTodasEtiquetas() {
            this.internaciones.forEach(internacion => {
                this.imprimirEtiqueta(internacion);
            });
        }
    }
}).mount('#app');