const { createApp, ref, onMounted, computed } = Vue;

createApp({
  setup() {
    const resultados = ref([]);
    const loading = ref(true);
    const error = ref(null);

    const fetchData = async () => {
      try {
        const response = await fetch('api/totales_recargos.php');
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        resultados.value = await response.json();
      } catch (e) {
        error.value = e.message;
        console.error("Error fetching data:", e);
      } finally {
        loading.value = false;
      }
    };

    const almuerzos = computed(() => {
      return resultados.value.filter(item => item.comida_id === 1);
    });

    const cenas = computed(() => {
      return resultados.value.filter(item => item.comida_id === 2);
    });

    const totalAlmuerzos = computed(() => {
      return almuerzos.value.reduce((sum, almuerzo) => sum + parseInt(almuerzo.total_cantidad), 0);
    });

    const totalCenas = computed(() => {
      return cenas.value.reduce((sum, cena) => sum + parseInt(cena.total_cantidad), 0);
    });

    onMounted(() => {
      fetchData();
    });

    return {
      resultados,
      loading,
      error,
      almuerzos,
      cenas,
      totalAlmuerzos,
      totalCenas
    };
  }
}).mount('#app2');