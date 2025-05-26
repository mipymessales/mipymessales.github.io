<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 05/09/2021
 * Time: 1:04
 */
defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
$errorUpdate="";
$errorInsert="";
$errorDelete="";
$msg="";
if (isset($_REQUEST["id_categoria"])){


    $id_bebida=$_REQUEST["id_categoria"];
    $imagess=$_REQUEST["foto"];
    $categoria=$_REQUEST["categoria"];

    if (!hash_equals($imagess,"blank1.jpg")){
        $target_file=str_replace("'\'","/", $_SERVER['DOCUMENT_ROOT'])."/images/".$imagess;
        unlink($target_file);
    }




    include_once ROOT_DIR."pdo/conexion.php";
    global $base_de_datos;
    $query="DELETE FROM ".$categoria." WHERE id=:id_bedida";
    $sentencia = $base_de_datos->prepare($query);
    $sentencia->bindParam(':id_bedida',$id_bebida);
    $sentencia->execute();

    if (!$sentencia) {
        #No existe
        $errorDelete= "¡Error al eliminar el elemento del menú:".$categoria." ! </br>";
        //  exit();
    }else {
        header("Location: ../index.php?section=ofertas&categoria=".$categoria);
        // $_SESSION["user"] = $userlogueado;
        # Redireccionar a la lista

       /* echo "<script type='text/javascript'>
window.location=('../index.php?section=ofertas');

</script>";*/
    }

}else{
    if (!isset($_REQUEST["nombrep"]) || !isset($_REQUEST["ingredientes"]) || !isset($_REQUEST["precio"]) || !isset($_REQUEST["radio"])) {
        exit();
    }

$categoria=$_REQUEST["categoria"]??"";

$nombrep = $_REQUEST["nombrep"];
$ingredientes = $_REQUEST["ingredientes"];
$precio = $_REQUEST["precio"];
$radio = $_REQUEST["radio"];
$imagess=$_REQUEST["foto"]??"blank1.jpg";

$target_dir = "../images/"; //directorio en el que se subira
$target_file = $target_dir .  basename($_FILES["image"]["name"]);//se añade el directorio y el nombre del archivo
$uploadOk = 1;//se añade un valor determinado en 1
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Comprueba si el archivo de imagen es una imagen real o una imagen falsa
//if(isset($_POST["submit"])) {//detecta el boton


    //ImageResize(250, 250,    basename($_FILES["image"]["name"]));

if($_FILES["image"]["tmp_name"]!=null)
    $check = getimagesize($_FILES["image"]["tmp_name"]);
else{
    if (empty($imagess))
      $check=false;
}


if($check !== false) {//si es falso es una imagen y si no lanza error
    //echo "Archivo es una imagen- " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    $msg.= "El archivo no es una imagen </br>";
    $uploadOk = 0;
}
//}
// Comprobar si el archivo ya existe
if (file_exists($target_file)) {
    //$uploadOk = 0;//si existe lanza un valor en 0
    if(!empty(basename($_FILES["image"]["name"]))){
      //  $uploadOk=1;
        $imagess=basename($_FILES["image"]["name"]);
    }

}else{
// Comprueba el peso
if ($_FILES["image"]["size"] > 500000) {
    $msg.=  "El archivo es muy pesado </br>";
    $uploadOk = 0;
}
// Permitir ciertos formatos de archivo
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    $msg.=  "Solo, JPG, JPEG, PNG & GIF Estan soportados </br>";
    $uploadOk = 0;
}
//Comprueba si $ uploadOk se establece en 0 por un error


if ($uploadOk == 0) {
    $msg.=  "El archivo no se subio </br>";
// si todo está bien, intenta subir el archivo
} else {

    // include_once "imageclass.php";

    if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        // Ok para continuar
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imagess=basename($_FILES["image"]["name"]);
            echo "El archivo ". basename( $_FILES["image"]["name"]). " Se subio correctamente";
        } else {
            $msg.=  "Error al cargar el archivo";

        }
    } else {
        $msg.=  "Error al subir la imagen: " . $_FILES["image"]["error"]."</br>";
    }


}
}

    defined('ROOT_DIR') || define('ROOT_DIR',dirname(__FILE__,2).'/');
    include_once ROOT_DIR."pdo/conexion.php";
global $base_de_datos;
if (isset($_REQUEST["insertar"])){
    $categoria=$_REQUEST["categ"];
    $sentencia = $base_de_datos->prepare("INSERT INTO ".$categoria." (nombre, ingredientes, precio, disponible, tipo, foto) VALUES (:nombrep, :ingredientes, :precio, :disponible,:tipo, :image)");
    $errorInsert= $msg;
    $sentencia->bindParam(':tipo',$categoria);
}else{
    $sentencia = $base_de_datos->prepare("UPDATE ".$categoria." SET  nombre=:nombrep, ingredientes=:ingredientes, precio=:precio, disponible=:disponible, foto=:image WHERE id=:id_bedida");
    $id_bebida=$_REQUEST["id"];
    $sentencia->bindParam(':id_bedida',$id_bebida);
    $errorUpdate=$msg;
}

//$sentencia = $base_de_datos->prepare("UPDATE bebidas SET (nombre, ingredientes, precio, disponible, tiempo_elavoracion, foto) VALUES (:nombrep, :ingredientes, :precio, :disponible, :elav, 5, :image)");
$sentencia->bindParam(':nombrep',$nombrep);
$sentencia->bindParam(':ingredientes',$ingredientes);
$sentencia->bindParam(':precio',$precio);


$d=null;
if($radio=="a"){
    $d=0;
    $sentencia->bindParam(':disponible',$d);
}else{
    $d=1;
    $sentencia->bindParam(':disponible',$d);
}

$sentencia->bindParam(':image',$imagess);






try{
    $sentencia->execute();
}catch (Exception $e){
    echo  print_r($e->getTraceAsString());
    if (isset($_REQUEST["insertar"])){
        $errorInsert.= print_r($e->getTraceAsString());
    }else{
        $errorUpdate.=print_r($e->getTraceAsString());;
    }
}


if (!$sentencia) {
    #No existe
   // echo "¡Error al actualizar la categoria! </br>";
    if (!empty($errorDelete))
    header("Location: ../index.php?section=ofertas&categoria=".$categoria."&errorDelete=".$errorDelete);
    if (!empty($errorInsert))
        header("Location: ../index.php?section=ofertas&categoria=".$categoria."&errorInsert=".$errorInsert);
    if (!empty($errorUpdate))
        header("Location: ../index.php?section=ofertas&categoria=".$categoria."&errorUpdate=".$errorUpdate);
    else
        header("Location: ../index.php?section=ofertas&categoria=".$categoria);
  //  exit();
}else{

    // $_SESSION["user"] = $userlogueado;
    # Redireccionar a la lista
    if (!empty($errorDelete)){
        header("Location: ../index.php?section=ofertas&categoria=".$categoria."&errorDelete=".$errorDelete);
    }elseif (!empty($errorInsert)){
        header("Location: ../index.php?section=ofertas&categoria=".$categoria."&errorInsert=".$errorInsert);
    }elseif (!empty($errorUpdate)){
        header("Location: ../index.php?section=ofertas&categoria=".$categoria."&errorUpdate=".$errorUpdate);
    }else{
        header("Location: ../index.php?section=ofertas&categoria=".$categoria);
    }

 /*   echo "<script type='text/javascript'>
window.location=('../index.php?section=ofertas&categoria='.$categoria);

</script>";*/
}


}
function ImageResize($width, $height, $img_name)
{
    /* Get original file size */
    list($w, $h) = getimagesize("../images/".$img_name);


    /*$ratio = $w / $h;
    $size = $width;

    $width = $height = min($size, max($w, $h));

    if ($ratio < 1) {
        $width = $height * $ratio;
    } else {
        $height = $width / $ratio;
    }*/

    /* Calculate new image size */
    $ratio = max($width/$w, $height/$h);
    $h = ceil($height / $ratio);
    $x = ($w - $width / $ratio) / 2;
    $w = ceil($width / $ratio);
    /* set new file name */
    $path = $img_name;


    /* Save image */
    if($_FILES['image']['type']=='image/jpeg')
    {
        /* Get binary data from image */
        $imgString = file_get_contents(ROOT_DIR."images/".$img_name);
        /* create image from string */
        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
        imagejpeg($tmp, $path, 100);
    }
    else if($_FILES['image']['type']=='image/png')
    {
        $image = imagecreatefrompng($_FILES['image']['tmp_name']);
        $tmp = imagecreatetruecolor($width,$height);
        imagealphablending($tmp, false);
        imagesavealpha($tmp, true);
        imagecopyresampled($tmp, $image,0,0,$x,0,$width,$height,$w, $h);
        imagepng($tmp, $path, 0);
    }
    else if($_FILES['image']['type']=='image/gif')
    {
        $image = imagecreatefromgif($_FILES['image']['tmp_name']);

        $tmp = imagecreatetruecolor($width,$height);
        $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $transparent);
        imagealphablending($tmp, true);

        imagecopyresampled($tmp, $image,0,0,0,0,$width,$height,$w, $h);
        imagegif($tmp, $path);
    }
    else
    {
        return false;
    }


    imagedestroy($image);
    imagedestroy($tmp);
    return true;
}
?>