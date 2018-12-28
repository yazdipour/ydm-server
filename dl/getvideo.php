<?php
// i=video id
// u=id *day>rev>64>64
require_once '../auth/SqlHandler.php';
$db= new DB;
/////////////////// FUNCTIONS //////////////////////////
function checkIdExp($id,$db){
    $res=$db->exec_query("SELECT nrCanDownload FROM usrs WHERE id=$id");
    if(isset($res) && is_array($res) && isset($res[0])){
        $exp=$res[0]['nrCanDownload'];
        return $exp>0;
    }
    else{
        die('Err: Illegal account'.$id);
    }
}
////////////////// AUTHENICATION ///////////////////////
if(!isset($_GET['i']))exit('Err: No video id passed in');
if(!isset($_GET['u']))exit('Err: Authentication Error');
$auth=htmlentities($_GET['u']);
$getdate=getdate();
$day= $getdate['yday'].$getdate['year'];
$id=base64_decode(strrev(base64_decode($auth)));
$idd=explode('|', $id);
$id=intval($idd[0]);
$dd=intval($day);
if(!isset($_GET['f'])){
    if (!checkIdExp($id,$db))exit("Err: Charge Your Account");
    if ($dd!=$day)exit('Err');
}
elseif(strlen($_GET['f'])<1){
    if (!checkIdExp($id,$db))exit("Err: Charge Your Account");
}
//////////////////// VIDEO ID CHK //////////////////////
$my_id = base64_decode($_GET['i']);
if( preg_match('/^https?:\/\/w*.?m?.?youtube.com\//', $my_id) ){
    $url   = parse_url($my_id);
    $my_id = NULL;
    if( is_array($url) && count($url)>0 && isset($url['query']) && !empty($url['query']) ){
        $parts = explode('&',$url['query']);
        if( is_array($parts) && count($parts) > 0 ){foreach( $parts as $p ){$pattern = '/^v\=/';if( preg_match($pattern, $p) ){$my_id = preg_replace($pattern,'',$p);break;}}}
        if( !$my_id ){echo 'Err: No video id passed in';die();}
    }else{
        echo 'Err: Invalid url';
        exit;
    }
}elseif( preg_match('/^https?:\/\/youtu.be/', $my_id) ) {
    $url   = parse_url($my_id);
    $my_id = NULL;
    $my_id = preg_replace('/^\//', '', $url['path']);
}
//////////////////// VIDEO DETAILS /////////////////////
// if(isset($_GET['f'])){
//     $f=base64_decode($_GET['f']);
//     header('Location: ./api/getvideo.php?i='.$my_id.'&format='.$f);
// }
// else header('Location: ./api/getvideo.php?i='.$my_id);
$_GET['i']=$my_id;
$_GET['id']=$id;
if(isset($_GET['f']))$_GET['format']=base64_decode($_GET['f']);
require './api/getvideo.php';