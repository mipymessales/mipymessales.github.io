<?php ?>
<!DOCTYPE html>
<html>
<head>
    <script src="assets/js/ventas_tabs.js" defer></script>
    <title>Cierre de Ventas</title>
    <style>
        .fecha-container {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        input[type="date"] {
            appearance: none;         /* Quita el estilo del navegador */
            -webkit-appearance: none; /* Compatibilidad con WebKit */
            -moz-appearance: none;    /* Firefox */
            background: white;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            width: 200px;
            cursor: pointer; /* importante para que todo el input se sienta interactivo */
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        input[type="date"]:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        button {
            padding: 10px 18px;
            font-size: 16px;
            background-color: rgba(10, 207, 151, 0.47);
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        button:hover {
            background-color:  #0acf97;
        }

        #resultado {
            font-family: Arial, sans-serif;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-top: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        @media (max-width: 320px) {
            .grid-container {
                display: grid;
                grid-template-columns: auto;
                gap: 20px;
            }

        }

        .mesa-section {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .mesa-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        .total-mesa {
            font-weight: bold;
            text-align: right;
            background-color: #ecf0f1;
        }

        .total-general {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            margin-top: 30px;
            background-color: #dff0d8;
            padding: 15px;
            border-radius: 5px;
            width: 100%;
        }
        .nav-tabs .nav-link.active{
           color: var(--sidebar-link-active-color);
        }
        form label {
            color: var(--sidebar-link-active-color);
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .facturatable {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px; /* Asegura que tenga un ancho mínimo */
        }

        .facturatable th, .facturatable td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="font-size: 0.875rem !important;">

<div class="mt-4">
    <h2 class="mb-4 text-center">Resumen de Ventas</h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="ventasTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="dia-tab" data-toggle="tab" href="#dia" role="tab" aria-controls="dia" aria-selected="true">Ventas del Día</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="mes-tab" data-toggle="tab" href="#mes" role="tab" aria-controls="mes" aria-selected="false">Ventas del Mes</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content mt-3">
        <!-- Ventas del Día -->
        <div class="tab-pane fade show active" id="dia" role="tabpanel" aria-labelledby="dia-tab">
            <h4>Filtrar por fecha</h4>
            <form id="form-dia" class="mb-3">
                <label>Fecha:</label>
                <input type="date" id="fecha-dia" name="fecha-dia" required>
                <button type="submit" class="btn btn-warning mr-2">Buscar</button>
                <button class="btn btn-success mr-2" onclick="seleccionarHoy()">Hoy</button>
            </form>
            <div id="resultado-dia"></div>
            <canvas id="chart-dia" style="max-width:600px"></canvas>
        </div>

        <!-- Ventas del Mes -->
        <div class="tab-pane fade" id="mes" role="tabpanel" aria-labelledby="mes-tab">
            <h4>Filtrar por mes</h4>
            <form id="form-mes" class="mb-3 form-inline">
                <label for="select-mes" class="mr-2">Mes:</label>
                <select id="select-mes" class="form-control mr-3" required>
                    <option value="">-- Selecciona --</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>

                <label for="select-anio" class="mr-2">Año:</label>
                <select id="select-anio" class="form-control mr-3" required></select>

                <button type="submit" class="btn btn-warning mr-2">Buscar</button>
                <button class="btn btn-success mr-2" onclick="seleccionarEsteMes()">Mes actual</button>
            </form>

            <div id="resultado-mes"></div>
        <!--   <canvas id="chart-mes" style="max-width:600px"></canvas> -->
        </div>


    </div>
</div>


<script src="assets/js/jquery-3.6.0.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->

<script src="assets/js/bootstrap.min.js"></script>



</body>
<script defer>
    function seleccionarHoy() {
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');
        const fechaHoy = `${yyyy}-${mm}-${dd}`;

        // Establecer valor en el input
        document.getElementById("fecha-dia").value = `${yyyy}-${mm}-${dd}`;


        // Llamar la función que carga las ventas
        fetchResumen({ tipo: "dia", fechaHoy }, "resultado-dia", "chart-dia");
    }
    function seleccionarEsteMes() {
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');
        document.getElementById("select-mes").value=mm;
        document.getElementById("select-anio").value=yyyy;

        const desde = `${yyyy}-${mm}-01`;
        const hasta = new Date(yyyy, parseInt(mm), 0).toISOString().split("T")[0];

        fetchResumen({ tipo: "mes", desde, hasta }, "resultado-mes", "chart-mes");
    }
</script>
</html>





