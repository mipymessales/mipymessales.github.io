<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 05/09/2021
 * Time: 12:14
 */
?>
<!-- Modal EDIT-->

<div class="form-validation">

        <div class="modal fade" id="exampleModalDELETE<?php echo $id_bebida;?>">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Eliminando <?php echo $categoria;?> ...</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>


                    <div class="form-validation">
                        <form enctype="multipart/form-data" class="form-valide" action="controllers/categoriaController.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id_bebida;?>">
                            <input type="hidden" name="foto" value="<?php echo $foto;?>">

                    <div class="modal-body">

                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title">¿ Seguro que desea eliminar este elemento del menú: <?php echo $categoria;?> ? </h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <div class="card vertical-card__menu">


                                            <?php if($disponible){?>
                                                <span class="ribbon ribbon__three_disp vertical-card__menu--status">Disponible <em
                                                        class="ribbon-curve"></em></span>
                                            <?php }else{?>
                                                <span class="ribbon ribbon__three vertical-card__menu--status">Agotado <em
                                                        class="ribbon-curve"></em></span>
                                            <?php }?>


                                            <?php if($foto!=null){?>
                                                <div class="card-header p-0">
                                                    <div class="vertical-card__menu--image">

                                                        <img src="/images/<?php echo $foto;?> "  alt="No hay fotos">



                                                    </div>
                                                </div>
                                            <?php }else{?>
                                                <div class="card-header p-0">
                                                    <div class="vertical-card__menu--image">
                                                        <img src="/images/blank1.jpg"  alt="">
                                                    </div>
                                                </div>
                                            <?php }?>





                                            <div class="card-body">
                                                <div class="vertical-card__menu--desc">
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="vertical-card__menu--title"><?php echo $nombre;  ?></h5>


                                                    </div>
                                                    <p><?php echo $ingredientes;  ?></p>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h2 class="vertical-card__menu--price">$<span><?php echo $precio;  ?> cup</span></h2>


                                                        <?php


                                                        if(($valoracion)>0){ ?>
                                                            <div class="vertical-card__menu--rating c-pointer">



                                                                <?php  if(($valoracion)==1){ ?>
                                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                                <?php }?>

                                                                <?php  if(($valoracion)==2){ ?>
                                                                    <span class='icon'>★★</span>
                                                                <?php }?>

                                                                <?php  if(($valoracion)==3){ ?>
                                                                    <span class='icon'>★★★</span>
                                                                <?php }?>

                                                                <?php  if(($valoracion)==4){ ?>
                                                                    <span class='icon'>★★★★</span>
                                                                <?php }?>

                                                                <?php  if(($valoracion)==5){ ?>
                                                                    <span class='icon'>★★★★★</span>
                                                                <?php }?>






                                                                <!--   <span class="icon"><i class="fa fa-star"></i></span>
                                                                   <span class="icon"><i class="fa fa-star"></i></span>
                                                                   <span class="icon"><i class="fa fa-star"></i></span>
                                                                   <span class="icon"><i class="fa fa-star-o"></i></span>-->


                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </div>



                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_categoria" id="id_categoria" value="<?php echo $id_bebida;?>">
                        <input type="hidden" name="categoria" id="categoria" value="<?php echo $categoria;?>">
                        <button type="button" class="btn btn-dark text-white" data-dismiss="modal" >Cancelar</button>
                        <button  type="submit" class="btn btn-danger text-white">Eliminar</button>
                    </div>
                            </form>
                        </div>


                </div>
            </div>
        </div>


</div>

<!--end modal insert-->


