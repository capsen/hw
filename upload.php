<?php
include "userlist.php";

if(!$userlist[strtoupper($_GET['token'])] && !$_GET['week']){
    echo "no token provided";
    die('no token provided');
}

header('Content-Type: application/json');
$uploaded = array();

if(!empty($_FILES['file']['name'][0])){
    $folderpath='uploads/'.$userlist[$_GET['token']].'/week'.$_GET['week'].'/';
    mkdir($folderpath, 0755, true);
	foreach($_FILES['file']['name'] as $position => $name){
            if(move_uploaded_file($_FILES['file']['tmp_name'][$position], $folderpath.$name)){
			$uploaded[] = array(
				'name' =>$name,
                'file' => $folderpath . $name
				);
		}
	}
}

echo json_encode($uploaded);

?>