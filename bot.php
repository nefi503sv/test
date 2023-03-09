<?php

// Bot mod por @gatesccn código original para o @gatesccn

date_default_timezone_set ('America/La_Paz'); // define timestamp padrão

// Incluindo arquivos nescessários
include __DIR__.'/Telegram.php';

if (!file_exists('dadosBot.ini')){

	echo "Cree el bot primero!";
	exit;

}

$textoMsg=json_decode (file_get_contents('textos.json'));
$iniParse=parse_ini_file('dadosBot.ini');

$ip=$iniParse ['ip'];
$token=$iniParse ['token'];
$limite=$iniParse ['limite'];

define ('TOKEN', $token); // token del bot creado en @botfather

// Instancia das classes
$tlg=new Telegram (TOKEN);
$redis=new Redis ();
$redis->connect ('localhost', 6379); //redis usando puerto estándar

// BLOQUE UTILIZADO EN SONDEO LARGO

while (true){

$updates=$tlg->getUpdates();

for ($i=0; $i < $tlg->UpdateCount(); $i++){

$tlg->serveUpdate($i);

switch ($tlg->Text ()){

	case '/start':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => $textoMsg->start,
		'parse_mode' => 'html',
		'reply_markup' => $tlg->buildInlineKeyBoard ([
			[$tlg->buildInlineKeyboardButton ('🇺🇸 SSH GRATIS 🇺🇸', null, '/onichan')]
		])
	]);

	break;
	case '/sobre':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'Este Bot Esta Echo para Brindarte Ayuda Con el Netfree Recuerda que todo Esfuerzo vale Si quieres hacer una donacion guiño guiño Contactame +59162069439'
	]);

	break;
	case '/total':

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => 'fueron creados <b>'.$redis->dbSize ().'</b> cuentas en las últimas 24h',
		'parse_mode' => 'html'
	]);

	break;
	case '/onichan':

	$tlg->answerCallbackQuery ([
	'callback_query_id' => $tlg->Callback_ID()
	]);

	if ($redis->dbSize () == $limite){

		$textoSSH=$textoMsg->sshgratis->limite;

	} elseif ($redis->exists ($tlg->UserID ())){

		$textoSSH=$textoMsg->sshgratis->nao_criado;

	} else {

		$usuario=substr (str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
		$senha=mt_rand(11111, 999999);

		exec ('./gerarusuario.sh '.$usuario.' '.$senha.' 1 1');

		$textoSSH="🇧🇺🇸 Cuenta SSH creada ;)\r\n\r\n<b>Servidor:</b> <code>".$ip."</code>\r\n<b>Usuario:</b> <code>".$usuario."</code>\r\n<b>Contraseña:</b> <code>".$senha."</code>\r\n<b>Cantidad:</b> 1\r\n<b>Validade:</b> ".date ('d/m', strtotime('+1 day'))."\r\n\r\n🤙 Cuenta ssh gratis Cortesía de El Mandarin Sniff";

		$redis->setex ($tlg->UserID (), 21600, 'true'); //define registro para ser guardado por 6horas

	}

	$tlg->sendMessage ([
		'chat_id' => $tlg->ChatID (),
		'text' => $textoSSH,
		'parse_mode' => 'html'
	]);

	break;

}

}}
