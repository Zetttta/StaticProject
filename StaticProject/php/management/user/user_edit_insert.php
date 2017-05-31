<?php
/*
 * このファイルの概要説明：追加したユーザデータをデータベースに反映
 * このファイルの詳細説明：使いしたユーザデータをデータベース反映させる。
 　　　　　　　　　　　　　画面には表示されず、成功すればそのままユーザ選択画面に遷移する。
 　　　　　　　　　　　　　失敗した場合にはその旨を表示する。
 *
 * システム名：管理者ユーザー追加
 * 作成者：坂田恵未
 * 作成日：2017/5/22
 * 最終更新日：2017/5/22
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
*/

//DBに接続します。
require_once("../../../function/database_session.php");
$dbh=connectDB();
$user = getUser();
$manager = getManager();
loginIdentify();
$manager_decision = Manager($user);
try{
  $sql  = "SELECT * FROM m_user ORDER BY user_id ";
  $sth  = $dbh->prepare($sql);
  $sth->execute();
}catch (PDOException $e){
  exit("SQL発行エラー:{$e->getMessage()}");
}

if(empty($_POST["user_edit_name"])
        || empty($_POST["user_edit_pass"])
        || empty($_POST["user_edit_mail"])
        || empty($_POST["user_edit_manager"])
        || empty($_POST["user_edit_quali"])
        || empty($_POST["user_edit_id"])
        || empty($_POST["user_edit_position"])
        || empty($_POST["user_edit_store"])
){
  print "必須項目を入力してください。";
  exit;
};

//プレースホルダつきのSQLを発行します。
$sql = "INSERT INTO m_user ";
$sql .="VALUES (:user_id,
                :user_pw,
                :user_email,
                :user_name,
                :store_id,
                :user_profile,
                :user_manager,
                :user_delete,
                :corner_id,
                :post_id,
                :user_right ) ";


//ここのWHERE文に関しては謎が残っている。
//$sql .="WHERE user_id=:user_id";
$sth = $dbh->prepare($sql);



//プレースホルダに値をバインド
$sth->bindValue(":user_id",            $_POST["user_edit_id"] );
$sth->bindValue(":user_pw",          $_POST["user_edit_pass"] );
$sth->bindValue(":user_email",         $_POST["user_edit_mail"] );
$sth->bindValue(":user_name",          $_POST["user_edit_name"] );
$sth->bindValue(":store_id",         $_POST["user_edit_store"] );
$sth->bindValue(":user_profile",          $_POST["user_edit_text"] );
$sth->bindValue(":user_manager",          $_POST["user_edit_manager"] );
$sth->bindValue(":user_delete",        $_POST["user_edit_delete"] );
$sth->bindValue(":corner_id",    $_POST["user_edit_department"] );
$sth->bindValue(":post_id",      $_POST["user_edit_position"] );
$sth->bindValue(":user_right",         $_POST["user_edit_quali"] );

var_dump($_POST);

/*
$sql2 ="INSERT INTO m_store(store_id) ";
$sql2 .="VALUES :user_edit_store ";
$sth2 = $dbh->prepare($sql2);

$sth2->bindValue(":user_edit_store",     $_POST["user_edit_store"] );


$sql3 ="INSERT INTO m_corner(corner_id) ";
$sql3 .="VALUES :user_edit_department";
$sth3 = $dbh->prepare($sql3);
$sth3->bindValue(":user_edit_department",    $_POST["user_edit_department"] );


$sql4 ="INSERT INTO m_post(post_id) ";
$sql4 .="VALUES :user_edit_position";
$sth4 = $dbh->prepare($sql3);
$sth4->bindValue(":user_edit_position",    $_POST["user_edit_position"] );
*/
//var_dump($sth);

//SQLを発行
$sth->execute();
//$sth2->execute();
//$sth3->execute();
//$sth4->execute();

require_once('user_select.php');
