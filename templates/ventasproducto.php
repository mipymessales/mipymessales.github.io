<meta charset="UTF-8">
<title>Ventas</title>
<style>
    .tabs {
        display: flex;
        cursor: pointer;
        /*background-color:  var(--gray-base);*/
        color: var(--sidebar-link-color);
        padding: 10px;
    }

    .tab {
        margin-right: 15px;
        padding: 10px;
        border: 1px solid #ccc;
    }
    .tab.active {
        background: var(--main-header-bg);
        color: var(--sidebar-link-active-color);
        border: var(--gray-tint-50) 1px dashed;
    }

    .tab:hover {
        color: var(--sidebar-link-active-color);
    }
    .label-info {
        background: var(--gray-base);
        padding: 10px;
        color: #fff; }

    #contenido {
        min-width: 700px; /* Fuerza el scroll en pantallas pequeñas */
    }

    @media (max-width: 768px) {
        #contenido {
            font-size: 12px;
        }
        #main-header {
            padding: 1rem 0.5rem;
        }
        .container-fluid {
            padding: 0;
            margin: auto;
        }
        .content-body .container-fluid{
            padding: 0;
            margin: auto;
            margin-top: 10px;
        }

    }
    #contenido .tabulator-cell {
        white-space: nowrap;
    }
    select {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 12px;
    }

    option {
        padding: 5px;
    }
    .tabla-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* scroll más suave en iOS */
        scrollbar-width: thin;
        width: 100%;
        position: relative;
        z-index: 1;
    }
    #carritoVisual {
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 16px;
        width: 100%;
        max-width: 500px;
        background-color: #fdfdfd;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        overflow-x: auto;
        text-align: center;
    }

    #carritoVisual h3 {
        text-align: center;
        margin-bottom: 16px;
    }

    table.factura {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table.factura th,
    table.factura td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }

    table.factura th {
        background-color: #f0f0f0;
    }

    .total-fila {
        font-weight: bold;
        text-align: right;
        background-color: #fafafa;
    }

    .vacio {
        text-align: center;
        color: #888;
    }
    .buscador-contenedor {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 10px;
    }

    #buscador {
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 250px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: border-color 0.2s ease-in-out;
    }

    #buscador:focus {
        outline: none;
        border-color: #007bff;
    }
    .tabulator-header-filter input {
        padding: 5px;
        font-size: 13px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .fila-roja {
        background-color: #ffe5e5 !important;
        color: #b30000;
    }

    .fila-roja .warning-icon::before {
        content: "⚠️";
        margin-right: 6px;
    }
    .fila-deshabilitada {
        opacity: 0.5;
    }
</style>
<link href="assets/css/dropify.min.css" rel="stylesheet">
<div id="main-header">

    <?php if (isset($_REQUEST["errorUpdate"])){?>
        <div class="alert alert-danger alert-dismissible fade show notification " style="display: block">
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
            </button>
            <strong>Error!</strong> Al actualizar los datos<br>Mensaje:<br> <?php ($_REQUEST["errorUpdate"])?>
        </div>
    <?php }elseif (isset($_REQUEST["errorInsert"])){?>
    <div class="alert alert-danger alert-dismissible fade show notification"  style="display: block">
        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
        </button>
        <strong>Error!</strong> Al insertar los datos<br>Mensaje:<br> <?php echo ($_REQUEST["errorInsert"]);?></div>
</div>
<?php }elseif (isset($_REQUEST["errorDelete"])){?>
    <div class="alert alert-danger alert-dismissible fade show notification"  style="display: block">
        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
        </button>
        <strong>Error!</strong> Al eliminar los datos<br>Mensaje:<br> <?php ($_REQUEST["errorDelete"])?>
    </div>
<?php }?>






<h1>Selecciona los productos que desea vender</h1>

<div id="contenidomesa"></div>

<div id="btn-change-llamada"></div>

<div class="">
    <div class="tabla-scroll">
        <div class="buscador-contenedor">
            <input id="buscador" type="text" placeholder="🔍 Buscar por nombre...">
        </div>
        <div id="contenido" style="width: 100%;">No hay pedidos.</div>
    </div>
    <div  class="container-fluid">
    <div id="carritoVisual">Listado: <strong>(vacío)</strong></div>
        <form id="ventasForm" >
    <div type="submit" id="btn-cerrar-cuenta"></div>
    <input type="hidden" id="carrito" name="carrito" value=""><br>
        </form>
        <p id="reservationMessage" class="mt-2"></p>
    </div>

</div>


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="assets/js/dropify.min.js"></script>
 <script src="assets/js/dropify-init.js"></script>-->

<script>
    function esNuloOVacio(v) {
        if (v === null || v === undefined) return true;

        // Strings
        if (typeof v === "string") {
            const trimmed = v.trim().toLowerCase();
            return trimmed === "" || trimmed === "undefined" || trimmed === "null";
        }

        // Arrays
        if (Array.isArray(v)) return v.length === 0;

        // HTML Elements
        if (v instanceof HTMLElement) {
            // Revisa contenido visible y atributos clave
            const contenido = v.textContent?.trim() || v.value?.trim() || v.innerHTML?.trim();
            const href = v.getAttribute?.("href");
            const src = v.getAttribute?.("src");
            return (!contenido && !href && !src);
        }

        // Objetos planos
        if (typeof v === "object") return Object.keys(v).length === 0;

        return false; // Para tipos como número, booleano, etc.
    }
    var table;
    var intervaloActualPedidoMesa=null;
    var intervaloActualListado=null;
    var datosAnterioresPedidoMesa = null;
    var datosAnterioresListadoMesa = null;
    var idrestaurant=<?php echo $_SESSION['idrestaurant']; ?>;

    const inputCarrito = document.getElementById('carrito');
    const carritoVisual = document.getElementById('carritoVisual');

    // Carrito como objeto: { "Pizza": { cantidad: 2, precio: 7.5 }, ... }
    let carrito = {};
    //console.log(callwaiter);
    window.onload = function () {
        $('.dropify').dropify();
        if (document.getElementById("contenido")) {
            //hacer una tabla de ventas
            table = new Tabulator("#contenido", {
                height: "auto",
                layout: "fitColumns",
                responsiveLayout: "collapse",

                rowFormatter: function (row) {
                    const data = row.getData();
                    const el = row.getElement();
                    if (data.cantidadproducto == 0 || data.cantidadproducto == '0') {
                        el.classList.add("fila-roja");
                        el.classList.add("fila-deshabilitada");
                        row.getCells().forEach(cell => {
                            const celda = cell.getElement();
                            celda.style.pointerEvents = "none"; // Bloquear interacción
                            celda.style.opacity = "0.5";        // Mostrar como deshabilitado
                        });
                       // console.log("❌ Fila deshabilitada:", data);
                    } else {
                        el.classList.remove("fila-roja");
                        el.classList.remove("fila-deshabilitada");
                        row.getCells().forEach(cell => {
                            const celda = cell.getElement();
                            celda.style.pointerEvents = "auto";
                            celda.style.opacity = "1";
                        });
                    }
                },

                columns: [
                    { title: "ID Plato", field: "idplato", visible: false },
                    { title: "cantidadproducto", field: "cantidadproducto", visible: false },
                    { title: "preciotransferencia", field: "preciotransferencia", visible: false },

                    {
                        title: "Categoría",
                        field: "categoria",
                        minWidth: 80,
                        headerFilter: "input",
                        formatter: function (cell) {
                            const data = cell.getRow().getData();
                            const texto = cell.getValue();
                            if (data.cantidadproducto == 0 || data.cantidadproducto == '0') {
                                return `<span class="warning-icon">${texto}</span>`;
                            }
                            return texto;
                        },
                    },

                    {
                        title: "Nombre (Cantidad)",
                        field: "nombre",
                        minWidth: 80,
                        headerFilter: "input",
                        formatter: function (cell) {
                            const data = cell.getRow().getData();
                            const texto = cell.getValue();
                            return `<span>${texto} (${data.cantidadproducto})</span>`;
                        }
                    },

                    {
                        title: "Tipo de Pago",
                        field: "tipoSeleccionado",
                        formatter: function (cell) {
                            const val = cell.getValue();
                            let color = "";
                            if (val === "Efectivo") {
                                color = "#0acf97";
                            } else if (val === "Transferencia") {
                                color = "#727c59";
                            } else {
                                color = "gray";
                            }
                            return `<span style="color: white; background-color: ${color}; padding: 4px 8px; border-radius: 4px; display: inline-block; text-align: center; min-width: 70px;">${val}</span>`;
                        },
                        cellClick: function(e, cell) {
                            let currentValue = cell.getValue(); // "Efectivo" o "Transferencia"
                            Swal.fire({
                                title: "¿Cambiar método de pago?",
                                input: "select",
                                inputOptions: {
                                    Efectivo: "Efectivo",
                                    Transferencia: "Transferencia"
                                },
                                inputValue: currentValue,
                                showCancelButton: true,
                                confirmButtonText: "Cambiar",
                                cancelButtonText: "Cancelar"
                            }).then(result => {
                                if (result.isConfirmed) {
                                    const nuevoTipo = result.value;

                                    if (nuevoTipo && nuevoTipo !== currentValue) {
                                        const row = cell.getRow();
                                        const rowData = row.getData();

                                        const nuevoPrecio = nuevoTipo === "Efectivo"
                                            ? parseFloat(rowData.precioventa || 0)
                                            : parseFloat(rowData.preciotransferencia || 0);

                                        const estransferencia = nuevoTipo === "Efectivo"
                                            ? 0
                                            : 1;

                                        const cantidad = parseInt(rowData.cantidad) || 0;
                                        const subtotal = cantidad * nuevoPrecio;

                                        row.update({
                                            tipoSeleccionado: nuevoTipo,
                                            precio: nuevoPrecio,
                                            subtotal: subtotal
                                        });

                                        // Forzar render de la celda precio
                                        const valorActual = row.getCell("precio").getValue();
                                        row.update({ precio: valorActual });

                                        // Actualizar carrito
                                        if (carrito[rowData.nombre]) {
                                            carrito[rowData.nombre].precio = nuevoPrecio;
                                            carrito[rowData.nombre].cantidadp = rowData.cantidadproducto;
                                            carrito[rowData.nombre].cantidad = cantidad;
                                            carrito[rowData.nombre].transferencia = estransferencia;

                                        }
                                        actualizarCarrito();
                                    }
                                }
                            });


                        }

                    },

                    {
                        title: "Precio Efectivo/Transferencia",
                        field: "precio",
                        formatter: function (cell) {
                            const data = cell.getRow().getData();
                            const tipo = data.tipoSeleccionado;
                            const efectivo = data.precioventa ?? 0;
                            const transferencia = data.preciotransferencia ?? 0;

                            const estilo = (seleccionado) => `
            background-color: ${seleccionado === "Efectivo" ? "#0acf97" : "#727c59"};
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
        `;

                            return `
    <span style="${tipo === "Efectivo" ? estilo("Efectivo") : ""}">$${efectivo}</span> /
    <span style="${tipo === "Transferencia" ? estilo("Transferencia") : ""}">$${transferencia}</span>
`;

                        }
                        // mutator eliminado o comentado
                    },


                    {
                        title: "Cantidad a vender",
                        field: "cantidad",
                        minWidth: 20,
                        formatter: function (cell) {
                            const value = cell.getValue();
                            return `
            <button class="btn btn-danger btn-decrement" style="margin-right: 10px">−</button>
            <span class="cantidad-valor">${value}</span>
            <button class="btn btn-success btn-increment" style="margin-left: 10px">+</button>
        `;
                        },
                        cellClick: function (e, cell) {
                            const target = e.target;
                            let value = parseInt(cell.getValue());
                            const row = cell.getRow();
                            const rowData = row.getData();

                            // Cantidad máxima permitida (stock disponible)
                            const cantRestante = parseInt(rowData.cantidadproducto);

                            // Precio unitario según tipo de pago seleccionado
                            const precioUnitario = rowData.tipoSeleccionado === "Transferencia"
                                ? parseFloat(rowData.preciotransferencia || 0)
                                : parseFloat(rowData.precioventa || 0);

                            const estransferencia = rowData.tipoSeleccionado === "Efectivo"
                                ? 0
                                : 1;

                            if (target.classList.contains("btn-increment")) {
                                if (value < cantRestante) {
                                    value++;

                                    // Actualizar o crear el objeto en carrito, con el precio correcto según tipo de pago
                                    carrito[rowData.nombre] = carrito[rowData.nombre] || {
                                        cantidad: 0,
                                        precio: precioUnitario,
                                        id: rowData.idplato,
                                        categoria: rowData.categoria,
                                        cantidadp: cantRestante,
                                        transferencia:estransferencia
                                    };
                                    carrito[rowData.nombre].cantidad++;

                                    // Actualiza el precio por si cambió el tipo de pago
                                    carrito[rowData.nombre].precio = precioUnitario;

                                    actualizarCarrito();
                                } else {
                                    return;
                                }
                            } else if (target.classList.contains("btn-decrement") && value > 0) {
                                value--;

                                if (carrito[rowData.nombre]) {
                                    carrito[rowData.nombre].cantidad--;

                                    if (carrito[rowData.nombre].cantidad <= 0) {
                                        delete carrito[rowData.nombre];
                                    } else {
                                        // Actualiza el precio por si cambió el tipo de pago
                                        carrito[rowData.nombre].precio = precioUnitario;
                                    }
                                    actualizarCarrito();
                                }
                            } else {
                                return;
                            }

                            // Calcular subtotal con el precio correcto según tipo de pago
                            const subtotal = value * precioUnitario;

                            row.update({
                                cantidad: value,
                                subtotal: subtotal
                            });
                        }
                    },


                    {
                        title: "Subtotal (Precio * Cantidad)",
                        field: "subtotal",
                        hozAlign: "right",
                        minWidth: 30,
                        bottomCalc: "sum"
                    },
                ],

                cellEdited: function (cell) {
                    if (cell.getField() === "tipoSeleccionado") {
                        const row = cell.getRow();
                        const data = row.getData();
                        const tipo = data.tipoSeleccionado;

                        const nuevoPrecio = tipo === "Efectivo"
                            ? data.precioventa
                            : data.preciotransferencia;

                        row.update({ precio: nuevoPrecio });
                    }
                }
            });

        }
        iniciarAutoCargaPedidoMesa(idrestaurant);
    };
    document.getElementById("buscador").addEventListener("keyup", function () {
        var valor = this.value;
        if (valor) {
            table.setFilter("nombre", "like", valor); // Filtra por coincidencia parcial
        } else {
            table.clearFilter(); // Limpia si el campo está vacío
        }
    });
    function iniciarAutoCargaPedidoMesa(idrestaurant) {
        if (intervaloActualPedidoMesa !== null) {
            clearInterval(intervaloActualPedidoMesa);
        }
        cargarPedidoMesa(idrestaurant);
        intervaloActualPedidoMesa =setInterval(function () {
           // console.log("intervaloActualPedidoMesa");
            cargarPedidoMesa(idrestaurant);
        }, 2000);
    }
    function cargarPedidoMesa(idrestaurant) {
        $.ajax({
            url: "controllers/obtener_producto_ventas.php",
            type: "POST",
            data: { idrestaurant: idrestaurant },
            dataType: "json",
            success: function (data) {
                const nuevosDatosP = JSON.stringify(data);
                if (nuevosDatosP === datosAnterioresPedidoMesa) {
                    //console.log('Datos sin cambios en pedidos, no se actualiza la vista.');
                }else{
                    datosAnterioresPedidoMesa = nuevosDatosP;
                 //   console.log("✅ Datos recibidos:", data);
                   // table.setData(data);  // Carga datos en la tabla ya creada
                   // table.redraw(true);
                    // Después de cargar los datos
                    table.setData(data).then(() => {
                        table.redraw(true); // <- esto aplica el rowFormatter
                    });
                }
                var buttonCerrarCuenta= document.getElementById("btn-cerrar-cuenta");
                buttonCerrarCuenta.replaceChildren();
                const botonC = document.createElement('button');
                botonC.textContent = 'Vender';
                botonC.classList.add('btn','btn-success','btn-block', 'border-0', 'text-black-50');
                botonC.style.width="auto";
                botonC.style.marginTop="5px";
                // Asignar la función al onclick usando una función anónima
                botonC.onclick = function() {
                    cerrarCuenta(data[0]['idcliente'],mesa);

                };
                buttonCerrarCuenta.appendChild(botonC);
            },
            error: function (xhr, status, error) {
                console.error("❌ Error AJAX:");
                console.error("Estado:", status);
                console.error("Error:", error);
                console.error("Respuesta completa:", xhr.responseText);
            }
        });
    }
    function cerrarCuenta(idcliente,mesa) {
        //alert(idcliente);
        $.ajax({
            url: '/controllers/edit_pedido_mesa.php',
            dataType:'json',
            method: 'POST',
            data: { idcliente: idcliente,idmesa:mesa,action:'cerrarcuenta'},
            success: function(data) {
                // document.getElementById('contenido').innerHTML =data;
                //console.log(data);
                if (data["status"]==="success"){
                    var pedidos = JSON.parse(localStorage.getItem('pedidos')) || [];
                    idcliente = Number(idcliente);
                    // console.log(JSON.stringify(pedidos));
                    pedidos = pedidos.filter(item => item.idcliente !== idcliente);
                    localStorage.setItem('pedidos', JSON.stringify(pedidos));
                    //console.log(JSON.stringify(pedidos));
                    location.reload();

                }

            }
        });
    }
    function saludar(mesaid) {
        $.ajax({
            url: '/controllers/call_waiter.php',
            /*  headers: {
                  "Content-Type": "application/x-www-form-urlencoded",
              },*/
            dataType:'json',
            method: 'POST',
            data: { table_id: mesaid,estado:'visto' },
            success: function(data) {
                // document.getElementById('contenido').innerHTML =data;
                console.log(data);
                if (data["status"]==="success"){
                    document.getElementById("span"+mesaid).style.display = 'none';
                    document.getElementById("btn-change-llamada").replaceChildren();
                    //location.reload();
                    // document.getElementById("call-status").innerText = data["message"];
                    // document.getElementById("call-status").attr("class","show");

                }

            }
        });
    }
    const navOfertas = document.querySelectorAll('.tab');
    // Función para establecer el enlace activo
    function setActiveLinkNav(link) {
        navOfertas.forEach(nav => nav.classList.remove('active'));
        link.classList.add('active');
        localStorage.setItem('navOfertas', link.getAttribute('id')); // Guarda el href como identificador
    }
    // Al hacer clic en un enlace, se guarda como activo
    navOfertas.forEach(link => {
        link.addEventListener('click', function(e) {
            setActiveLinkNav(this);
        });
    });
    // Al cargar la página, revisa si hay un enlace guardado
    window.addEventListener('DOMContentLoaded', () => {

        const savedHref = localStorage.getItem('navOfertas');
        if (savedHref) {
            const savedLink = document.querySelector(`.tab[href="${savedHref}"]`);
            if (savedLink) {
                setActiveLinkNav(savedLink);
            }
        }
    });
    const element = document.querySelector('.alert');
    if (!esNuloOVacio(element)) {
        setTimeout(() => {
            element.classList.remove('show');
            element.style.display = 'none';
        }, 6000);
    }

    function actualizarCarrito() {
        inputCarrito.value = JSON.stringify(carrito);

        if (Object.keys(carrito).length === 0) {
            carritoVisual.innerHTML = 'Carrito: <strong>(vacío)</strong>';
            return;
        }

        let totalGeneral = 0;
        let html = `<h4>Productos seleccionados</h4><br>
      <table class="factura">
        <thead>
          <tr>
            <th>Producto</th>
            <th class="text-center">Cantidad</th>
            <th class="text-right">Precio Unitario</th>
            <th class="text-right">Subtotal</th>
          </tr>
        </thead>
        <tbody>
    `;

        for (const [producto, datos] of Object.entries(carrito)) {
            const subtotal = datos.cantidad * datos.precio;
            totalGeneral += subtotal;

            html += `
        <tr>
          <td>${producto}</td>
          <td class="text-center">${datos.cantidad}</td>
          <td class="text-right">$${datos.precio}</td>
          <td class="text-right">$${subtotal}</td>
        </tr>
      `;
        }

        html += `
        <tr class="total-row">
          <td colspan="3" class="text-right">Total</td>
          <td class="text-right">$${totalGeneral}</td>
        </tr>
      </tbody>
    </table>`;

        carritoVisual.innerHTML = html;
    }


    // RESERVAS
    document.getElementById('ventasForm').addEventListener('submit', (e) => {
        e.preventDefault();

        /* if (!captchaResuelto()) {
             alert("Por favor, resuelve el CAPTCHA antes de enviar.");
             return;
         }*/

        const formData = new FormData(e.target);
        formData.append('restaurantid', idrestaurant);
        fetch('controllers/guardar_venta.php', {
            method: 'POST',
            dataType: 'json',
            body: formData
        })
            .then(res => res.json())
            .then(data => {

                console.log((data));

                if (data["status"] === 'success') {
                    const inputCarrito = document.getElementById('carrito');
                    const carritoVisual = document.getElementById('carritoVisual');
                    carritoVisual.innerHTML = 'Carrito: <strong>(vacío)</strong>';
                    inputCarrito.value='';
                    Swal.fire("✅ Venta aprobada", "", "success");
                    e.target.reset();

                    // grecaptcha.reset();
                } else {
                    Swal.fire("❌ Error al vender productos.", error.message, "error");
                    console.log(" Error "+data["msg"]);
                    e.target.reset();
                }
            });
    });


</script>
