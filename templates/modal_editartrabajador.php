<?php ?>
<div class="form-validation">
    <form enctype="multipart/form-data" class="form-valide" action="controllers/trabajadorController.php" method="POST" id="main-contact-form">
        <div class="modal fade bd-example-modal-lg" id="exampleModalEDIT<?php echo $id_cocinero;?>">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: black">Editando trabajador ...</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">



                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title" style="color: black">Edite los datos del trabajador</h4>
                            </div>
                            <div class="card-body">





                                <div class="media media-reply">



                                    <!-- <img class="mr-0 mr-lg-3 rounded-circle" src="../images/blank1.jpg" width="50" height="50" alt="Generic placeholder image">-->
                                    <?php if($foto!=null){?>
                                        <input type="file" class="dropify mr-0 mr-lg-3 rounded-circle" name="image"  height="50" id="image" data-default-file="/images/<?php echo $foto;?>" />
                                    <?php }else{?>
                                        <input type="file" class="dropify mr-0 mr-lg-3 rounded-circle" name="image"  height="50" id="image" data-default-file="/images/blank1.jpg" />
                                    <?php }?>





                                </div>
                                <div class="media-body">
                                    <div class="d-lg-flex justify-content-between mb-2">


                                        <div class="basic-form col-12">
                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="nombrep">Nombre <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="nombrep" name="nombrep" placeholder="Edite el nombre del trabajador.."  value="<?php echo $nombre;?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="ci">Carnet Indentidad <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" maxlength="11" id="ci" name="ci" placeholder="Edite el CI del trabajador.." value="<?php echo $ci;?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-form-label">
                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label" for="tel">Teléfono <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="tel" name="tel" placeholder="Edite el teléfono del trabajador.." value="<?php echo $telefono;?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <h4 class="card-title mt-5" style="color: black">Estado de disponibilidad </h4>
                                            <div class="basic-form">

                                                <?php if($disponible){?>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="d" checked="true"> Activo</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="a"> Inactivo</label>
                                                <?php }else{?>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="d" > Activo</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="a" checked="true"> Inactivo</label>
                                                <?php }?>
                                            </div>

                                            <?php
                                            $roles_disponibles = ["ofertas", "pedidoscliente", "ventasproducto", "cierresventa", "configuracion"];
                                            $userrold= obtenerRolIdByUserId($id_cocinero, $base_de_datos);
                                            $roles_usuario = obtenerRoles($userrold, $base_de_datos); // ["ventas", "confisitio"]
                                            ?>

                                            <h4 class="card-title mt-5" style="color: black">Edita los accesos al panel de administración </h4>
                                            <div class="basic-form">

                                                <?php foreach ($roles_disponibles as $rol): ?>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" name="roles[]" value="<?= $rol ?>"
                                                            <?= in_array($rol, $roles_usuario) ? 'checked' : '' ?>>
                                                        <?= ucfirst($rol) ?>
                                                    </label><br>
                                                <?php endforeach; ?>

                                               <!-- <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="ofertas"> Ofertas</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="pedidos"> Pedidos</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="ventas"> Ventas</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="cierres"> Cierres</label>
                                                <label class="checkbox-inline"><input type="checkbox" name="roles[]" value="confisitio"> Configuración del sitio</label>
-->

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
                                                                <h5 class="mb-0 collapsed c-pointer" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne1" style="color: black"><i class="fa" aria-hidden="true"></i>
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
                                                                        <input type="text" class="form-control" id="user" name="user" placeholder="Edite el usuario del trabajador.."  value="<?php echo $usuario;?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-form-label">
                                                                <div class="form-group row">
                                                                    <label class="col-lg-4 col-form-label" for="pass">Contraseña<span class="text-danger">*</span>
                                                                    </label>
                                                                    <div class="col-lg-8">
                                                                        <input type="text" class="form-control" id="pass" name="pass" placeholder="Edite la contraseña del trabajador.."  value="">
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
                        <input type="hidden" name="id" value="<?php echo $id_cocinero;?>">
                        <input type="hidden" name="idrol" value="<?php echo $idrol;?>">
                        <input type="hidden" name="foto" value="<?php echo $foto;?>">
                        <input type="hidden" id="idrestaurant" name="idrestaurant" value="<?php echo $restaurantid; ?>">
                        <input type="hidden" id="action" name="action" value="edit">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button  type="submit" class="btn btn-warning">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>