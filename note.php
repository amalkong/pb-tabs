<?php
date_default_timezone_set('America/Jamaica');
define('IN_WINDOWS',TRUE);
define('PBD',TRUE);
define('PATH', str_replace('\\','/',__dir__).'/');

$note_name = 'note.txt';
$uniqueNotePerIP = false;

if($uniqueNotePerIP){
	// Use the user's IP as the name of the note.
	// This is useful when you have many people
	// using the app simultaneously.
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$note_name = PATH. md5($_SERVER['HTTP_X_FORWARDED_FOR']).'.txt';
	} else{
		$note_name = PATH. md5($_SERVER['REMOTE_ADDR']).'.txt';
	}
}

if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
	// This is an AJAX request
	if(isset($_POST['note'])){
		// Write the file to disk
		file_put_contents($note_name, $_POST['note']);
		echo '{"saved":1}';
	}
	exit;
}
$note_content = '

                Write your note here.

             It will be saved with AJAX.';

if( file_exists($note_name) ){
	$note_content = htmlspecialchars( file_get_contents($note_name) );
}
?>