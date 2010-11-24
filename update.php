<?
// ini_set('DISPLAY_ERRORS',1);

$users = array(
	'some'
	, 'notifo'
	, 'user'
	, 'names'
);
$notifoService = 'your service name';
$notifoSecret = 'your secret API key';

/* include the Notifo_API PHP library */
include("Notifo_API.php");

if($_POST['update'] != '') {
	
	$update = '"' . date('D M j G:i:s T Y') . '":';
	$update .= '{"message": "' . $_POST['update'] . '",';
	$update .= '"tech": "' . $_POST['tech'] .'",';
	$update .= '"status": "' . $_POST['status'] . '"}';
	
	if($_POST['private'] == 'false'){
		
		$history = $update . ',';
	
		$new = array(0=> $history);
		
		$file = file('updates.json');
		
		array_splice($file,1,0,$new);
		
		$updates = fopen('updates.json', 'w+');
		
		foreach($file as $key => $value){
			fwrite($updates,$value);
		}
		
		$latest = fopen('latest.json', 'w');
		fwrite($latest,'{'.$update.'}');
		
	}
	
	//*********//
	// NOTIFO! //
	//*********//
	
	echo '{';
	foreach( $users as $i => $user ){
		/* create a new "notifo" object */
		$notifo = new Notifo_API($notifoService, $notifoSecret);

		/* set the notification parameters */
		$params = array("to"=>$user, /* "to" only used with Service accounts */
				"title"=>'IT Update -' . $_POST['tech'] . ' #' . $_POST['status'],
				"msg"=>$_POST['update'],
				"uri"=>'http://status.integer.com');

		/* send the notification! */
		$response = $notifo->send_notification($params);

		echo $response;

		if (count($users) == ($i+1))
			return;
		else
			echo ',';
	}
	echo '}';
	
}
if($_POST['expire'] == true) {
	$latest = fopen('latest.json', 'w');
	fwrite($latest,'{}');
	
	echo '{';
	foreach( $users as $i => $user ){
		/* create a new "notifo" object */
		$notifo = new Notifo_API($notifoService, $notifoSecret);

		/* set the notification parameters */
		$params = array("to"=>$user, /* "to" only used with Service accounts */
				"title"=> 'IT Update Expired',
				"msg"=> $_POST['tech'].' has expired the latest message.',
				"uri"=>'http://status.integer.com');

		/* send the notification! */
		$response = $notifo->send_notification($params);

		echo $response;

		if (count($users) == ($i+1))
			return;
		else
			echo ',';
	}
	echo '}';
}
?>