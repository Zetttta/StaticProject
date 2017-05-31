<?php
/**
* 店舗のリスト表示機能
*
* 店舗のリスト表示機能です。
* 店舗の名前を押せば個別店舗の情報を表示されます。
* 編集ボタンを押して情報を編集することができます。
*
* システム名：店舗のリスト機能
* 作成者：朴
* 作成日：2017/05/23
* 最終更新日：2017/05/24
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/

require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
$dbh = connectDB();

$sql = "update drag_db.m_store ";
$sql .= "set
         store_name=:shop_edit_name,
         store_address=:shop_edit_add,
         store_phone=:shop_edit_phone,
         store_id=:store_edit_id ";
$sql .= "where store_id=:store_edit_id and store_delete=false";
$sth = $dbh->prepare($sql);

$sth->bindValue(":shop_edit_name", $_POST["shop_edit_name"]);//修正された値を投げる。
$sth->bindValue(":shop_edit_add", $_POST["shop_edit_add"]);
$sth->bindValue(":shop_edit_phone", $_POST["shop_edit_phone"]);
$sth->bindValue(":store_edit_id", $_POST["store_edit_id"]);




$sth->execute();

require_once("store_select.php");
