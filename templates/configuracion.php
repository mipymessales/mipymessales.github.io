<?php
$ruta=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/transferencia_transfermovil.png";
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;

$stmt = $base_de_datos->prepare("SELECT nro_cuenta,telefono,usuario FROM admin ");
//$stmt->bind_param('s', $categoria);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_OBJ);

?>
<svg style="width: 10% !important;height:  10% !important;">
    <use xlink:href="#icon-user"></use>
</svg>
<h1><?php echo $resultado[0]->usuario;?></h1>
<div class="row">
    <div class="col-xl-4">
<div class="form-validation">
    <form enctype="multipart/form-data" class="form-valide" action="controllers/qr_pago.php" method="POST">

        <?php if (!file_exists($ruta)){  ?>
            <div class="card mb-3">
                <div class="card-header pb-0">
                    <h5 class="card-title">Configura tu QR para transferir dinero a tu cuenta</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-form-label">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="nro_tarjeta">Nro cuenta <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="nro_tarjeta" name="nro_tarjeta" value="" placeholder="Inserte el nro de su tarjeta..">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label" for="telefono">Nro tel&eacute;fono <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <?php if (empty($resultado[0]->telefono)){?>
                                    <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="Nro de telefono para confirmar la transferencia" value=""></input>
                                    <?php }else{ ?>
                                        <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="<?php echo $resultado[0]->telefono;?>" value="<?php echo $resultado[0]->telefono;?>"></input>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <input type="hidden" name="agregar" id="agregar">
                    <button type="button" class="btn btn-dark text-white" data-dismiss="modal">Cancelar</button>
                    <button  type="submit" class="btn btn-warning text-white">Agregar</button>
                </div>
            </div>
            <?php  }else{ ?>

            <div class="card mb-3">


                <div class="card-header pb-0">
                    <h5 class="card-title">Cuenta de Transfermovil</h5>
                </div>
                <div class="card horizontal-card__menu mb-0 horizontal">
                    <div class="horizontal-card__menu--image">
                        <img src="/images/transferencia_transfermovil.png"  alt="menu">
                    </div>
                    <div class="card-body">
                        <div id="mostrar">
                        <h4 class="horizontal-card__menu--title d-flex justify-content-between">Datos de la cuenta</h4>

                        <p class="d-flex justify-content-between">Nro cuenta: <?php echo $resultado[0]->nro_cuenta;?></p>

                        <p class="d-flex justify-content-between">Tel&eacute;fono: <?php echo $resultado[0]->telefono;?></p>
                        </div>


                        <div id="actualizar" style="display: none">
                            <form enctype="multipart/form-data" class="form-valide" action="controllers/qr_pago.php" method="POST">
                            <div class="col-12 col-form-label">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="nro_tarjeta">Nro cuenta <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="nro_tarjeta" name="nro_tarjeta" value="" placeholder="Inserte el nro de su tarjeta..">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="telefono">Nro tel&eacute;fono <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-8">
                                        <?php if (empty($resultado[0]->telefono)){?>
                                            <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="Nro de telefono para confirmar la transferencia" value=""></input>
                                        <?php }else{ ?>
                                            <input type="text" class="form-control" id="telefono" name="telefono"  rows="5" placeholder="<?php echo $resultado[0]->telefono;?>" value="<?php echo $resultado[0]->telefono;?>"></input>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>



                    </div>
                </div>

                <div class="card-footer">
                    <button  onclick="editarCuenta()" class="btn btn-warning text-white" >Editar</button>
                </div>
            </div>
        <?php } ?>


    </form>
</div>

    </div>
    <div class="col-xl-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a wider card with supporting text and below as a natural lead-in to the additional content. This content is a little</p>
            </div>
            <div class="card-footer">
                <p class="card-text d-inline">Card footer</p>
                <a href="javascript:void()" class="card-link float-right">Card link</a>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                <p class="card-text">This is a wider card with supporting text and below as a natural lead-in to the additional content. This content is a little</p>
            </div>
            <div class="card-footer">
                <p class="card-text d-inline">Card footer</p>
                <a href="javascript:void()" class="card-link float-right">Card link</a>
            </div>
        </div>
    </div>
</div>
