<?php
/**
* 店舗作成機能
*
* 新たな店舗を作成します。
* 店舗名。
* 店舗の住所。
* 店舗の電話番号。
* を入力します。
* 店舗のIDは自動で作られます。
*
*
* システム名：店舗作成機能
* 作成者：朴
* 作成日：2017/05/24
* 最終更新日：2017/05/24
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/

require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
require_once("../../../function/xss.php");
    $dbh = connectDB();

    try{
    $sql = "INSERT INTO drag_db.m_store(store_id,store_name,store_address,store_phone,store_delete)";
    //店舗の情報を入力します。
    $sql .= " SELECT COALESCE(MAX(store_id)+1,1),";
    //店舗のIDが自動で作られます。全ての店舗の数がら1を添えてIDが決定されます。
    $sql .= ":name,";
    $sql .= ":add,";
    $sql .= ":phone,";
    $sql .= "0";
    $sql .= " FROM m_store;";

    $sth = $dbh->prepare($sql);
    $sth->bindValue(":name", $_POST["shop_name"]);
    $sth->bindValue(":add", $_POST["shop_add"]);
    $sth -> bindValue(":phone", $_POST["shop_phone"]);
    $sth->execute();//sql発行


   }catch (PDOException $e) {
      exit("SQL発行エラー：{$e->getMessage()}");
   }


require_once("store_select.php");
