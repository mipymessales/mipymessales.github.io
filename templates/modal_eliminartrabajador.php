<?php ?>
<div class="form-validation">
    <form enctype="multipart/form-data" class="form-valide" action="controllers/trabajadorController.php" method="POST" id="main-contact-form">
        <div class="modal fade bd-example-modal-lg" id="exampleModalDELETE<?php echo $id_cocinero;?>">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: black">Eliminando trabajador ...</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">



                        <div class="card">
                            <div class="card-header pb-0">
                                <h4 class="card-title" style="color: black">Seguro desea eliminar los datos del trabajador</h4>
                            </div>
                            <div class="card-body">
                                    <div class="card">
                                        <?php if($disponible){?>
                                            <span class="ribbon ribbon__three_disp vertical-card__menu--status">Activo <em
                                                        class="ribbon-curve"></em></span>
                                        <?php }else{?>
                                            <span class="ribbon ribbon__three vertical-card__menu--status">Inactivo <em
                                                        class="ribbon-curve"></em></span>
                                        <?php }?>
                                        <div class="card-body">
                                            <div class="text-center">
                                                <?php if($foto!=null){?>
                                                    <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="/images/<?php echo $foto;?>" width="80" height="80" alt="">
                                                <?php }else{?>
                                                    <img class="mr-3 rounded-circle mr-0 mr-sm-3" src="/images/blank1.jpg" width="80" height="80" alt="">
                                                <?php }?>

                                                <p class="mb-0" style="color: black"><strong class="">Nombre :</strong> <?php echo $nombre?></p>
                                                <p class="mb-0" style="color: black"><strong class="">CI :</strong> <?php echo $ci;?></p>
                                                <p class="mb-0" style="color: black"><strong class="">Teléfono :</strong>  <?php echo $telefono;?></p>

                                            </div>
                                        </div>
                                    </div>

                            </div>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="<?php echo $id_cocinero;?>">
                        <input type="hidden" name="foto" value="<?php echo $foto;?>">
                        <input type="hidden" id="idrestaurant" name="idrestaurant" value="<?php echo $restaurantid; ?>">
                        <input type="hidden" id="action" name="action" value="delete">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        <button  type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>