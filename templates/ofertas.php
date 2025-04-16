
    <meta charset="UTF-8">
    <title>Menú con Tabs</title>
    <style>
        .tabs {
    display: flex;
    cursor: pointer;
    background-color: #eee;
            padding: 10px;
        }

        .tab {
    margin-right: 15px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .tab:hover {
    background-color: #ddd;
        }

        #contenido {
            margin-top: 20px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="label-info">Selecciona una categoría para ver los productos.</div>
<div class="tabs">
    <div class="tab" onclick="cargarCategoria('entrantes')">Entrantes</div>
    <div class="tab" onclick="cargarCategoria('platos')">Platos</div>
    <div class="tab" onclick="cargarCategoria('postres')">Postres</div>
    <div class="tab" onclick="cargarCategoria('bebidas')">Bebidas</div>
</div>

<div id="contenido">Selecciona una categoría para ver los productos.</div>

<script>
    function cargarCategoria(categoria) {
        $.ajax({
            url: '../controllers/obtener_menu.php',
            method: 'POST',
            data: { categoria: categoria },
            success: function(data) {
            $('#contenido').html(data);
        }
        });
    }
</script>

