<?php 
/**
 * votar.php
 */
require_once ("facebook.php");
//Iniciamos la API
$facebook=new Facebook(array('appId' => 'APP_ID_DE_NUESTRA_APLICACION','secret'=>'SECRET_DE_NUESTRA_APLICACION'));

//Capturamos el id de lo que estamos votando
$idvotado=$_GET['idvotado'];

$uid = $facebook->getUser();
if($uid==0)
{
	//Ha ocurrido un error en el proceso
	echo('No estás identificado en Facebook');
}
else
{
	try {
	$user_profile = $facebook->api('/me','GET');
	$idusuario=$user_profile['id'];
		if(emitirvoto($idvideo,$idusuario,date('Y-m-d')))
		{
			//Posteamos en el muro del usuario un mensaje promocionando el concurso
			//CUIDADO: según FB hay que darle la opción al usuario de omitir este paso
			$post_url = '/'.$user_profile['id'].'/feed';
			$msg_body = array(
				'link' => 'http://www.nuestra_web.com',
				'message' => utf8_encode('Acabo de votar en el concurso ¡Vota tú también!')
				);
			$postResult = $facebook->api($post_url, 'post', $msg_body );
			
			//Mostramos el resultado
			echo("Gracias por tu voto");
		}
		else
		{
			echo('Ha ocurrido un error al votar. Posiblemente ya hayas votado antes con este ususario de Facebook.');
		}
	}
	catch (FacebookApiException $e) {
		echo $e->getMessage();
	  }
}

function emitirvoto($idvideo,$idusuario,$fecha)
{
	//Función que controla y graba los votos emitidos
	//Si el proceso va bien devuelve true
	//Si algo va mal o el usuario ya ha votado, devuelve false
}
?>