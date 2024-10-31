<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <style>
        .botones {
            display: flex;
            flex-direction: row;
        }
        body {
            width: 1200px; 
            margin: 0 auto; 
            display: flex; 
            justify-content: center; 
        }
    </style>
</head>
<body>
    <?php
    // Conexión a la base de datos
    $link = mysqli_connect('localhost', 'super', 'alumno', 'tienda');

    if (!$link) {
        die('No hay conexión');
    }

    // Obtener el cliente desde los parámetros GET
    $cliente = empty($_GET['oculto']) ? $_GET['boton'] : $_GET['clienteOculto'];

    // Inserción en la base de datos cuando se hace clic en "Agregar"
    if (isset($_GET['opcion']) && isset($_GET['cantidad'])) {
        $opcion = $_GET['opcion'];
        $cantidad = $_GET['cantidad'];

        // Encontrar ID del cliente
        $query = "SELECT c.cli_id FROM clientes c WHERE c.cli_nombre='$cliente'";
        $registros = mysqli_query($link, $query);
        $id = mysqli_fetch_row($registros)[0];

        // Encontrar ID del producto
        $query = "SELECT a.art_id FROM articulos a WHERE a.art_nombre= '$opcion'";
        $registros = mysqli_query($link, $query);
        $idpto = mysqli_fetch_row($registros)[0];

        // Insertar en carritos_compra
        $update = "INSERT INTO carritos_compra (car_articulo, car_cliente, car_cantidad) VALUES ($idpto, $id, $cantidad)";
        mysqli_query($link, $update);
    }
    ?>

    <!-- Formulario para mostrar el carrito -->
    <form action="index.php" method="get">
        <table border="1">
            <?php
            // Consulta para mostrar los artículos en el carrito del cliente
            $query = "SELECT a.art_nombre, cc.car_cantidad
                      FROM articulos a 
                      JOIN carritos_compra cc ON a.art_id = cc.car_articulo
                      JOIN clientes c ON cc.car_cliente = c.cli_id
                      WHERE c.cli_nombre = '$cliente'";

            $registros = mysqli_query($link, $query);

            while ($registro = mysqli_fetch_row($registros)) {
                echo '<tr>';
                foreach ($registro as $campo) {
                    echo '<td>' . $campo . '</td>';
                }
                echo '</tr>';
            }
            ?>
        </table>
        <br>
        <div class="botones">
            <button type="submit">Volver atrás</button>
        </div>
    </form>

    <!-- Formulario para agregar productos -->
    <form action="" method="get">
        <input type="hidden" name="oculto" value="<?php echo $cliente; ?>">
        <input type="hidden" name="clienteOculto" value="<?php echo $cliente; ?>">
        
        <?php echo "Elige los productos que quieres añadir a la cesta, $cliente <br>"; ?>
        
        <?php
        // Consulta para listar los productos disponibles
        $query2 = "SELECT a.art_nombre FROM articulos a";
        $registros2 = mysqli_query($link, $query2);
        echo '<select name="opcion">';

        while ($registro = mysqli_fetch_row($registros2)) {
            echo '<option value="' . $registro[0] . '">' . $registro[0] . '</option>';
        }
        echo '</select>';
        ?>

        <label for="cantidad"></label>
        <input type="number" name="cantidad" placeholder="Cantidad" required>
        <br>
        <button type="submit" name="agregar">Agregar</button>
    </form>

    <?php
    // Cerrar la conexión a la base de datos
    mysqli_close($link);
    ?>
</body>
</html>
