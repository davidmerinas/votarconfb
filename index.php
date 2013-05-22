<?php
/**
 *index.php
 **/

require_once ("facebook.php");
//Creamos el objeto pasando los valores de appId y secret
$facebook = new Facebook ( array ('appId' => 'APP_ID_DE_NUESTRA_APLICACION', 'secret' => 'SECRET_DE_NUESTRA_APLICACION' ) );

$uid = $facebook->getUser ();
if ($uid == 0) {
	//No hay usuario identificado, mostramos el login
	$params = array (//'scope' => 'read_stream, friends_likes, user_likes, publish_actions',
	'scope' => 'read_stream, friends_likes, user_likes', 'redirect_uri' => 'URL_A_LA_QUE_VOLVER_DESPUES_DEL_LOGIN' );
	$loginUrl = $facebook->getLoginUrl ( $params );
	
	echo ("<a href='" . $loginUrl . "'>Conectar con Facebook</a>");
} else {
	//El usuario ya ha conectado, mostramos el contenido
	//Botón de logout para permitirle al usuario desconectar
	$params = array ('next' => 'URL_A_LA_QUE_VOLVER_DESPUES_DEL_LOGOUT' );
	$logoutUrl = $facebook->getLogoutUrl ( $params );
	echo ("<a href='" . $logoutUrl . "'>Cerrar sesión</a>");
	try {
		
		$user_profile = $facebook->api ( '/me', 'GET' );
		$idusuario = $user_profile ['id'];
		
		//Vemos si ya le ha dado a Me Gusta en nuestra página 
		

		$checkMeGusta = $facebook->api ( array ("method" => "fql.query", "query" => "select uid from page_fan where uid=me() and page_id=ID_PAGINA_FACEBOOK" ) );
		
		if (sizeof ( $checkMeGusta ) == 1) {
			//Le ha dado a ME GUSTA, podemos mostrar el botón de voto
			//Veamos si ya ha votado esta opcion
			//La función checkvoto controla si ese usuario ya ha votado esa opción.
			

			if (checkvoto ( $idvotado, $idusuario )) {
				//No ha votado, mostramos el boton Votar
				echo ("<a href='contabilizarvoto.php?idvotado=$idvotado'>Votar</a>");
			} else {
				//Ya ha votado, mostramos el YA HAS VOTADO
				echo ("<a href='#'>Ya has votado</a>");
			}
		} else {
			//Si no le ha dado a ME GUSTA a nuestra Página
			echo ("<a href='URL_DE_NUESTRA_PAGINA_FB'>Hacer clic en ME GUSTA</a>");
		}
	} catch ( FacebookApiException $e ) {
		// Algo ha salido mal, mostramos el Login nuevamente seguido del error
		

		$loginUrl = $facebook->getLoginUrl ( $params );
		
		echo ("<a href='" . $loginUrl . "'>Conectar con Facebook</a>");
		error_log ( $e->getType () );
		error_log ( $e->getMessage () );
	}
}

function checkvoto($idvotado, $idusuario) {
/**
 *Devuelve true si el usuario puede votar (no ha votado previamente) y false en caso contrario
 *El contenido de esta función dependerá del tipo de concurso, criterios de votos, tipo de concurso...
 **/
}
?>