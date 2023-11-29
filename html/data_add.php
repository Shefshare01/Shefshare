<?php

include 'mysqli.php';
include 'logout.php';
$mysqli = new mysqli ('db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com', 'root', 'port2023', 'usbdata');
$db = new MysqliDb ($mysqli);

$type=$_POST['type'];
$del=$_POST['del_check'];
session_start();

if($type == 'admin'){

    $id = $_POST['id'];
    $pw = $_POST['pw'];

    if($del == 0){

        $db->where('id',$id);
        $res = $db->get('member');


        if(count($res) > 0) {
        echo "아이디가 이미 존재합니다.";
        }else {
        $data = array('id' => $id, 'pw' => $pw);
        $db->insert('member', $data);
        echo "아이디가 추가되었습니다.";

        $data = array('id' => $id, 'log' => $pw);
        $db->insert('log', $data);
        }


    }else {

        $db->where('id',$id);
        $res = $db->get('member');

        if(count($res) == 0){
        echo "아이디가 존재하지 않습니다";
        }else {
        $db->where('id',$id);
        $db->delete('member');
        echo "아이디가 삭제되었습니다.";
        logout('username');
        }


    }

}else if($type == 'vpid') {

    $vid = $_POST['vid'];
    $pid = $_POST['pid'];


    if ($del == 0) {

        $db->where('vid', $vid);
        $db->where('pid', $pid);
        $res = $db->get('vpid');


        if (count($res) > 0) {
            echo "이미 vid,pid가 존재합니다.";
        } else {
            $data = array('vid' => $vid, 'pid' => $pid);
            $db->insert('vpid', $data);
            echo "VID/PID가 추가되었습니다.";
        }


    } else {

        $db->where('vid', $vid);
        $db->where('pid', $pid);
        $res = $db->get('vpid');

        if (count($res) == 0) {
            echo "VID/PID가 존재하지 않습니다";
        } else {
            $db->where('vid', $vid);
            $db->where('pid', $pid);
            $db->delete('vpid');
            echo "VID/PID가 삭제되었습니다.";
        }


    }
}




?>