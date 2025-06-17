let chartDia, chartMes;
//console.log("Cargar ventas.js");
document.getElementById("form-dia").addEventListener("submit", e => {
    e.preventDefault();
    const fecha = document.getElementById("fecha-dia").value;
    fetchResumen({ tipo: "dia", fecha }, "resultado-dia", "chart-dia");
});

// Llenar el select de años (últimos 10 años)
const anioActual = new Date().getFullYear();
const selectAnio = document.getElementById("select-anio");
for (let i = anioActual; i >= anioActual - 10; i--) {
    const option = document.createElement("option");
    option.value = i;
    option.text = i;
    selectAnio.appendChild(option);
}

// Evento de envío del formulario
document.getElementById("form-mes").addEventListener("submit", e => {
    e.preventDefault();

    const mes = document.getElementById("select-mes").value;
    const anio = document.getElementById("select-anio").value;

    if (!mes || !anio) {
        alert("Selecciona mes y año.");
        return;
    }

    const desde = `${anio}-${mes}-01`;
    const hasta = new Date(anio, parseInt(mes), 0).toISOString().split("T")[0];

    fetchResumen({ tipo: "mes", desde, hasta }, "resultado-mes", "chart-mes");
});


function fetchResumen(payload, contID, chartID) {
    fetch("controllers/cierres_por_fecha.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
        .then(r => r.json())
        .then(res => {
            renderTabla(res.data, contID, payload.tipo, res.gastos);
           // renderChart(res.data, chartID, payload.tipo);
        });
}
function capitalizarCadaPalabra(frase) {
    return frase
        .toLowerCase()
        .split(" ")
        .map(p => p.charAt(0).toUpperCase() + p.slice(1))
        .join(" ");
}
function renderTabla(data, contID, tipo, gastos = []) {
    let html = `<h5>Ventas ${tipo === "dia" ? "del Día" : "del Mes"}</h5>`;
    let total = 0, totalGanancia = 0;

    for (let cat in data) {
        html += `<h6 class="mb-0" style="background: white; padding: 10px;">Categoría: ${capitalizarCadaPalabra(cat)}</h6>
        <div class="table-responsive">
        <table class="table table-striped table-sm facturatable" style=" overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* scroll más suave en iOS */
            scrollbar-width: thin;
            width: 100%;
            position: relative;
            z-index: 1;">
        <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio Compra</th><th>Precio Venta</th><th>Subtotal</th><th>Ganancia</th></tr></thead><tbody style="background: white;">`;

        let subtotalCat = 0, gananciaCat = 0;

        data[cat].forEach(p => {
            const cant = tipo === "dia" ? p.venta_dia : p.venta_mes;
            if (cant <= 0) return;

            const trans = (p.transferencia === 1 || p.transferencia === "1") ? "Transferencia" : "Efectivo";
            const sub = cant * p.precio_unitario;
            const ganancia = cant * (p.precio_unitario - p.precio_compra);

            subtotalCat += sub;
            gananciaCat += ganancia;
            total += sub;
            totalGanancia += ganancia;

            html += `<tr>
                <td>${p.nombre}</td>
                <td>${cant}</td>
                  <td>$${p.precio_compra.toFixed(2)}</td>
                <td>$${p.precio_unitario.toFixed(2)} (${trans})</td>
                
                <td>$${sub.toFixed(2)}</td>
                <td class="text-success fw-bold">$${ganancia.toFixed(2)}</td>
            </tr>`;
        });

        html += `<tr class="fw-bold">
            <td colspan="4" class="text-end">Subtotal <em>${cat}</em>:</td>
            <td>$${subtotalCat.toFixed(2)}</td>
            <td class="text-success">$${gananciaCat.toFixed(2)}</td>
        </tr></tbody></table></div>`;
    }

    html += `<h6 class="text-end" style="background: white; padding: 10px;">
        Total ${tipo === "dia" ? "del Día" : "del Mes"}: $${total.toFixed(2)} &nbsp;&nbsp; | &nbsp;&nbsp;
        <span class="text-success">Ganancia: $${totalGanancia.toFixed(2)}</span>
    </h6>`;
 //console.log("data: "+JSON.stringify(data));
    if (gastos.length > 0 && !esNuloOVacio(data)) {
        if (tipo === "dia"){
                html += `<h5 class="mt-4">Gastos</h5>
 
        <table class="table table-bordered table-sm">
            <thead><tr><th>Seleccionar</th><th>Fecha</th><th>Concepto</th><th>Monto</th></tr></thead><tbody style="background: white;">`;
            }else{
                html += `<h5 class="mt-4">Gastos</h5>
<div class="table-responsive">
        <table class="table table-bordered table-sm facturatable">
            <thead><tr><th>Seleccionar</th><th>Cantidad del gasto en el mes</th><th>Concepto</th><th>Monto</th></tr></thead><tbody style="background: white;">`;
            }
            let totalGastos = 0;
            const hoy = tipo === "dia" ? document.getElementById("fecha-dia").value : null;

            gastos.forEach((g) => {
                //const isHoy = hoy && g.fecha === hoy;
                const checked = g.aplicado ? "checked" : "";
                if (tipo === "dia"){
                    if (checked) totalGastos += parseFloat(g.monto);
                }else{
                    if (checked) totalGastos += (parseFloat(g.monto)*parseInt(g.cantmes));
                }

                if (tipo === "dia"){
                    html += `<tr>
                <td><input type="checkbox" class="gasto-check" data-monto="${g.monto}" ${checked} /></td>
                <td> ${g.fecha}</td>
                <td>${g.concepto}</td>
                <td>$${parseFloat(g.monto).toFixed(2)}</td>
            </tr>`;
                }else{
                    html += `<tr>
                <td><input type="checkbox" class="gasto-check" data-monto="${g.monto}" ${checked} /></td>
                <td> ${g.cantmes}</td>
                <td>${g.concepto}</td>
                <td>$${parseFloat(g.monto).toFixed(2)}</td>
            </tr>`;
                }

            });

            html += `</tbody></table> </div>`;
        if (tipo === "dia") {
            html += `<div class="text-end mt-2" style="text-align: end;margin-bottom: .5rem;">
    <button class="btn btn-success btn-sm" id="guardar-gastos">Guardar Selección</button>
</div>`;
        }
            html += `<h6 class="text-end" style="background:white;padding:10px;">
            <span class="text-danger">Total Gastos Seleccionados: $<span id="gasto-total">${totalGastos.toFixed(2)}</span></span><br/>
            <span class="text-primary">Ganancia Real: $<span id="ganancia-real">${(totalGanancia - totalGastos).toFixed(2)}</span></span>
        </h6>`;



    }

    document.getElementById(contID).innerHTML = html;

    // Listeners para los checkboxes de gastos
    document.querySelectorAll(".gasto-check").forEach(input => {
        input.addEventListener("change", () => {
            let sum = 0;
            document.querySelectorAll(".gasto-check:checked").forEach(i => {
                sum += parseFloat(i.dataset.monto);
            });
            document.getElementById("gasto-total").innerText = sum.toFixed(2);
            document.getElementById("ganancia-real").innerText = (totalGanancia - sum).toFixed(2);
        });
    });

    document.addEventListener("click", function (e) {
        if (e.target && e.target.id === "guardar-gastos") {
            const checks = document.querySelectorAll(".gasto-check");
            const gastosSeleccionados = [];

            checks.forEach((chk) => {
                const row = chk.closest("tr");
                const fecha = row.children[1].textContent;
                const concepto = row.children[2].textContent;
                const monto = parseFloat(row.children[3].textContent.replace("$", ""));
                gastosSeleccionados.push({
                    fecha,
                    concepto,
                    monto,
                    aplicado: chk.checked
                });
            });

            fetch("controllers/guardar_gastos_aplicados.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ fecha: document.getElementById("fecha-dia").value, gastos: gastosSeleccionados })
            })
                .then(r => r.json())
                .then(res => {

                    if (res["success"]) {
                        Swal.fire("✅ "+res.message, "", "success");
                    }else{
                        Swal.fire("❌ "+res.message, "", "error");
                    }
                    //alert(res.message || "Actualización completada.");
                });
        }
    });


    // document.getElementById(contID).innerHTML = html;
}

/*
function renderChart(data, chartID, tipo) {
    const labels = [], values = [];

    for (let cat in data) {
        data[cat].forEach(p => {
            const cant = tipo === "dia" ? p.venta_dia : p.venta_mes;
            if (!labels.includes(p.nombre)) {
                labels.push(p.nombre);
                values.push(cant);
            } else {
                const idx = labels.indexOf(p.nombre);
                values[idx] += cant;
            }
        });
    }

    const ctx = document.getElementById(chartID).getContext("2d");
    if (tipo === "dia") chartDia?.destroy();
    else chartMes?.destroy();

    const cfg = {
        type: "bar",
        data: { labels, datasets: [{ label: `Ventas ${tipo === "dia" ? "Hoy" : "Mes"}`, backgroundColor: "#3e95cd", data: values }] },
        options: { responsive: true, plugins: { title: { display: true, text: `Ventas por producto (${tipo})` }}, scales:{y:{beginAtZero:true}}}
    };

    tipo === "dia" ? chartDia = new Chart(ctx, cfg) : chartMes = new Chart(ctx, cfg);
}
*/