<?php
$VMDIR="/var/spool/asterisk/voicemail/default";	// Directorio donde se encuentran los mensajes de voz

$URLXML="http://www.example.com/cisco;


$USER=trim($_GET[user]);				// Obtenemos el usuario que queremos consultar
$msg=trim($_GET[msg]);
$call=trim($_GET[call]);




//$USER="0626";		// ESTO HABRA QUE QUITARLO CUANDO 

// Obtenemos los datos y los almacenamos en la estructura
$VMDIR.="/$USER/INBOX";				// Le añadimos el usuario del voicemail
$localdir=opendir($VMDIR);
while ($cnt=readdir($localdir)){
    if ($cnt[0] != "."){
        if (strpos($cnt,".txt") !== false){
	    $listaArchivos[]=$cnt;
	}
    }
}
sort($listaArchivos);
for ($t=0; $t<count($listaArchivos); $t++){
    $tmpfile=file($VMDIR."/".$listaArchivos[$t]);
	list($mensaje[$t][archivo])=explode(".",$listaArchivos[$t]);
    for ($f=0; $f<count($tmpfile); $f++){		// Parseamos todas las filas buscando los parámetros
	list($campo,$valor)=explode("=",$tmpfile[$f]);
	$campo=trim($campo); $valor=trim($valor);
	if (($campo != "")&&($valor != "")){
	    $mensaje[$t][$campo]=$valor;
	}
    }
}
header ("content-type: text/xml");
if (($USER != "")&&($msg == "") && ($call == "")){				// Mostramos un menú básico con los mensajes que tiene por leer
    echo "<CiscoIPPhoneMenu>\n";
    echo "<Title>Voicemail menu</Title>\n";
    echo "<Prompt>Select a message</Prompt>\n";
    for ($t=0; $t<count($mensaje); $t++){
	list($n,$orig)=explode("<",$mensaje[$t][callerid]);
	list($orig)=explode(">",$orig);
	$fecha=date("m/d g:ia",$mensaje[$t][origtime]);
	$msg=trim($msg);
	if (($USER != "") && ($msg == "")){
	    echo "<MenuItem>\n";
	    echo "	<Name>$fecha $n ($orig)</Name>\n";               
	    echo "	<URL>$URLXML/voicemail.php?user=$USER&amp;msg=$t</URL>\n";
	    echo "</MenuItem>\n";
	}
    }
    echo "<SoftKeyItem>\n";
    echo "	<Name>Exit</Name>\n";
    echo "	<URL>Init:Services</URL>\n";       //Force application to close
    echo "	<Position>1</Position>\n";
	echo "</SoftKeyItem>\n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Select</Name>\n";
    echo "	<URL>SoftKey:Select</URL>\n";
    echo "	<Position>2</Position>\n";
	echo "</SoftKeyItem>\n";
	echo "</CiscoIPPhoneMenu>\n";

}elseif (($USER != "")&&($msg != "")&&($call == "")){				// Hemos seleccionado un mensaje, le damos los datos


    echo "<CiscoIPPhoneText>\n";
    echo "<Title>Menu Voicemail</Title>\n";
    echo "<Prompt> </Prompt>\n";
    list($n,$orig)=explode("<",$mensaje[$msg][callerid]);
    list($orig)=explode(">",$orig);
    $fecha=date("m/d/Y g:ia",$mensaje[$msg][origtime]);
    $msg=trim($msg);
	
    echo "<Text>Date: $fecha\n Caller: $n ($orig)\n Length: ".$mensaje[$msg][duration]." Seconds</Text> \n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Exit</Name>\n";
    echo "	<URL>$URLXML/voicemail.php?user=$USER</URL>\n";
    echo "	<Position>1</Position>\n";
	echo "</SoftKeyItem>\n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Play</Name>\n";
    echo "	<URL>$URLXML/voicemail.php?user=$USER&amp;msg=$msg&amp;call=1</URL>\n";
    echo "	<Position>2</Position>\n";
	echo "</SoftKeyItem>\n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Delete</Name>\n";
    echo "	<URL>$URLXML/voicemail.php?user=$USER&amp;msg=$msg&amp;call=3</URL>\n";
    echo "	<Position>3</Position>\n";
	echo "</SoftKeyItem>\n";
	
    echo "</CiscoIPPhoneText>\n";

}elseif (($USER != "")&&($msg != "")&&($call == 1) ){ // Play Message
	$random=rand(100,999);
	$f1=fopen("/tmp/getVoiceMailMsg$random.call","w+");
	//fputs($f1,"Channel: SIP/$USER\n");  If you Use FeePBX and have "Paging and Intercom" Enabled you can uncomment the following line and comment out this line to have the phone auto pickup and start playing your message
	fputs($f1,"Channel: Local/*80$USER@ext-intercom\n"); 
	fputs($f1,"Application: Playback\n");
	fputs($f1,"Data: $VMDIR/".$mensaje[$msg][archivo]."");
	fclose($f1);
	rename("/tmp/getVoiceMailMsg$random.call","/var/spool/asterisk/outgoing/getVoiceMailMsg$random.call");

    echo "<CiscoIPPhoneText>\n";
    echo "<Title>Menu Voicemail</Title>\n";
    echo "<Prompt> </Prompt>\n";
    list($n,$orig)=explode("<",$mensaje[$msg][callerid]);
    list($orig)=explode(">",$orig);
    $fecha=date("m/d/Y g:ia",$mensaje[$msg][origtime]);
    $msg=trim($msg);
	
    echo "<Text>Date: $fecha\n Caller: $n ($orig)\n Length: ".$mensaje[$msg][duration]." Seconds</Text> \n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Exit</Name>\n";
    echo "	<URL>$URLXML/voicemail.php?user=$USER</URL>\n";
    echo "	<Position>1</Position>\n";
	echo "</SoftKeyItem>\n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Play</Name>\n";
    echo "	<URL>$URLXML/voicemail.php?user=$USER&amp;msg=$msg&amp;call=1</URL>\n";
    echo "	<Position>2</Position>\n";
	echo "</SoftKeyItem>\n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Delete</Name>\n";
    echo "	<URL>$URLXML/voicemail.php?user=$USER&amp;msg=$msg&amp;call=3</URL>\n";
    echo "	<Position>3</Position>\n";
	echo "</SoftKeyItem>\n";
	
    echo "</CiscoIPPhoneText>\n";

}elseif (($USER != "")&&($msg != "")&&($call == 3) ){ // Delete Message 
unlink("$VMDIR/".$mensaje[$msg][archivo].".wav");
unlink("$VMDIR/".$mensaje[$msg][archivo].".WAV");
unlink("$VMDIR/".$mensaje[$msg][archivo].".txt");
unlink("$VMDIR/".$mensaje[$msg][archivo].".gsm");


           echo "<CiscoIPPhoneText>\n";
    echo "<Title>Menu Voicemail</Title>\n";
    echo "<Prompt> </Prompt>\n";
	echo "<Text>Message Deleted</Text> \n";
	echo "<SoftKeyItem>\n";
    echo "	<Name>Exit</Name>\n";
    echo "	<URL>$URLXML/voicemail.php?user=$USER</URL>\n";
    echo "	<Position>1</Position>\n";
	echo "</SoftKeyItem>\n";
echo "</CiscoIPPhoneText>\n";	
}

?>
