<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 05/09/2021
 * Time: 12:14
 */
?>
<link href="../assets/css/dropify.min.css" rel="stylesheet">
<!--<script src="../assets/js/dropify.min.js"></script>-->
<!-- Modal EDIT-->
<div class="form-validation">

        <div class="modal fade" id="exampleModalComboEDIT<?php echo $id_bebida;?>">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: black">Editando Combos ...</h5>
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
                                    <h4 class="card-title" style="color: black">Edita los datos este elemento del menú: Combos</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 ">
                                            <?php if($foto!=null){?>
                                                <?php
                                                $target_file=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/".$foto;
                                                if (file_exists($target_file)) {  ?>
                                                    <input type="file" class="dropify" name="image_<?php echo $id_bebida; ?>" id="image_<?php echo $id_bebida; ?>" data-height="200" data-default-file="/images/<?php echo $foto;?>" />
                                                <?php  }else{?>
                                                    <input type="file" class="dropify" name="image_<?php echo $id_bebida; ?>" id="image_<?php echo $id_bebida; ?>" data-height="200" data-default-file="/images/blank1.jpg" />
                                                <?php }  ?>
                                            <?php }else{?>
                                                <input type="file" class="dropify" name="imagecombo" id="imagecombo" data-default-file="/images/blank1.jpg" />
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-form-label">
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="nombrep">Nombre <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="nombrep" name="nombrep" value="<?php echo $nombre;?>" placeholder="Inserte el nombre de la bebida..">
                                                </div>
                                            </div>
                                            <!-- TOTAL GENERAL -->
                                            <div class="total-general">

                                                <p class="display-3 mb-0" style="font-size: 1.5rem !important;"> Total sin descuento: <span id="totalBrutoedit">$<?php echo $monto_total;?></span></p><br>
                                                <div class="form-group row">

                                                    <label class="col-lg-4 col-form-label" for="descuento">Descuento (%): <span class="text-danger">*</span><br>
                                                    </label><br>
                                                    <div class="col-lg-8">
                                                        <input type="number" class="form-control" id="descuento"  value="<?php echo $descuento;?>" min="0" max="100" name="descuento" placeholder="10%">
                                                    </div>
                                                </div>
                                                <p class="display-3 mb-0" style="font-size: 1.5rem !important;">Total con descuento: <span id="totalCarritoedit">$<?php echo $monto_descuento;?></span></p><br>
                                                <p class="display-3 mb-0" style="font-size: 1.5rem !important;">Ganancia: <span id="gananciaedit">$<?php echo $ganancia;?></span></p><br>

                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="descripcion">Descripcion <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Inserte breve descripcion.." value="<?php echo $descripcion;?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="cantidad">Cantidad <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="10" value="<?php echo $cantidad;?>">
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="estado">Estado: <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <select name="estado" id="estado">
                                                        <option value="Activo" <?php (hash_equals("activo",strtolower($estado))) ?'selected':''?>>Activo</option>
                                                        <option value="Agotado" <?php (hash_equals("agotado",strtolower($estado))) ?'selected':''?>>Agotado</option>
                                                    </select>
                                                </div>
                                            </div>

                                             <div class="form-group row">
                                                 <label class="col-lg-4 col-form-label" for="expira">Fecha de caducidad <span class="text-danger">*</span>
                                                 </label>
                                                 <div class="col-lg-8">
                                                     <input type="datetime-local" class="form-control" id="expira" name="expira" value="<?php echo $expira;?>">
                                                 </div>
                                             </div>


                                            <h4 class="card-title mt-5">Estado de disponibilidad </h4>
                                            <div class="basic-form">

                                                <?php if($disponible){?>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="d" checked="true"> Disponible</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="a"> Agotado</label>
                                                <?php }else{?>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="d" > Disponible</label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="radio" id="radio" value="a" checked="true"> Agotado</label>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="categoria" id="categoria" value="combos">
                        <input type="hidden" name="idrestaurant" id="idrestaurant" value="<?php echo $idrestaurant;?>">
                        <button type="button" class="btn btn-dark text-white" data-dismiss="modal">Cancelar</button>
                        <button  type="submit" class="btn btn-warning text-white">Actualizar</button>
                    </div>
                            </form>
                        </div>


                </div>
            </div>
        </div>


</div>
<script>
        $('.dropify').dropify();
</script>
<!--end modal insert-->


