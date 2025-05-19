
    <meta charset="UTF-8">
    <title>Menú con Tabs</title>
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
           /* margin-top: 20px;*/
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



    <?php
    defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
    include_once ROOT_DIR."pdo/conexion.php";

    global $base_de_datos;
    $sentencia = $base_de_datos->query("select * from mesa where disponible=0");
    $mesas = $sentencia->fetchAll(PDO::FETCH_OBJ);
    if (!$mesas) {
        #No existe
        echo "<h1>No existe pedidos en el salon !</h1>";
        //  exit();
    }else{
        $i=1;
    ?>



        <h1>Selecciona una mesa para ver los pedidos.</h1>
        <div class="tabs">
            <?php foreach($mesas as $mesa){
                $nro_mesa= $mesa->id;
                ?>
                <?php if ($i==1){?>
            <div class="tab active" id="mesa<?php echo $nro_mesa; ?>" onclick="cargarPedidoMesa(<?php echo $nro_mesa; ?>)">Mesa<?php echo $nro_mesa; ?> </div>
                    <?php  echo "<script type='text/javascript'>
                          var idmesa='$nro_mesa';
                    </script>"; ?>


                 <?php }else{  ?>
                    <div class="tab" id="mesa<?php echo $nro_mesa; ?>" onclick="cargarPedidoMesa(<?php echo $nro_mesa; ?>)">Mesa<?php echo $nro_mesa; ?> </div>
                <?php }  ?>
            <?php $i++;  }  ?>
        </div>
        <div class="content-body">

            <div class="container-fluid">

        <div id="contenido">No hay pedidos.</div>

        </div>
        </div>


    <?php }  ?>
    </div>

   <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/dropify.min.js"></script>
    <script src="assets/js/dropify-init.js"></script>-->

<script>
    function esNuloOVacio(v) {
        return v === null || v === undefined || v === "";
    }
    var table;
    window.onload = function () {
        $('.dropify').dropify();
        table = new Tabulator("#contenido", {
            height: "auto",
            layout: "fitColumns",
            responsiveLayout: "collapse",
            columns: [
                { title: "id", field: "id", visible: false },
                { title: "Nombre", field: "nombre" },
                { title: "Precio", field: "precio", hozAlign: "right" },
                { title: "Cantidad", field: "cantidad" },
                { title: "Subtotal (Precio * Cantidad)", field: "subtotal", hozAlign: "right" },
                { title: "Categoría", field: "categoria" },
                {
                    title: "Estado",
                    field: "estado",
                    formatter: function(cell) {
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

                                fetch("controllers/edit_pedido_mesa.php", {
                                    method: "POST",
                                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
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

                { title: "Cliente ID", field: "idcliente", visible: false },
                { title: "ID Plato", field: "idplato", visible: false },
                {
                    formatter: "buttonCross",
                    width: 30,
                    align: "center",
                    cellClick: function (e, cell) {
                        if (confirm("¿Eliminar este registro?")) {
                            const row = cell.getRow();
                            fetch("controllers/edit_pedido_mesa.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `action=delete&id=${row.getData().id}`
                            }).then(() => row.delete());
                        }
                    }
                }
            ],
            cellEdited: function (cell) {
                const data = cell.getRow().getData();

                fetch("controllers/edit_pedido_mesa.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({
                        action: "update",
                        id: data.id,
                        estado: data.estado
                    })
                })
                    .then(response => response.text())
                    .then(res => {
                        console.log("✅ Actualización exitosa:", res);
                    })
                    .catch(error => {
                        console.error("❌ Error al actualizar:", error);
                    });
            }
        });
        if (!esNuloOVacio(idmesa))
           cargarPedidoMesa(idmesa);
    };
    function cargarPedidoMesa(mesa) {
        $.ajax({
            url: "/controllers/obtener_pedido_mesa.php",
            type: "POST",
            data: { mesa: mesa },
            dataType: "json",
            success: function (data) {
                console.log("✅ Datos recibidos:", data);
                table.setData(data);  // Carga datos en la tabla ya creada
            },
            error: function (xhr, status, error) {
                console.error("❌ Error AJAX:");
                console.error("Estado:", status);
                console.error("Error:", error);
                console.error("Respuesta completa:", xhr.responseText);
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

