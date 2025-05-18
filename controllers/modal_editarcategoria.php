<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 05/09/2021
 * Time: 12:14
 */
?>
<!-- Modal EDIT-->
<link href="assets/css/dropify.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/dropify.min.js"></script>
<script src="assets/js/dropify-init.js"></script>
<div class="form-validation">

        <div class="modal fade" id="exampleModalEDIT<?php echo $id_bebida;?>">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editando <?php echo $categoria;?> ...</h5>
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
                                    <h4 class="card-title">Edita los datos este elemento del menú: <?php echo $categoria;?></h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 ">


                                            <?php if($foto!=null){?>

                                                <?php
                                                $target_file=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/mipymessales/images/".$foto;
                                                if (file_exists($target_file)) {  ?>
                                                    <input type="file" class="dropify" name="image" id="image" data-default-file="/mipymessales/images/<?php echo $foto;?>" />
                                                <?php  }else{?>
                                                    <input type="file" class="dropify" name="image" id="image" data-default-file="/mipymessales/images/blank1.jpg" />
                                                <?php }  ?>



                                            <?php }else{?>
                                                <input type="file" class="dropify" name="image" id="image" data-default-file="/mipymessales/images/blank1.jpg" />

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

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="ingredientes">Ingredientes <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <textarea class="form-control" id="ingredientes" name="ingredientes"  rows="5" placeholder="Breve descripción de los ingrendientes separados por coma ( , )" value="<?php echo $ingredientes; ?>"><?php echo $ingredientes; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label" for="precio">Precio <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="precio" name="precio" value="<?php echo $precio;?>" placeholder="$21.60">
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
                        <input type="hidden" name="categoria" id="categoria" value="<?php echo $categoria;?>">
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
    window.onload = function () {
        $('.dropify').dropify();
    };
</script>
<!--end modal insert-->


