<?php
/**
* 店舗の編集機能
*
* 店舗の編集表示機能です。
* DBからm_storeテーブルを読み込みます。
*
*
* システム名：店舗のリスト機能
* 作成者：朴
* 作成日：2017/05/23
* 最終更新日：2017/05/23
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/

require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
require_once("../../../function/xss.php");
$dbh = connectDB();
$sql = "select * from drag_db.m_store where store_id =:id and store_delete=false";
$sth = $dbh->prepare($sql);
$sth -> bindValue(":id",$_GET["id"]);
$sth->execute();//sql発行
$row=$sth->fetch(PDO::FETCH_ASSOC);//結果データをを利得

if(empty($row)){//結果データがない場合。
  exit("不正なアクセスをしました。");
}

require_once("store_edit_view.php");
