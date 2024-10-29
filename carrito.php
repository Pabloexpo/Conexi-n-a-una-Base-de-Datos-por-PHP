<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>

            .botones{
                display: flex;
                flex-direction: row;
            }
        </style>

    </head>
    <body>
        <form action="index.php" method="post">
            <table border="1">
                <?php
                $cliente = isset($_GET['boton']) ? $_GET['boton'] : null;

                $link = mysqli_connect('localhost', 'super', 'alumno', 'tienda');

                if (!$link) {
                    echo 'No hay conexión';
                }


                $query = "SELECT a.art_nombre, cc.car_cantidad
                FROM articulos a 
                JOIN carritos_compra cc on a.art_id = cc.car_articulo
                JOIN clientes c on cc.car_cliente=c.cli_id
                WHERE c.cli_nombre='$cliente'";

                $registros = mysqli_query($link, $query);

                while ($registro = mysqli_fetch_row($registros)) {
                    echo '<tr>';
                    for ($i = 0; $i < count($registro); $i++) {
                        echo '<td>' . $registro[$i] . '</td>';
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

        <form action="" method="get">
            <input type="hidden" name="clienteOculto" value="<?php echo $cliente; ?>">
            <input type="hidden" name="idptoOculto" value="<?php echo $idpto; ?>">
            <input type="hidden" name="idOculto" value="<?php echo $id; ?>">
            <input type="hidden" name="cantidadOculta" value="<?php echo $cantidad; ?>">
           
            <?php echo "Elige los productos que quieres añadir a la cesta, $cliente <br>"; ?>

            <?php
            echo '<br>';
            //consulta para ver los ptos en disponibiilidad
            $query2 = "SELECT a.art_nombre FROM articulos a";
            $registros2 = mysqli_query($link, $query2);
            echo '<select name="opcion">';

            while ($registro = mysqli_fetch_row($registros2)) {
                echo '<option value="' . $registro[0] . '">' . $registro[0] . '</option>';
            }

            echo '</select>';

            echo '<label for="cantidad"></label>
                <input type="number" name="cantidad" placeholder="cantidad">
                <br>';

            //vamos a agregar a la cesta de $cliente (su id) el producto option elegido
//            y la cantidad

            $opcion = isset($_GET['opcion']) ? $_GET['opcion'] : null;

            $cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : null;

            //encontramos id del cliente 

            $query = "SELECT c.cli_id FROM clientes c WHERE c.cli_nombre='$cliente'";

            $registros = mysqli_query($link, $query);

            while ($registro = mysqli_fetch_row($registros)) {
                $idOculto = $registro [0];
            }
            ?>
            <input type="hidden" name="oculto" value="<?php echo $idOculto; ?>">

            <?php
            $id = $_GET['oculto'];

//encontramos id del producto

            $query = "SELECT a.art_id FROM articulos a WHERE a.art_nombre= '$opcion'";

            $registros = mysqli_query($link, $query);

            while ($registro = mysqli_fetch_row($registros)) {
                $idpto = $registro [0];
            }
            ?>

            <button type="submit" name='agregar'>Agregar</button>


            <?php
//agregamos lo elegido

            $update = "INSERT INTO carritos_compra (car_articulo, car_cliente, car_cantidad) VALUES ($idpto, $id, $cantidad)";

            mysqli_query($link, $update);

//guardamos al cliente en una oculta para volver a buscarlo
            $clienteActualizado = $_GET['clienteOculto'];

//comprobamos 
            $query = "SELECT a.art_nombre, cc.car_cantidad
                FROM articulos a 
                JOIN carritos_compra cc on a.art_id = cc.car_articulo
                JOIN clientes c on cc.car_cliente=c.cli_id
                WHERE c.cli_nombre='$clienteActualizado'";

            echo "<br>Contenido del carrito de compra de $clienteActualizado";

            $registros = mysqli_query($link, $query);
            echo '<table border="1"';
            while ($registro = mysqli_fetch_row($registros)) {
                echo '<tr>';
                for ($i = 0; $i < count($registro); $i++) {
                    echo '<td>' . $registro[$i] . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

            echo 'datos actualizados';
            ?>
        </form>
    </body>
</html>
