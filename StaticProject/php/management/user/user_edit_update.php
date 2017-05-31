<?php
/*
 * このファイルの概要説明：編集したユーザデータをデータベースに反映
 * このファイルの詳細説明：編集したユーザデータをデータベース反映させる。
 　　　　　　　　　　　　　画面には表示されず、そのままユーザ選択画面に遷移する。
 *
 * システム名：管理者ユーザー編集画面
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
        && empty($_POST["user_edit_pass"])
        && empty($_POST["user_edit_mail"])
        && empty($_POST["user_edit_manager"])
        && empty($_POST["user_edit_quali"])
        && empty($_POST["user_edit_id"])
        && empty($_POST["user_edit_position"])
        && empty($_POST["user_edit_store"])
){
  print "必須項目を入力してください。";
  exit;
};


//プレースホルダつきのSQLを発行します。
$sql = "UPDATE m_user ";
$sql .="SET
        user_name    =:user_name,
        store_id     =:store_id,
        post_id      =:post_id,
        corner_id    =:corner_id,
        user_right   =:user_right,
        user_profile =:user_profile,
        user_id      =:user_id,
        user_delete  =:user_delete,
        user_email   =:user_email,
        user_pw      =:user_pw ,
        user_manager      =:user_manager ";

//ここのWHERE文に関しては謎が残っている。
$sql .="WHERE user_id=:user_id" ;
//var_dump($_POST);
$sth = $dbh->prepare($sql);


//プレースホルダに値をバインド
$sth->bindValue(":user_name", $_POST["user_edit_name"]);
$sth->bindValue(":store_id", $_POST["user_edit_store"]);
$sth->bindValue(":post_id", $_POST["user_edit_position"]);
$sth->bindValue(":corner_id", $_POST["user_edit_department"]);
$sth->bindValue(":user_right", $_POST["user_edit_quali"]);
$sth->bindValue(":user_profile", $_POST["user_edit_text"]);
$sth->bindValue(":user_id", $_POST["user_edit_id"]);
$sth->bindValue(":user_delete", $_POST["user_id_delete"]);
$sth->bindValue(":user_email", $_POST["user_edit_mail"]);
$sth->bindValue(":user_pw", $_POST["user_edit_pass"]);
$sth->bindValue(":user_manager", $_POST["user_edit_manager"]);

//var_dump($sth);
//SQLを発行
$sth->execute();

//var_dump($_POST);
require_once('user_select.php');
