<?php
require(__DIR__."/../config/database.php");

require(__DIR__."/../config/napthe.php"); // nhúng file kết nối data
if(isset($_GET['status'])) {
    $status = $_GET['status'];
	$code = $_GET['card_code'];
	$serial = $_GET['card_seri'];
	$thucnhan = $_GET['value_receive'];
	$sotien = $_GET['pricesvalue'];
	$tiennhan = ($sotien*70)/100;
    $tranid = $_GET['requestid'];
    $check_card = mysqli_fetch_row(mysqli_query($connect, "SELECT * FROM `napthe` WHERE `mathe` = '".$code."' AND `serial` = '".$serial."'"));
   
    if($check_card > 0)
    {
        $data_card = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM `napthe` WHERE `mathe` = '".$code."' AND `serial` = '".$serial."'"));
       
        if($data_card['trangthai'] == 0)
        {
            if($status == 200){
                $uid = $data_card['uid'];
                mysqli_query($connect, "UPDATE user SET tien = tien + $tiennhan WHERE `id` = $uid");
                mysqli_query($connect, "UPDATE user SET toptien = toptien + $tiennhan WHERE `id` = $uid");
                mysqli_query($connect, "UPDATE `napthe` SET `trangthai` = 2 WHERE `mathe` = '".$code."' AND `serial` = '".$serial."'");
            }
            else{
                mysqli_query($connect, "UPDATE `napthe` SET `trangthai` = 1 WHERE `mathe` = '".$code."' AND `serial` = '".$serial."'");
            }
            echo json_encode(array(
                'status' => 200,
                'message' => 'Đã nhận callback'
            ));
        }
        else{
            echo json_encode(array(
                'status' => 403,
                'message' => 'Không thể thay đổi trạng thái'
            ));
        }
    }
    else
    {
        echo json_encode(array(
            'status' => 404,
            'message' => 'Không tìm thấy đơn hàng'
        ));
    }

}
