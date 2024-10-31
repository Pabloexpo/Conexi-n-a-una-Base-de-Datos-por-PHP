<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            body{
                width: 1200px;
                margin: 0 auto;
                display:flex; 
                justify-content: center
            }
        </style>
        <title>Selección de usuario</title>
    </head>
    <body>
        <form action="carrito_compra.php" method="get">


            <table border="1">

                <?php
                $link = mysqli_connect('localhost', 'super', 'alumno', 'tienda');

                if (!$link) {
                    echo 'No hay conexión';
                }

                $query = "SELECT c.cli_nombre, SUM((lv.lin_cantidad*a.art_precio_venta))
                    FROM clientes c 
                    JOIN cabeceras_ventas cv on c.cli_id = cv.cab_cliente
                    JOIN lineas_ventas lv on cv.cab_id=lv.lin_cabecera
                    JOIN articulos a on lv.lin_articulo = a.art_id
                    GROUP BY c.cli_nombre";

                $registros = mysqli_query($link, $query);

                while ($registro = mysqli_fetch_row($registros)) {

                    echo '<tr>';
                    for ($i = 0; $i < count($registro); $i++) {
                        echo '<td>' . $registro[$i] . '</td>';
                        if ($i == (count($registro)) - 1) {
                            echo '<td><button type="submit" name="boton" value="' . $registro[$i - 1] . '">Seleccionar cliente</button></td>';
                        }
                    }

                    echo '</tr>';
                }
                mysqli_close($link);
                ?>

            </table>
        </form>


    </body>
</html>
