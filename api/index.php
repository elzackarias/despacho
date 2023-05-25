<?php
ini_set('session.gc_maxlifetime', 7200);
session_set_cookie_params(7200);
session_start();
date_default_timezone_set('America/Mexico_City');
require_once('../lib/config.php');
require_once('../lib/funcs.php');
/*
    Author: @elzackarias
    Powered by Vezack S.A.S
*/
if (!isset($_GET['type'])) {
    http_response_code(400);
} else {
    $json =  file_get_contents('php://input');
    $obj = json_decode($json, true);
    //Tipo de solicitud (Login, Register, Delete, Consulta, Update)
    $tipo = $_GET['type'];

    switch ($tipo) {
        case 'q':
            $consulta = $_GET['que'];
            switch ($consulta) {
                case 'appointments_available':
                    $dia = $_GET['dia'];
                    $query = mysqli_query($connect, "SELECT id,date_start,mode FROM appointments WHERE DATE(date_start) = DATE('$dia')");
                    //"local" => date('H')
                    $arr = ["local" => date('H'), "date" => date('Y-m-d'), 'data' => []];
                    while ($data = mysqli_fetch_assoc($query)) {
                        array_push($arr['data'], array("id" => $data['id'], "start" => $data['date_start'], "mode" => $data['mode'], "hour" => getHour($data['date_start'])));
                    }
                    echo json_encode($arr);
                    break;


                case 'user_verify':
                    $user = $obj['user'];
                    $getUser = mysqli_query($connect, "SELECT id FROM users WHERE user = '$user'");
                    if (mysqli_num_rows($getUser) != 0) {
                        $arr = array(
                            "status" => 'ERROR',
                            "msg" => 'El usuario ya existe, eliga otro'
                        );
                    } else {
                        $arr = array(
                            "status" => 'EXITO',
                            "msg" => 'Usuario disponible!'
                        );
                    }
                    echo json_encode($arr);
                    break;

                case 'email_verify':
                    $email = $obj['email'];
                    $getEmail = mysqli_query($connect, "SELECT id FROM users WHERE email = '$email'");
                    if (mysqli_num_rows($getEmail) != 0) {
                        $arr = array(
                            "status" => 'ERROR',
                            "msg" => 'Email ya registrado'
                        );
                    } else {
                        $arr = array(
                            "status" => 'EXITO',
                            "msg" => ''
                        );
                    }
                    echo json_encode($arr);
                    break;
                default:
                    # code...
                    break;
            }

            break;

        case 'login':
            if (!isset($obj['email']) || !isset($obj['password']) || empty($obj['email']) || empty($obj['password'])) {
                $arr = array(
                    "status" => 'ERROR',
                    "msg" => 'Rellena y revisa todos los campos'
                );
            } else {
                //A pesar de que recibimos email, no comprobamos que tenga el formato del mismo, puesto que podemos recibir un usuario sin ese formato
                $email = mysqli_real_escape_string($connect, trim(strip_tags(strtolower($obj['email']))));
                $password = $obj['password'];
                $query = mysqli_query($connect, "SELECT id, password, email, user, token FROM users WHERE email = '$email' OR user = '$email'");
                if ($query) {
                    if (mysqli_num_rows($query) == 1) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            if (($email == $row['email'] || $email == $row['user']) && password_verify($password, $row['password'])) {
                                $_SESSION['uid'] = $row['id'];
                                $_SESSION['tk'] = $row['token'];
                                $arr = array(
                                    "status" => 'EXITO',
                                    "msg" => 'Login existoso'
                                );
                            } else {
                                $arr = array(
                                    "status" => 'ERROR',
                                    "msg" => 'La contraseña que ingresaste es incorrecta :/'
                                );
                            }
                        }
                    } else {
                        $arr = array(
                            "status" => 'ERROR',
                            "msg" => 'El email o usuario no existe :o'
                        );
                    }
                } else {
                    $arr = array(
                        "status" => "ERROR",
                        "msg" => $connect->error
                    );
                }
            }
            echo json_encode($arr);
            break;

        case 'register':
            if (!isset($obj['firstname']) || !isset($obj['lastname']) || !isset($obj['email']) || !isset($obj['user']) || !isset($obj['phone']) || !isset($obj['cell']) || !isset($obj['password']) || empty($obj['firstname']) || empty($obj['lastname']) || empty($obj['email']) || empty($obj['user']) || empty($obj['phone']) || empty($obj['cell']) || empty($obj['password']) || !filter_var($obj['email'], FILTER_VALIDATE_EMAIL) || strlen($obj['phone']) != 10 || strlen($obj['cell']) != 10) {
                $arr = array(
                    "status" => 'ERROR',
                    "msg" => 'Rellena y revisa todos los campos'
                );
            } else {
                $firstname = strip_tags($obj['firstname']);
                $lastname = strip_tags($obj['lastname']);
                $email = mysqli_real_escape_string($connect, trim(strip_tags(strtolower($obj['email']))));
                $user = mysqli_real_escape_string($connect, trim(strip_tags(strtolower($obj['user']))));
                $phone = mysqli_real_escape_string($connect, trim(strip_tags(strtolower($obj['phone']))));
                $cell = mysqli_real_escape_string($connect, trim(strip_tags(strtolower($obj['cell']))));
                $password = password_hash($obj['password'], PASSWORD_ARGON2I);
                $comp = mysqli_query($connect, "SELECT id FROM users WHERE email = '$email' OR user = '$user'");
                if (mysqli_num_rows($comp) != 0) {
                    $arr = array(
                        "status" => "ERROR",
                        "msg" => "El usuario o el email ya estan registrados"
                    );
                } else {
                    $tk = hash("sha256", time());
                    //Se procede a registrarse en la base de datos
                    $ins = mysqli_query($connect, "INSERT INTO users (firstname,lastname,email,cellphone,telephone,user,password,token) VALUES ('$firstname','$lastname','$email','$cell','$phone','$user','$password','$tk')");
                    if ($ins) {
                        //Se rescata la ID guardada buscado su email
                        $q_id = mysqli_query($connect, "SELECT id FROM users WHERE email = '$email'");
                        $datos = mysqli_fetch_assoc($q_id);
                        $_SESSION['uid'] = $datos['id'];
                        $_SESSION['tk'] = $tk;
                        $arr = array(
                            "status" => "EXITO",
                            "msg" => "Usuario registrado con exito",
                        );
                    } else {
                        $arr = array(
                            "status" => "ERROR",
                            "msg" => $connect->error
                        );
                    }
                }
            }
            echo json_encode($arr);
            break;
        case 'borrar':
            $opcion = $_GET['que'];

            switch ($opcion) {
                case 'appointment':
                    if (!isset($obj['uid']) || !isset($obj['tk']) || !isset($obj['pid']) || empty($obj['uid']) || empty($obj['tk']) || empty($obj['pid'])) {
                        $arr = array(
                            "status" => 'ERROR',
                            "msg" => 'Auth Error'
                        );
                    } else {
                        $uid = base64_decode($obj['uid']);
                        $tk = base64_decode($obj['tk']);
                        $pid = base64_decode($obj['pid']);
                        $q = mysqli_query($connect, "SELECT id,token FROM users WHERE id = '$uid' AND token = '$tk'");
                        if (mysqli_num_rows($q) == 1) {
                            $comp = mysqli_query($connect, "SELECT id FROM appointments WHERE id = '$pid' AND user_id = '$uid'");
                            if (mysqli_num_rows($comp) == 1) {
                                $ins = mysqli_query($connect, "DELETE FROM appointments WHERE id = '$pid'");
                                if ($ins) {
                                    $arr = array(
                                        "status" => 'EXITO',
                                        "msg" => 'Cancelada con exito!'
                                    );
                                } else {
                                    $arr = array(
                                        "status" => 'ERROR',
                                        "msg" => $connect->error
                                    );
                                }
                            }else{
                                $arr = array(
                                    "status" => 'ERROR',
                                    "msg" => 'Auth Error --'
                                );
                            }
                        } else {
                            $arr = array(
                                "status" => 'ERROR',
                                "msg" => 'Auth Error'
                            );
                        }
                    }
                    echo json_encode($arr);
                    break;
        		/*case 'direccion':
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
        		*/
        		default:
        			# code...
        			break;
        	}

            break;

        case 'insertar':
            $opcion = $_GET['que'];
            switch ($opcion) {
                case 'direccion':
                    if (!isset($obj['uid']) || !isset($obj['tk']) || !isset($obj['street']) || !isset($obj['city']) || !isset($obj['colony']) || !isset($obj['zipcode']) || empty($obj['uid']) || empty($obj['tk']) || empty($obj['street']) || empty($obj['city']) || empty($obj['colony']) || empty($obj['zipcode'])) {
                        $arr = array(
                            "status" => 'ERROR',
                            "msg" => 'Rellena y revisa todos los campos'
                        );
                    } else {
                        $uid = base64_decode($obj['uid']);
                        $tk = $obj['tk'];
                        $street = $obj['street'];
                        $city = $obj['city'];
                        $colony = $obj['colony'];
                        $zipcode = $obj['zipcode'];

                        //Comprobamos si el UID y el TK coinciden con el mismo usuario
                        $comp = mysqli_query($connect, "SELECT token FROM users WHERE id = '$uid'");
                        if (mysqli_num_rows($comp) == 0) {
                            $arr = array(
                                "status" => 'ERROR',
                                "msg" => 'ERROR TOKEN'
                            );
                        } else {
                            $dato = mysqli_fetch_assoc($comp);
                            $q_tk = $dato['token'];
                            $comp_token = mysqli_query($connect, "SELECT firstname FROM users WHERE id = '$uid' AND token = '$q_tk'");
                            if (mysqli_num_rows($comp_token) == 0) {
                                $arr = array(
                                    "status" => 'ERROR',
                                    "msg" => 'ERROR TOKEN'
                                );
                            } else {
                                $insert = mysqli_query($connect, "INSERT INTO addresses (user_id,street,colony,city,zipcode) VALUES ('$uid','$street','$city','$colony','$zipcode')");
                                if ($insert) {
                                    $arr = array(
                                        "status" => 'EXITO',
                                        "msg" => 'Se ha insertado la direccion correctamente'
                                    );
                                } else {
                                    $arr = array(
                                        "status" => 'ERROR',
                                        "msg" => 'Ha ocurrido un error :/'
                                    );
                                }
                            }
                        }
                    }
                    echo json_encode($arr);
                    break;
                case 'appointment':
                    if (!isset($obj['uid']) || !isset($obj['tk']) || !isset($obj['modality']) || !isset($obj['date']) || !isset($obj['subject']) || empty($obj['uid']) || empty($obj['tk']) || empty($obj['modality']) || empty($obj['subject']) || empty($obj['date'])) {
                        $arr = array(
                            "status" => 'ERROR',
                            "msg" => 'Rellena y revisa todos los campos'
                        );
                    } else {
                        $uid = base64_decode($obj['uid']);
                        $tk = base64_decode($obj['tk']);
                        $subject = htmlspecialchars(strip_tags($obj['subject']), ENT_QUOTES);
                        $date = $obj['date'];
                        $modality = $obj['modality'];
                        $q = mysqli_query($connect, "SELECT id,token FROM users WHERE id = '$uid' AND token = '$tk'");
                        if (mysqli_num_rows($q) == 1) {
                            $comp = mysqli_query($connect, "SELECT id FROM appointments WHERE date_start = '$date'");
                            if (mysqli_num_rows($comp) == 0) {
                                $ins = mysqli_query($connect, "INSERT INTO appointments (user_id,date_start,subject,mode) VALUES ('$uid','$date','$subject','$modality')");
                                if ($ins) {
                                    $arr = array(
                                        "status" => 'EXITO',
                                        "msg" => 'Cita creada con exito!'
                                    );
                                } else {
                                    $arr = array(
                                        "status" => 'ERROR',
                                        "msg" => $connect->error
                                    );
                                }
                            }else{
                                $arr = array(
                                    "status" => 'ERROR',
                                    "msg" => 'Fecha ya tomada'
                                );
                            }
                        } else {
                            $arr = array(
                                "status" => 'ERROR',
                                "msg" => 'Auth Error'
                            );
                        }
                    }
                    echo json_encode($arr);
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

                    $comp_tk = mysqli_query($connect, "SELECT nombre FROM usuarios WHERE id = '$id_user' AND IDcel = '$oldtoken'");
                    $q_envio = mysqli_query($connect, "SELECT costo_envio FROM configuracion WHERE id = 1");
                    $envio = mysqli_fetch_assoc($q_envio);

                    if (mysqli_num_rows($comp_tk) == 0) {
                        //Actualizar y enviar msg
                        $update_tk = mysqli_query($connect, "UPDATE usuarios SET IDcel = '$oldtoken' WHERE id = '$id_user'");
                        if ($update_tk) {
                            $arr = array(
                                "status" => 'EXITO',
                                "envio" => $envio['costo_envio'],
                                "msg" => 'El Token se ha cambiado :3'
                            );

                            echo json_encode($arr);
                        } else {
                            $arr = array(
                                "status" => 'ERROR',
                                "envio" => $envio['costo_envio'],
                                "msg" => 'Error error :c'
                            );

                            echo json_encode($arr);
                        }
                    } else {
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
    function clean_str($data)
    {
        //$data = mysqli_real_escape_string($con, $data);
        return trim(strip_tags(strtolower($data)));
    }
}
