<?php
require_once "../conexion.php";     //Local
//require_once "/home/u197156154/domains/solucionventas.com/public_html/wendysfood/modelos/conexion.php";   //PROD Hostinger

$stmt = Conexion::conectar()->prepare("UPDATE venta V
                                        SET V.FECHA_VENTA = CURDATE() - INTERVAL 1 DAY
                                        WHERE V.ID_VENTA IN(SELECT V.ID_VENTA
                                                            FROM venta V
                                                            WHERE DATE_FORMAT(V.FECHA_VENTA,'%Y-%m-%d') = CURDATE()
                                                            )");

$stmt->execute();

$contador = $stmt->rowCount();

echo "Ejecucion realizada, filas afectadas: ".$contador;