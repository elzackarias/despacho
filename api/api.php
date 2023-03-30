<?php
date_default_timezone_set('America/Mexico_City');
require_once('lib/config.php');
$token = 'NgappLBhrR94sBnXHd6TBj6qBrH9';
if(!isset($_GET['type'])){
    http_response_code(400);
}else{
    $json =  file_get_contents('php://input');
    $obj = json_decode($json,true);
    /*$tk = $obj['token'];
    if($token != $tk){
        http_response_code(400);
        exit;
    }*/
    //Tipo de solicitud (Login, Register, Delete, Consulta, Update)
    $tipo = $_GET['type'];

    switch ($tipo) {
        case 'q':
            $consulta = $_GET['que'];

            switch ($consulta){
                case 'establecimientos':
                    //Si esta seteado algun establecimiento damos datos solo de ese establecimeinto => datos tienda y prod
                    if(isset($_GET['est'])){
                        $establecimiento = $_GET['est'];
                        $q = mysqli_query($connect,"SELECT * FROM establecimientos WHERE id = '$establecimiento'");
                        $q_prod = mysqli_query($connect,"SELECT * FROM productos WHERE establecimiento = '$establecimiento'");
                        //$arr = [];
                        $prod = [];

                        while($dato = mysqli_fetch_assoc($q)){
                            $arr = array(
                                "id" => $dato['id'],
                                "nombre" =>  $dato['nombre'],
                                "logo" => $dato['logo'],
                                "banner" => $dato['banner'],
                            );
                        }

                        while($dato_p = mysqli_fetch_assoc($q_prod)){
                            array_push($prod,array("id" => $dato_p['id_prod'], "nombre" => $dato_p['nombre'], "precio" => $dato_p['precio']));
                        }

                        $arreglo= array(
                            "datos" => $arr,
                            "productos" => $prod,
                        );

                        echo json_encode($arreglo);

                    }else{
                        $q = mysqli_query($connect,"SELECT * FROM establecimientos");
                        $arr = [];

                        while($dato = mysqli_fetch_assoc($q)){
                            $arr[] = array(
                                "id" => $dato['id'],
                                "nombre" =>  $dato['nombre'],
                                "logo" => $dato['logo'],
                                "banner" => $dato['banner']
                            );
                        }

                        echo json_encode($arr);
                    }
                break;
                default:
                    # code...
                    break;
            }

            break;

        case 'login':
            $email = $obj['email'];
            $email = mysqli_real_escape_string($connect,$email);
		    $email = strip_tags($email);
		    $email = trim($email);
		    $email = strtolower($email);
            $password = $obj['password'];
            $query = mysqli_query($connect,"SELECT * FROM usuarios WHERE email = '$email'");
            if(mysqli_num_rows($query) == 1){
                $q_envio = mysqli_query($connect,"SELECT costo_envio FROM configuracion WHERE id = 1");
                $envio = mysqli_fetch_assoc($q_envio);
                while($row = mysqli_fetch_assoc($query)){
                    if($email = $row['email'] && password_verify($password, $row['password'])){
                    	$direccion = [];
                    	$iduser = $row['id'];
                        $arr = array(
                            "status" => 'OK',
                            "id" => $row['id'],
                            "envio" => $envio['costo_envio'],
                            "nombre" => $row['nombre'],
                            "email" => $row['email'],
                            "telefono" => $row['telefono']
                        );

                        //Buscamos direcciones
                        $dir = mysqli_query($connect,"SELECT * FROM direcciones WHERE user_id = '$iduser'");
                        if(mysqli_num_rows($dir) == 0){
                        	//array_push($prod,array(null,0));
                        }else{

		                    while($direcc = mysqli_fetch_assoc($dir)){
		                        array_push($direccion,array("id" => $direcc['id'], "titulo" => $direcc['titulo'], "latitud" => $direcc['latitud'], "longitud" => $direcc['longitud'], "referencias" => utf8_encode($direcc['referencias'])));
		                    }

                        }

	                        $arreglo= array(
	                            "datos" => $arr,
	                            "direcciones" => $direccion,
	                        );

	                        echo json_encode($arreglo);
                    }else{
                        $arr = array(
                            "status" => 'ERROR',
                            "titulo" => 'Ops...',
                            "msg" => 'La contraseña que ingresaste es incorrecta :/'
                        );
                        echo json_encode($arr);
                    }
                }
            }else{
                $arr = array(
                    "status" => 'ERROR',
                    "titulo" => 'Ops...',
                    "msg" => utf8_encode('El usuario no existe :o')
                );
                echo json_encode($arr);
            }          
            break;

        case 'register':
            $email = $obj['email'];
            $email = mysqli_real_escape_string($connect,$email);
		    $email = strip_tags($email);
		    $email = trim($email);
		    $email = strtolower($email);

		    $nombre = $obj['nombre'];
		    $nombre = strip_tags($nombre);

		    $telefono = $obj['telefono'];
		    $telefono = mysqli_real_escape_string($connect,$telefono);
		    $telefono = trim($telefono);
		    $telefono = strip_tags($telefono);

		    $IDcel = $obj['IDcel'];

            $contrasena = $obj['password'];
            $password = password_hash($contrasena, PASSWORD_ARGON2I);

            $query = mysqli_query($connect,"SELECT * FROM usuarios WHERE email = '$email'");
            $query1 = mysqli_query($connect,"SELECT * FROM usuarios WHERE telefono = '$telefono'");
            if(mysqli_num_rows($query) == 1){
                $arr = array(
                    "status" => 'ERROR',
                    "titulo" => 'Ops...',
                    "msg" => 'El email ya está registrado :/'
                );
                echo json_encode($arr);
            }elseif(mysqli_num_rows($query1) == 1){
                $arr = array(
                    "status" => 'ERROR',
                    "titulo" => 'Ops...',
                    "msg" => 'El teléfono ya está registrado :/'
                );
                echo json_encode($arr);
            }elseif(is_numeric($telefono) != 1){
                $arr = array(
                    "status" => 'ERROR',
                    "titulo" => 'Ops...',
                    "msg" => 'El número de teléfono no es correcto :/'
                );
                echo json_encode($arr);
            }else{
                $q_envio = mysqli_query($connect,"SELECT costo_envio FROM configuracion WHERE id = 1");
                $envio = mysqli_fetch_assoc($q_envio);
            	//AQUI SE INSERTA LA INFORMACION
            	$q = mysqli_query($connect,"INSERT INTO usuarios (nombre, email, password, telefono, IDcel) VALUES ('$nombre','$email','$password','$telefono','$IDcel')");
            	$q_const = mysqli_query($connect,"SELECT * FROM usuarios WHERE email = '$email' AND telefono = '$telefono'");
            	while($row = mysqli_fetch_assoc($q_const)){
	                $arr = array(
	                    "status" => 'OK',
	                    "id" => $row['id'],
                        "envio" => $envio['costo_envio'],
	                    "nombre" => $row['nombre'],
	                    "email" => $row['email'],
	                    "telefono" => $row['telefono']
	                );
	                echo json_encode($arr);
                }
            }   
        	break;

        case 'borrar':
        	$opcion = $_GET['que'];

        	switch ($opcion) {
        		case 'direccion':
        			if(!isset($_GET['user']) || !isset($_GET['id_direccion'])){
        				$arr = array(
        					"status" => "ERROR",
        					"titulo" => "Ups...",
        					"msg" => "400 BAD REQUEST, intente de nuevo plocs"
        				);
        				echo json_encode($arr);
        				exit();
        			}else{
        				$usuario = $_GET['user'];
        				$id_direccion = $_GET['id_direccion'];

        				$query = mysqli_query($connect,"SELECT * FROM direcciones WHERE id = '$id_direccion' AND user_id = '$usuario'");
        					if(mysqli_num_rows($query) == 0){
	        					$arr = array(
	        						"status" => "ERROR",
	        						"titulo" => "Ups...",
	        						"msg" => "Esa direccion no existe en la Base de Datos :o"
	        					);
	        					echo json_encode($arr);
	        					exit();
        					}else{

        						$delete = mysqli_query($connect, "DELETE FROM direcciones WHERE id = '$id_direccion'");
        							if($delete){
	        							$arr = array(
	        								"status" => "OK",
	        								"titulo" => "Yeeii!",
	        								"msg" => "Direccion eliminada con exito :3"
	        							);
	        							echo json_encode($arr);
        							}else{
	        							$arr = array(
	        								"status" => "ERROR",
	        								"titulo" => "Ups..",
	        								"msg" => "Ha ocurrido el siguiente error:".$connect->error
	        							);
	        							echo json_encode($arr);
        							}

        					}
        			}

        			break;
        		
        		default:
        			# code...
        			break;
        	}

        	break;

        case 'insertar':
        	$opcion = $_GET['que'];

        	switch ($opcion) {
        		case 'direccion':
        			if(!isset($_GET['token'])){
                        http_response_code(400);
                        exit();
                    }else{
						$usuario = $obj['usuario'];
						$latitud = $obj['latitud'];
						$longitud = $obj['longitud'];
						$titulo = utf8_decode($obj['titulo']);
						$referencias = $obj['referencias'];

						$insert = mysqli_query($connect,"INSERT INTO direcciones (user_id, latitud, longitud, titulo, referencias) VALUES ('$usuario', '$latitud', '$longitud', '$titulo', '$referencias')");

						if($insert){
                			$arr = array(
                			    "status" => 'OK',
                			    "titulo" => 'Eureca!',
                			    "id" => $connect->insert_id,
                			    "msg" => 'Se ha insertado la direccion correctamente'
                			);
                			echo json_encode($arr);
						}else{
                			$arr = array(
                			    "status" => 'ERROR',
                			    "titulo" => 'Ups...',
                			    "msg" => 'Ha ocurrido un error :/'
                			);
                			echo json_encode($arr);
						}

                    }
        			break;
                case 'pedido':
                    //Se genera ID de pedido
                    $id_pedido = md5(time());
                    //Se reciben parametros para insertar
                    $user_id = $obj['dataUser']['id'];
                    $direccion_id = $obj['direccion']['id_dir'];
                    $id_negocio = $obj['compra'][0]['negocio'];
                    $total = $obj['total'];
                    $sinenvio = $obj['sinenvio'];
                    $createAt = $obj['createAt'];

                    //Seleccionamos el token del negocio
                    $q_token = mysqli_query($connect,"SELECT IDcel FROM establecimientos WHERE id = '$id_negocio'");
                    $token = mysqli_fetch_assoc($q_token);

                    //Se iteran los productos comprados 
                    foreach ($obj['compra'] as $key) {
                        $id_prod = $key['id'];
                        $nombre_prod = $key['nombre'];
                        $cantidad = $key['qty'];
                        $subtotal = $key['subtotal'];
                        $negocio = $key['negocio'];
                        $clu = $subtotal/$cantidad;

                        $inserta = mysqli_query($connect,"INSERT INTO pedidos (id_pedido, nombre, id_producto, clu, cantidad, subtotal, negocio, id_usuario, id_direccion) VALUES ('$id_pedido', '$nombre_prod', '$id_prod', $clu, '$cantidad', '$subtotal', '$negocio', '$user_id', '$direccion_id')");
                    }

                    //Se ingresa a status_pedidos
                    $statusped = mysqli_query($connect,"INSERT INTO status_pedidos (id_pedido, user_id, direccion_id, negocio, sinenvio, total, status, createAt) VALUES ('$id_pedido', '$user_id', '$direccion_id', '$id_negocio', '$sinenvio', '$total', 'Enviado', '$createAt')");
                    
                    if($statusped){
                        $send = sendPushNotificationNegocio($token['IDcel']);
                        echo json_encode(array("status" => 'EXITO', "idpedido" => $id_pedido));
                    }else{
                        echo json_encode(array("status" => 'ERROR'));
                        http_response_code(400);
                        exit();
                    }

                    break;
        		
        		default:
                    http_response_code(400);
                    exit();
        			break;
        	}

        break;	

        case 'actualizar':
            $opcion = $_GET['que'];
            switch ($opcion) {
                case 'tokenid':
                   $id_user = $obj['id_user'];
                   $oldtoken = $obj['oldtoken'];

                   $comp_tk = mysqli_query($connect,"SELECT nombre FROM usuarios WHERE id = '$id_user' AND IDcel = '$oldtoken'");
                   $q_envio = mysqli_query($connect,"SELECT costo_envio FROM configuracion WHERE id = 1");
                   $envio = mysqli_fetch_assoc($q_envio);

                   if(mysqli_num_rows($comp_tk) == 0){
                    //Actualizar y enviar msg
                    $update_tk = mysqli_query($connect,"UPDATE usuarios SET IDcel = '$oldtoken' WHERE id = '$id_user'");
                    if($update_tk){
                        $arr = array(
                            "status" => 'EXITO',
                            "envio" => $envio['costo_envio'],
                            "msg" => 'El Token se ha cambiado :3'
                        );

                        echo json_encode($arr);
                    }else{
                        $arr = array(
                            "status" => 'ERROR',
                            "envio" => $envio['costo_envio'],
                            "msg" => 'Error error :c'
                        );

                        echo json_encode($arr);
                    }

                   }else{
                    //Enviar mensaje satisfactorio
                        $arr = array(
                            "status" => 'EXITO',
                            "envio" => $envio['costo_envio'],
                            "msg" => 'El Token es el mismo :3'
                        );

                        echo json_encode($arr);
                   }

                    break;
                
                default:
                    # code...
                    break;
            }

        break;

        default:
            http_response_code(400);
            exit();
            break;
    }

}