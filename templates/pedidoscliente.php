
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <style>

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
        .fila-roja {
            background-color: #ffe5e5 !important;
            color: #b30000;
        }

        .fila-roja .warning-icon::before {
            content: "⚠️";
            margin-right: 6px;
        }
    </style>
        <link href="assets/css/dropify.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/restaurant.css" rel="stylesheet">
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






        <h1>Selecciona un cliente para ver los pedidos.</h1>

        <div id="contenidomesa"></div>



        <div class="">
            <div class="tabla-scroll">
                <div id="descripcion" class="mb-2" ></div>
                <div id="contenido" style="width: 100%;">No hay pedidos.</div>
            </div>
            <div id="btn-cerrar-cuenta"></div>
            <div class="alert-warning mb-2" style="padding: 10px"><h5 class="section-title" style="font-weight: bold">¡Importante!</h5><h6>» Solo los pedidos con el estado marcado como
                    <span style="color: white; background-color: #0acf97; padding: 4px 8px; border-radius: 4px; display: inline-block; text-align: center; min-width: 70px;">Aprobado</span>
                     seran vendidos, los otros se eliminaran de la venta !</h6><br>
                <h6>» Los productos que excedan a la cantidad que hay en el almacen no se Aprobaran hasta que se rebajen a una cantidad disponible
                    !</h6>
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
    var idcliente=null;
    //console.log(callwaiter);
    window.onload = function () {
        $('.dropify').dropify();
        if (document.getElementById("contenido")) {
            table = new Tabulator("#contenido", {
                height: "auto",
                layout: "fitColumns",
                responsiveLayout: "collapse",

               /* rowFormatter: function (row) {
                    const data = row.getData();
                    const el = row.getElement();

                    if (parseInt(data.cantidadproducto) < parseInt(data.cantidad) ) {
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
*/
                rowFormatter: function (row) {
                    const data = row.getData();
                    const el = row.getElement();

                    // Obtener solo la celda de la columna "estado"
                    const estadoCell = row.getCell("estado");
                    const estadoEl = estadoCell ? estadoCell.getElement() : null;

                    if (parseInt(data.cantidadproducto) < parseInt(data.cantidad)) {
                        el.classList.add("fila-roja"); // Si aún quieres marcar la fila visualmente

                        if (estadoEl) {
                            estadoEl.style.pointerEvents = "none"; // Bloquear interacción
                            estadoEl.style.opacity = "0.5";        // Mostrar como deshabilitado
                        }

                 /*       const subCell = row.getCell("subtotal");
                        const subEl = subCell ? subCell.getElement() : null;
                        if (subEl) {
                            subEl.value=0;
                            return subEl;
                        }*/


                    } else {
                        el.classList.remove("fila-roja");

                        if (estadoEl) {
                            estadoEl.style.pointerEvents = "auto"; // Rehabilitar interacción
                            estadoEl.style.opacity = "1";          // Restaurar opacidad
                        }
                    }
                },





                columns: [
                    {title: "id", field: "id", visible: false},
                    {title: "cantidadproducto", field: "cantidadproducto", visible: false},
                    {title: "Categoría", field: "categoria", minWidth: 20},
                    {title: "Nombre(Cantidad)", field: "nombre", minWidth: 170,
                        formatter: function (cell) {
                            const rowData = cell.getRow().getData();
                            const value = cell.getValue();

                            return `${value}<span> (${rowData.cantidadproducto})</span>`;
                        },
                    },

                    {
                        title: "Cantidad",
                        field: "cantidad",
                        minWidth: 120,
                        formatter: function(cell) {
                            const value = cell.getValue();
                            const rowData = cell.getRow().getData();
                            if (rowData.estado=== "Aprobado"){
                                return `

      <span class="cantidad-valor">${value}</span>

    `;
                            }else{
                                return `
      <button class="btn btn-danger btn-decrement" style="margin-right: 5px">−</button>
      <span class="cantidad-valor">${value}</span>
      <button class="btn btn-success btn-increment" style="margin-left: 5px">+</button>
    `;
                            }

                        },
                        cellClick: function(e, cell) {
                            const target = e.target;
                            let value = cell.getValue();
                            const rowData = cell.getRow().getData(); // Aquí puedes obtener ID u otros datos necesarios

                            if (target.classList.contains("btn-increment")) {
                                value++;
                            } else if (target.classList.contains("btn-decrement")) {
                                if (value > 0) value--;
                            } else {
                                return; // clic en otra parte, no hace nada
                            }

                            // Actualiza la celda visualmente
                            cell.setValue(value);

                            // ✅ AJAX para actualizar en la base de datos
                            fetch("controllers/edit_pedido_cliente.php", {
                                method: "POST",

                                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                                body: `action=updatecantidad&id=${rowData.id}&cantidad=${value}`,


                             /*   headers: {
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify({
                                    id: rowData.id,
                                    action:"updatecantidad",// usa el ID real del ítem
                                    cantidad: value
                                }),*/




                            })
                                .then(response => response.json())
                                .then(data => {
                                  //  console.log("Actualizado correctamente:", data);
                                })
                                .catch(error => {
                                    console.error("Error al actualizar:", error);
                                });
                        }
                    },

                    {
                        title: "Estado",
                        field: "estado",
                        minWidth: 80,
                        formatter: function (cell) {
                            const value = cell.getValue();
                            let color = "";
                            if (value === "Aprobado") {
                                color = "#0acf97";
                            } else if (value === "Enviado") {
                                color = "#ffbc00";
                            } else {
                                color = "gray";
                            }
                            return `<span style="color: white; background-color: ${color}; padding: 4px 8px; border-radius: 4px; display: inline-block; text-align: center; min-width: 70px;">${value}</span>`;
                        },
                        cellClick: function (e, cell) {
                            const currentValue = cell.getValue();

                            Swal.fire({
                                title: "¿Cambiar estado?",
                                input: "select",
                                inputOptions: {
                                    Enviado: "Enviado",
                                    Aprobado: "Aprobado"
                                },
                                inputValue: currentValue,
                                showCancelButton: true,
                                confirmButtonText: "Actualizar",
                                cancelButtonText: "Cancelar"
                            }).then(result => {
                                if (result.isConfirmed && result.value !== currentValue) {
                                    cell.setValue(result.value);

                                    fetch("controllers/edit_pedido_cliente.php", {
                                        method: "POST",
                                        headers: {"Content-Type": "application/x-www-form-urlencoded"},
                                        body: new URLSearchParams({
                                            action: "update",
                                            id: cell.getRow().getData().id,
                                            estado: result.value
                                        })
                                    })
                                        .then(res => res.text())
                                        .then(() => {
                                            Swal.fire("✅ Estado actualizado", "", "success");
                                        })
                                        .catch(error => {
                                            Swal.fire("❌ Error al actualizar", error.message, "error");
                                        });
                                }
                            });
                        }
                    },
                    {
                        title: "Eliminar",
                        minWidth: 80,
                        align: "center",
                        formatter: function (cell, formatterParams, onRendered) {
                            const data = cell.getData();
                            if (data.estado !== "Aprobado") {
                                // Devolvemos el símbolo de botón ❌ o puedes usar HTML
                                return "<button class='btn btn-danger btn-sm'>Eliminar</button>";
                            } else {
                                // Puedes dejarlo vacío o poner un guion
                                return "<span style='color: #0acf97;font-weight: bold'>Aprobado</span>";
                            }
                        },
                        cellClick: function (e, cell) {
                            const data = cell.getData();

                            // Solo ejecutar si estado === 'Aprobado'
                            if (data.estado !== "Aprobado") {
                                Swal.fire({
                                    title: "¿Eliminar pedido?",
                                    showCancelButton: true,
                                    confirmButtonText: "Eliminar",
                                    cancelButtonText: "Cancelar"
                                }).then(result => {
                                    if (result.isConfirmed) {
                                        const row = cell.getRow();
                                        fetch("controllers/edit_pedido_cliente.php", {
                                            method: "POST",
                                            headers: {"Content-Type": "application/x-www-form-urlencoded"},
                                            body: `action=delete&id=${row.getData().id}`
                                        }).then(() => row.delete());
                                    }
                                });
                            }
                        }
                    }

                    /*     {
                             formatter: "buttonCross",
                             minWidth: 50,
                             align: "center",
                             title: "Eliminar",
                             cellClick: function (e, cell) {

                                 Swal.fire({
                                     title: "¿Eliminar pedido?",
                                     showCancelButton: true,
                                     confirmButtonText: "Eliminar",
                                     cancelButtonText: "Cancelar"
                                 }).then(result => {
                                     if (result.isConfirmed) {
                                         const row = cell.getRow();
                                         fetch("controllers/edit_pedido_mesa.php", {
                                             method: "POST",
                                             headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                             body: `action=delete&id=${row.getData().id}`
                                         }).then(() => row.delete());
                                     }
                                 });
                             }
                         }*/,
                    {title: "Precio", field: "precio", hozAlign: "right", minWidth: 10},

                    {
                        title: "Subtotal (Precio * Cantidad)",
                        field: "subtotal",
                        hozAlign: "right",
                        minWidth: 10,
                        bottomCalc: "sum"
                    },


                    {title: "Cliente ID", field: "idcliente", visible: false},
                    {title: "ID Plato", field: "idplato", visible: false}

                ],
                cellEdited: function (cell) {
                    const data = cell.getRow().getData();

                    fetch("controllers/edit_pedido_cliente.php", {
                        method: "POST",
                        headers: {"Content-Type": "application/x-www-form-urlencoded"},
                        body: new URLSearchParams({
                            action: "update",
                            id: data.id,
                            estado: data.estado
                        })
                    })
                        .then(response => response.text())
                        .then(res => {
                         //   console.log("✅ Actualización exitosa:", res);
                        })
                        .catch(error => {
                            console.error("❌ Error al actualizar:", error);
                        });
                }
            });
        }

        iniciarAutoCargaListadoPedidos();
        esperarZ((idcliente) => {
         //console.log('z está lista:', idcliente);
            if (idcliente!=='-1')
            iniciarAutoCargaPedidoCliente(idcliente);
            else{
                $('#contenido').html('');
            }
        });

       /*if (!esNuloOVacio(idcliente)){
            console.log("ID OK"+idcliente);
            iniciarAutoCargaPedidoMesa(idcliente);
        }else{
            idcliente= document.getElementById("idclientep").value;
            console.log("ID CCC"+idcliente);
            iniciarAutoCargaPedidoMesa(idcliente);
        }*/
    };
    function esperarZ(callback) {
        const intervalo = setInterval(() => {
            if (typeof window.idcliente !== 'undefined' && window.idcliente !== null) {
                clearInterval(intervalo);
                callback(window.idcliente);
            }
        }, 50);
    }
/*  window.onZReady = function(idcliente) {
        iniciarAutoCargaPedidoMesa(idcliente);
    };*/

    function iniciarAutoCargaPedidoCliente(idcliente, e) {
        if (!esNuloOVacio(e)){
            const navLinks = document.querySelectorAll('.tab');
            navLinks.forEach(nav => nav.classList.remove('active'));
            e.classList.add('active');
        }
        if (intervaloActualPedidoMesa !== null) {
            clearInterval(intervaloActualPedidoMesa);
        }
        cargarPedidoCliente(idcliente);
     intervaloActualPedidoMesa =setInterval(function () {
            cargarPedidoCliente(idcliente);
        }, 2000);
    }
    function iniciarAutoCargaListadoPedidos() {

        if (intervaloActualListado !== null) {
            clearInterval(intervaloActualListado);
        }
        cargarListadoPedidos();
        intervaloActualListado =setInterval(function () {
            cargarListadoPedidos();
        }, 2000);
    }
    function cargarListadoPedidos() {
        $.ajax({
            url: "controllers/obtener_pedidos_clientes.php",
            type: "GET",
            // data: { mesa: mesa },
            dataType: "html",
            success: function (data) {
                const nuevosDatosM = JSON.stringify(data);
                if (nuevosDatosM === datosAnterioresListadoMesa) {
                   // console.log('Datos sin cambios en pedidos, no se actualiza la vista.');
                }else{
                    datosAnterioresListadoMesa = nuevosDatosM;
                   // console.log("✅ Datos recibidos:", data);
                    $('#contenidomesa').html(data);
                }
            },
            error: function (xhr, status, error) {
                console.error("❌ Error AJAX:");
                console.error("Estado:", status);
                console.error("Error:", error);
                console.error("Respuesta completa:", xhr.responseText);
            }
        });

    }

    function cargarPedidoCliente(idcliente) {
        $.ajax({
            url: "controllers/obtener_pedido.php",
            type: "POST",
            data: { idcliente: idcliente },
            dataType: "json",
            success: function (data) {
                const nuevosDatosP = JSON.stringify(data);
                if (nuevosDatosP === datosAnterioresPedidoMesa) {
                  //console.log('Datos sin cambios en pedidos, no se actualiza la vista.');
                }else{
                    datosAnterioresPedidoMesa = nuevosDatosP;
                 // console.log("✅ Datos recibidos:", data);
                    $('#descripcion').html(data["html"]);
                    table.setData(data["data"]);  // Carga datos en la tabla ya creada
                    table.redraw(true);
                }


                var buttonCerrarCuenta= document.getElementById("btn-cerrar-cuenta");
                buttonCerrarCuenta.replaceChildren();
                const botonC = document.createElement('button');
                botonC.textContent = 'Cerrar pedido';
                botonC.classList.add('btn','btn-success','btn-block', 'border-0', 'text-black-50');
                botonC.style.width="auto";
                botonC.style.marginTop="5px";
                // Asignar la función al onclick usando una función anónima
                botonC.onclick = function() {
                    cerrarCuenta(data["data"],idcliente);

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
    function cerrarCuenta(data,idcliente) {
        //alert(idcliente);
        $('#descripcion').html('<div class="alert-warning mb-2" style="padding: 10px"><h5 class="section-title" style="font-weight: bold">¡Cerrando Pedido!</h5></div>');
       $.ajax({
            url: '/controllers/edit_pedido_cliente.php',
            dataType:'json',
            method: 'POST',
            data: { data: data,idcliente:idcliente,action:'cerrarcuenta'},
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









</script>

