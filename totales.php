<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Totales de Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="container mt-5">
        <h1 class="mb-4">Totales de Dietas por Comida</h1>

        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-for="(sector, index) in sectores" :key="index" class="sector-card">
            <h4>{{ sector.sector }}</h4>
            <div class="totales">
                <h5>Totales de Almuerzo</h5>
                <ul>
                    <li v-for="(dieta, index) in sector.totales_almuerzo" :key="index">
                        {{ dieta.dieta }}: {{ dieta.total }} unidades
                    </li>
                </ul>

                <h5>Totales de Cena</h5>
                <ul>
                    <li v-for="(dieta, index) in sector.totales_cena" :key="index">
                        {{ dieta.dieta }}: {{ dieta.total }} unidades
                    </li>
                </ul>

                <h5>Totales Generales</h5>
                <p>Almuerzo: {{ sector.general_almuerzo }} unidades</p>
                <p>Cena: {{ sector.general_cena }} unidades</p>
            </div>
        </div>

        <div v-if="totalesGenerales.length > 0">
            <h3>Totales Generales</h3>
            <ul>
                <li v-for="(total, index) in totalesGenerales" :key="index">
                    {{ total.dieta }} - {{ total.comida_id === 1 ? 'Almuerzo' : 'Cena' }}: {{ total.total }} unidades
                </li>
            </ul>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/totales.js"></script>
</body>

</html>
