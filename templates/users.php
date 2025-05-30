<?php
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
include_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;
?>

<button class="btn-flotante" data-toggle="modal" data-target="#exampleModalCenter" >Nuevo</button>
<div class="form-validation">
    <form enctype="multipart/form-data" class="form-valide" action="controllers/trabajadorController.php" method="POST" id="main-contact-form">
        <div class="modal fade bd-example-modal-lg" id="exampleModalCenter">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregando nuevo trabajador ...</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">



                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title">Inserte los datos del trabajador</h4>
                            </div>
                            <div class="card-body">





                                <div class="media media-reply">



                                    <!-- <img class="mr-0 mr-lg-3 rounded-circle" src="../images/blank1.jpg" width="50" height="50" alt="Generic placeholder image">-->

                                            <input type="file" class="dropify mr-0 mr-lg-3 rounded-circle" name="image"  height="50" id="image" data-default-file="" />




                                </div>
                                <div class="media-body">
                                    <div class="d-lg-flex justify-content-between mb-2">


                                        <div class="basic-form col-12">
                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="nombrep">Nombre <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="nombrep" name="nombrep" placeholder="Inserte el nombre del trabajador..">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="ci">Carnet Indentidad <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" maxlength="11" id="ci" name="ci" placeholder="Inserte el CI del trabajador..">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="tel">Teléfono <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="tel" name="tel" placeholder="Inserte el teléfono del trabajador..">
                                                    </div>
                                                </div>
                                            </div>

                                          <!--  <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="dir">Dirección <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="dir" name="dir" placeholder="Inserte la dirección del trabajador..">
                                                    </div>
                                                </div>
                                            </div>-->

                                         <!--   <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="exp">Experiencia <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="exp" name="exp" placeholder="Inserte los años de experiencia del trabajador..">
                                                    </div>
                                                </div>
                                            </div>-->



                                            <div id="accordion-faq" class="accordion">
                                                <div class="card">
                                                    <div class="card-header">


                                                        <div class="form-check form-check-inline">

                                                            <label class="form-check-label">
                                                                <h5 class="mb-0 collapsed c-pointer" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1"><i class="fa" aria-hidden="true"></i>
                                                                    <input class="form-check-input" name="acceso" id="acceso" type="checkbox">
                                                                    Permitir acceso al sitio web
                                                                </h5>

                                                            </label>
                                                        </div>


                                                    </div>
                                                    <div id="collapseOne1" class="collapse" data-parent="#accordion-faq">
                                                        <div class="card-body">

                                                            <div class="col-12 col-form-label">
                                                                <div class="form-group row">
                                                                    <label class="col-lg-4 col-form-label" for="user">Usuario<span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-lg-8">
                                                                        <input type="text" class="form-control" id="user" name="user" placeholder="Inserte el usuario del trabajador..">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-form-label">
                                                                <div class="form-group row">
                                                                    <label class="col-lg-4 col-form-label" for="pass">Contraseña<span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-lg-8">
                                                                        <input type="text" class="form-control" id="pass" name="pass" placeholder="Inserte la contraseña del trabajador..">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>



                                            </div>



                                        </div>
                                    </div>



                                </div>



                            </div>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button  type="submit" class="btn btn-success">Agregar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<div class="content-body">
    <div class="container-fluid">
        <?php



                            $sentencia = $base_de_datos->query("select * from trabajador;");
                            $trabajadores = $sentencia->fetchAll(PDO::FETCH_OBJ);


                            if (!$trabajadores) {
                                #No existe
                                // echo "¡No existe bebidas en el salon !";

                                ?>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Ups!!!. No se encontraron trabajadores.</h4>


                                                <div class="alert alert-warning alert-dismissible fade show">
                                                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close"></span>
                                                    </button>
                                                    <strong>Alerta!</strong> No hay trabajadores agregados al sistema!. Click en nuevo para insertar un trabajador <a data-toggle="modal" data-target="#exampleModalCenter" href="#exampleModalCenter">Nuevo</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php

                            }else{    ?>
                            <div class="row">
                                <?php   foreach($trabajadores as $trabajador) {

                                    $nombre= $trabajador->nombre;
                                    $telefono= $trabajador->phone;
                                    $ci= $trabajador->ci;
                                    $direccion= $trabajador->valoracion;


                                    $usuario=$trabajador->nombre_usuario;
                                    $contrasena=$trabajador->contrasena_usuario;

                                    $b=1;

                                    $chequeo=1;
                                    if($usuario=="Sin usuario"){
                                        $chequeo=0;
                                    }

                                    $foto= $trabajador->foto;
                                    $id_cocinero= $trabajador->id;

                                    // echo $valoracion;
                                    ?>



                                        <div class="col-sm-6 col-lg-4 col-xl-2 col-xxl-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <?php if($foto!=null){?>
                                                            <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="/images/<?php echo $foto;?>" width="80" height="80" alt="">
                                                        <?php }else{?>
                                                            <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="/images/blank1.jpg" width="80" height="80" alt="">
                                                        <?php }?>

                                                        <h4 class="mb-0"><?php echo $nombre?></h4>
                                                        <p class="text-muted mb-0"><?php echo $ci;?></p>
                                                        <ul class="card-profile__info ">
                                                            <li class="mb-1"><strong class="text-dark mr-4 text-left">Teléfono :</strong>  <?php echo $telefono;?></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-footer border-0 bg-white pb-4">
                                                    <div class="button-group ">
                                                        <button data-toggle="modal" data-target="#exampleModalEDIT<?php echo $id_cocinero;?>" class="btn btn-warning text-white" style="margin: 4px">Editar</button>
                                                        <button data-toggle="modal" data-target="#exampleModalDELETE<?php echo $id_cocinero;?>" class="btn btn-danger text-white" style="margin: 4px">Eliminar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php

                                        include "modal_editartrabajador.php";

                                }?>

                                  </div>
                            <?php  }?>





    </div>
</div>






<!-- Modal  INSERT-->


