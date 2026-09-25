<?php
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	setlocale(LC_ALL,'es_ES.UTF-8','Spanish_Spain.1252');
    // Productos para el sorteo con descripciones
$productos = [     
    "Casino"  => "Gonbidatzen zaitugu gure Casinora",   
    "BARRIRO?"  => "Beste aukera bat daukazu!!!",    
    "Ruta_Magica"  => "Gure ruta magikoak ikusi nahi dituzu?",    
    "Viajes_Trullijo"  => "Gure bidaiak hemen dituzu!!",    
    "Adventure"  => "Aventura barri bat bizitzeko prest?",    
    "PCCOM"  => "Ordenagailua konpondu edo berri bat behar!!!",
    "AreitioSport"  => "Gure ruta magikoak ikusi nahi dituzu?",    
    "DrogaOsasuna"  => "Drogen inguruan informaziorik nahi?",    
    "Pitahaya"  => "Informatikako denda hemen zure eskura",    
    "Txoricoffe"  => "Behar dituzun erosotazun guztiekin lan egin nahi?",   
    "Casa_Empeños"  => "Zerbaitetan lagundu zaitzakegu?",  
    "OPARIA!!!"  => "Opari bat suertatu zaizu!!!"
];

// Crear un array de 12 productos alternando los 4 originales
$productosRepetidos = array_merge(...array_fill(0, 1, array_keys($productos)));

// Función para obtener un producto aleatorio
function obtenerProductoAleatorio($productos) {
    return array_rand($productos);
}

// Manejar la solicitud AJAX
if(isset($_POST['girar'])) {
    $ganador = obtenerProductoAleatorio($productos);
    echo json_encode(['ganador' => $ganador , 'descripcion' => $productos[$ganador]]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruleta de Sorteo - Politeknika Txorierri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F0F0F0;
            color: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        h1 {
            color: #00d084;
            margin-bottom: 20px;
        }
        .ruleta-container {
            width: 300px;
            height: 300px;
            position: relative;
            margin: 0 auto;
        }
        .ruleta {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
            border: 10px solid #00d084;
            transition: transform 5s cubic-bezier(0.25, 0.1, 0.25, 1);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        .sector {
            position: absolute;
            width: 0;
            height: 0;
            left: 50%;
            top: 50%;
            transform-origin: left top;
            border-style: solid;
            border-width: 150px 86.6px 0;
            border-color: transparent;
            border-top-color: inherit;
        }
        .sector span {
            position: absolute;
            left: -43.3px;
            top: -130px;
            width: 86.6px;
            text-align: center;
            transform: rotate(30deg);
            font-size: 12px;
            font-weight: bold;
            color: #FFFFFF;
        }
        #girar {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #00d084;
            color: #FFFFFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #girar:hover {
            background-color: #00b873;
        }
        #resultado {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #00d084;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ruleta de Sorteo - Politeknika Txorierri</h1>
        <div class="ruleta-container">
            <div class="ruleta" id="ruleta">
                <?php 
                $totalSectores = count($productosRepetidos);
                foreach($productosRepetidos as $index => $producto): 
                    $angulo = $index * (360 / $totalSectores);
                    $color = '';
                    switch($producto) {
                        case 'OPARIA!!!': $color = '#00d084'; break; // Nuevo verde
                        case 'BARRIRO?': $color = '#0538f4'; break; // 
                        case 'Ruta_Magica': $color = '#FF0000'; break; // Rojo
                        case 'Viajes_Trullijo': $color = '#0000FF'; break; // Azul
                        case 'Adventure': $color = '#FFA500'; break; // Naranja
                        case 'PCCOM': $color = '#e2f405'; break; // 
                        case 'AreitioSport': $color = '#f405d3'; break; // 
                        case 'DrogaOsasuna': $color = '#05f431'; break; // 
                        case 'Pitahaya': $color = '#a305f4'; break; // 
                        case 'Txoricoffe': $color = '#f45205'; break; // 
                        case 'Casino': $color = '#f4cd4b'; break; //                          
                        case 'Casa_Empeños': $color = '#def163'; break; // 
                    }
                ?>
                    <div class="sector" style="transform: rotate(<?= $angulo ?>deg); border-top-color: <?= $color ?>;">
                        <span>
                            <?= $producto ?><br>
                            <!--small> = substr($productos[$producto], 0, 20) ?>...</small-->
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button id="girar">Girar Ruleta</button>
        <div id="resultado"></div>        
        <div id="resultadoEnlace"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#girar').click(function() {
                var grados = Math.floor(Math.random() * 360) + 1440; // Al menos 4 vueltas completas
                $('#ruleta').css('transform', 'rotate(' + grados + 'deg)');
                
                setTimeout(function() {
                    $.post('', {girar: true}, function(data) {
                        var resultado = JSON.parse(data);
                        $('#resultado').html('¡El resultado es: <strong>' + resultado.ganador + '</strong>!<br><small>' + resultado.descripcion + '</small>');
                      // alert("ZURE EMAITZA:" +  resultado.ganador + "   " + resultado.descripcion);
                        switch (resultado.ganador){
                            case "Casino":
                                window.open('/IWEB/Erakustxori/Casino/index.html', '_blank'); 
                                break;
                           // case "BARRIRO?":
                             //   window.location.href = '/IWEB/Erakustxori/RuletaWeb.php';
                               // break;
                            case "Ruta_Magica":
                                window.open('/IWEB/Erakustxori/RutaMagica/weborrialdea.html', '_blank'); 
                                break;
                            case "Viajes_Trullijo":
                                window.open('/IWEB/Erakustxori/ViajesTrullijo/index.html', '_blank'); 
                                break;
                            case "Adventure":
                                window.open('/IWEB/Erakustxori/Adventure/index.html', '_blank'); 
                                break;
                            case "PCCOM":
                                window.open('/IWEB/Erakustxori/PCCOM/Paginaweb.html', '_blank'); 
                                break;
                            case "AreitioSport":
                                window.open('/IWEB/Erakustxori/AreitioSport/index.html', '_blank'); 
                                break;
                            case "DrogaOsasuna":
                                window.open('/IWEB/Erakustxori/DrogaOsasuna/index.html', '_blank'); 
                                break;
                            case "Pitahaya":
                                window.open('/IWEB/Erakustxori/Pitahaya/Proiekto-Pitahaya/index.html', '_blank'); 
                                break;
                            case "Txoricoffe":
                                window.open('/IWEB/Erakustxori/Txoricoffe/inicio.html', '_blank'); 
                                break;
                            case "Casa_Empeños":
                                window.open('/IWEB/Erakustxori/CasaApuestas/index.html', '_blank'); 
                                break;        
                            default: //OPARIA
                                //window.location.href = '/IWEB/Erakustxori/RuletaWeb.php';
                        }
                    });
                }, 5000); // Esperar 5 segundos para mostrar el resultado
            });
        });


  /*      $(document).on('click', '#miEnlace', function (e) {
            e.preventDefault(); // Evita la acción predeterminada
            window.open('/IWEB/Erakustxori/1Talde/weborrialdea.html', '_blank'); // Abre en otra pestaña
            window.location.href = '/IWEB/Erakustxori/RuletaWeb.php'; // Redirige a la URL
        });*/

    </script>


<!--a href="/IWEB/Erakustxori/Casino/index.html">Prueba directa</a-->
<br>
<pp><a href="/IWEB/Erakustxori/RuletaWeb.php">BARRIZ HASI</a></>
</body>
</html>