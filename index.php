<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//Si el archivo existe
if(file_exists("archivo.txt")){ 

    // leemos y almacenamos el contenido de archivo.txt en la variable $JsonTareas (fopen, fread, fclose)
    $JsonTareas = file_get_contents("archivo.txt"); 

    //Convertir el json a $aTareas (convierto un string en un array)
    $aTareas = json_decode($JsonTareas, true); 

} else {
    //si no, array vacio (no hay tareas)
    $aTareas = array(); 
}

if(isset($_GET["id"]) && $_GET["id"] >= 0){ //tambien se puede hacer: $id = isset($_GET["id"]) && $_GET["id"] >= 0? $_GET["id"] : "";
    $id = $_GET["id"];
} else {
    $id = "";
}

if($_POST){
    $prioridad = $_POST["lstPrioridad"];
    $usuario = $_POST["lstUsuario"];
    $estado = $_POST["lstEstado"];
    $titulo = $_POST["txtTitulo"];   
    $descripcion = $_POST["txtDescripcion"];

    if($id >= 0){
        //editar tarea
        $aTareas[$id] = array(
            "fecha" => $aTareas[$id]["fecha"],
            "prioridad" => $prioridad,
            "usuario" => $usuario,
            "estado" => $estado,
            "titulo" => $titulo,
            "descripcion" => $descripcion
        );

    } else {
        //insertar tarea
        $aTareas[] = array(
            "fecha" => date("d/m/Y"),
            "prioridad" => $prioridad,
            "usuario" => $usuario,
            "estado" => $estado,
            "titulo" => $titulo,
            "descripcion" => $descripcion
        );
    }
    //Convertir el array de aTareas en json (convierto un array a un string)
    $JsonTareas = json_encode($aTareas);

    //almacenamos el json en el archivo (fopen, fwrite y fclose. si el archivo no existe se crea, si ya existe se sobrescribe)
    file_put_contents("archivo.txt", $JsonTareas);
}

if (isset($_GET["do"]) && $_GET["do"] == "eliminar"){

    //destruimos las variables seleccionadas
    unset($aTareas[$id]);

    //Convertir aTareas en json (para guardar los cambios (eliminar en este caso))
    $JsonTareas = json_encode($aTareas);

    //almacenamos el json en el archivo (en este caso "eliminar" existe, asi q borraremos)
    file_put_contents("archivo.txt", $JsonTareas);

    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <title>Gestor de tareas</title>
</head>
<body>

    <main class="container">
        <div class="row">
            <div class="col-12 py-4 text-center">
                <h1>Gestor de tareas</h1>
            </div>
        </div>
        <div class="row pb-3">
            <div class="col-12">
                <form action="" method="post" class="form">
                    <div class="row">
                        <div class="py-1 col-4">
                            <label for="lstPrioridad">Prioridad</label>
                            <select class="form-control" name="lstPrioridad" id="lstPrioridad" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Alta" <?php echo isset($aTareas[$id]) && $aTareas[$id]["prioridad"] == "Alta"? "selected": "";?> >Alta</option>
                                <option value="Media"<?php echo isset($aTareas[$id]) && $aTareas[$id]["prioridad"]  == "Media"? "selected":"";?> >Media</option>
                                <option value="Baja" <?php echo isset($aTareas[$id]) && $aTareas[$id]["prioridad"]  == "Baja"? "selected": "";?> >Baja</option>
                            </select>
                        </div>
                        <div class="py-1 col-4">
                            <label for="lstUsuario">Usuario</label>
                            <select class="form-control" name="lstUsuario" id="lstUsuario" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Ana" <?php echo isset($aTareas[$id]) && $aTareas[$id]["usuario"] == "Ana" ? "selected" : "";?> >Ana</option>
                                <option value="Bernabe" <?php echo isset($aTareas[$id]) && $aTareas[$id]["usuario"] == "Bernabe" ? "selected" : "";?> >Bernabe</option>
                                <option value="Daniela" <?php echo isset($aTareas[$id]) && $aTareas[$id]["usuario"] == "Daniela" ? "selected" : "";?> >Daniela</option>
                            </select>
                        </div>
                        <div class="py-1 col-4">
                            <label for="lstEstado">Estado</label>
                            <select  class="form-control" name="lstEstado" id="lstEstado" required>
                                <option value="" disabled selected>Seleccionar</option>
                                <option value="Sin asignar" <?php echo isset($aTareas[$id]) && $aTareas[$id]["estado"] == "Sin asignar" ? "selected" : "";?> >Sin asignar</option>
                                <option value="Asignado" <?php echo isset($aTareas[$id]) && $aTareas[$id]["estado"] == "Asignado" ? "selected" : ""; ?> >Asignado</option>
                                <option value="En proceso" <?php echo isset($aTareas[$id]) && $aTareas[$id]["estado"] == "En proceso" ? "selected" : ""; ?> >En proceso</option>
                                <option value="Terminado" <?php echo isset($aTareas[$id]) && $aTareas[$id]["estado"] == "Terminado" ? "selected" : ""; ?> >Terminado</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 py-1">
                            <label for="txtTitulo">Título</label>
                            <input type="text" name="txtTitulo" id="txtTitulo" class="form-control" required value="<?php echo isset($aTareas[$id])? $aTareas[$id]["titulo"] :""; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 py-1">
                            <label for="txtDescripcion">Descripción</label>
                            <textarea name="txtDescripcion" id="txtDescripcion" class="form-control" required><?php echo isset($aTareas[$id])? $aTareas[$id]["descripcion"] : ""; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 py-2 text-center">
                            <button type="submit" id="btnEnviar" name="btnEnviar" class="btn btn-primary">ENVIAR</button>
                             <a href="index.php" class="btn btn-secondary">CANCELAR</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
                
        <?php if(count($aTareas)): ?>
        <div class="row">
            <div class="col-12 pt-3">
                <table class="table table-hover border">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha de inserción</th>
                        <th>Título</th>
                        <th>Prioridad</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>                        
                        <?php foreach($aTareas as $pos => $tarea): ?> 
                        <tr>
                            <td><?php echo $pos?></td>
                            <td><?php echo $tarea["fecha"]; ?></td>
                            <td><?php echo $tarea["titulo"]; ?></td>
                            <td><?php echo $tarea["prioridad"]; ?></td>
                            <td><?php echo $tarea["usuario"]; ?></td>
                            <td><?php echo $tarea["estado"]; ?></td>
                            <td>
                                <a href="index.php?id=<?php echo $pos ?>&do=editar" class="btn btn-secondary"><i class="bi bi-pencil-fill"></i></a>
                                <a href="index.php?id=<?php echo $pos ?>&do=eliminar" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        Aún no se han cargado tareas.
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
        
</body>
</html>