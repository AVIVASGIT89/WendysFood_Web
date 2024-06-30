<?php
require_once "../conexion.php";

$stmt = Conexion::conectar()->prepare("UPDATE venta V
                                        SET V.FECHA_VENTA = DATE(CURDATE() - 1)
                                        WHERE V.ID_VENTA IN(SELECT V.ID_VENTA
                                                            FROM venta V
                                                            WHERE DATE_FORMAT(V.FECHA_VENTA,'%Y-%m-%d') = CURDATE()
                                                            )");

$stmt->execute();

$contador = $stmt->rowCount();

echo "Ejecucion realizada, filas afectadas: ".$contador;