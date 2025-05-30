<?php ?>
<!DOCTYPE html>
<html>
<head>
    <title>Calendario de Ventas</title>
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
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
            background-color: #f7f9fc;
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
    </style>
</head>
<body>

<h2>Seleccione una fecha</h2>

<!--<input type="date" id="fecha" onchange="cargarVentas()">--><!--<input type="date" id="fecha" onchange="cargarVentas()">-->


<div class="input-group">
    <input type="date" id="fecha" onchange="cargarVentas()" placeholder="YYYY-MM-DD">
    <button onclick="seleccionarHoy()">Hoy</button>
</div>

<div class="grid-container" id="resultado">Selecciona una fecha para ver las ventas.</div>
<div id="totalGeneral" class="total-general"></div>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        seleccionarHoy();
    });
    function seleccionarHoy() {
        const hoy = new Date();
        const yyyy = hoy.getFullYear();
        const mm = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd = String(hoy.getDate()).padStart(2, '0');
        const fechaHoy = `${yyyy}-${mm}-${dd}`;

        // Establecer valor en el input
        document.getElementById("fecha").value = fechaHoy;

        // Llamar la función que carga las ventas
        cargarVentas();
    }
    function cargarVentas() {
        const fecha = document.getElementById('fecha').value;
        if (!fecha) return;

        fetch('/controllers/ventas_por_fecha.php?fecha=' + fecha)
            .then(response => response.json())
            .then(data => {
                const contenedor = document.getElementById('resultado');
                if (!data || Object.keys(data.mesas).length === 0) {
                    contenedor.innerHTML = `<p>No hay ventas para <strong>${fecha}</strong>.</p>`;
                    document.getElementById("totalGeneral").innerText ='$0';
                    return;
                }
                console.log(JSON.stringify(data));

                let html = "";

                for (const [mesaId, mesaData] of Object.entries(data.mesas)) {
                    html += `
        <div class="mesa-section">
          <div class="mesa-title">Mesa ${mesaId}</div>
          <table>
            <thead>
              <tr>
                <th>Categoría</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
      `;

                    for (const [categoria, info] of Object.entries(mesaData.categorias)) {
                        html += `
          <tr>
            <td>${categoria}</td>
            <td>${info.cantidad}</td>
            <td>$${info.subtotal}</td>
          </tr>
        `;
                    }

                    html += `
            </tbody>
            <tfoot>
              <tr class="total-mesa">
                <td colspan="2">Total Mesa ${mesaId}</td>
                <td>$${mesaData.total_mesa}</td>
              </tr>
            </tfoot>
          </table>
        </div>
      `;
                }

                contenedor.innerHTML = html;
                document.getElementById("totalGeneral").innerText = `TOTAL GENERAL: $${data.total_general}`;



            });
    }
</script>

</body>
</html>





