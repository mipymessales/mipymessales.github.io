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

        <div class="modal fade" id="exampleModalComboDELETE<?php echo $id_bebida;?>">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: black">Eliminando combo ...</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>


                    <div class="form-validation">
                        <form enctype="multipart/form-data" class="form-valide" action="controllers/comboController.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id_bebida;?>">
                            <input type="hidden" name="foto" value="<?php echo $foto;?>">

                    <div class="modal-body">

                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title" style="color: black">¿ Seguro que desea eliminar este elemento del menú: Combos ? </h4>
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

                                                    <?php    $listadoProductos = json_decode($fila->productos, true);
                                                    if (is_array($listadoProductos)) {   ?>
                                                  <div class='card-footer'>
                                                      <?php      foreach ($listadoProductos as $itemArray) {
                                                        foreach ($itemArray as $item) {
                                                        $name=$item["nombre"];
                                                        $cantidad=$item["cantidad"];
                                                        $precio=$item["precio"];   ?>

                                                        <p class='mb-2' style='color: black'><strong><?php echo $name  ?> x <?php echo $cantidad  ?>  </strong><span> a <?php echo $precio ?>  pesos</span></p>
                                                      <?php     }
                                                        }   ?>
                                                  </div>
                                                    <?php      }

                                                    ?>


                                                    <div class="mb-2">
                                                        <p class=""><strong>Cantidad: </strong><span><?php echo $cantidad;  ?></span></p>
                                                        <p class=""><strong>Descripcion: </strong><span><?php echo $descripcion;  ?></span></p>
                                                        <p class=""><strong>Precio total:</strong> $<span><?php echo $monto_total;  ?> cup</span></p>
                                                        <p class=""><strong>Descuento:</strong> <span><?php echo $descuento;  ?> %</span></p>
                                                        <p class=""><strong>Precio con descuento:</strong> $<span><?php echo $monto_descuento;  ?> cup</span></p>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </div>



                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="idrestaurant" id="idrestaurant" value="<?php echo $idrestaurant;?>">
                        <input type="hidden" name="id_combo" id="id_combo" value="<?php echo $id_bebida;?>">
                        <input type="hidden" name="categoria" id="categoria" value="combos">
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


