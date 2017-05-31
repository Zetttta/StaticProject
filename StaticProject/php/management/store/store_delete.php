<?php
/**
* 店舗削除機能
*
* 店舗削除機能です。
* 削除ボタンを押せば、確認のウィンドーがでるあと、確認すれば削除、
* 取り消しすれば削除されません。
*
*
* システム名：店舗のリスト機能
* 作成者：朴
* 作成日：2017/05/24
* 最終更新日：2017/05/24
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/


require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
$dbh = connectDB();

$sql = "update drag_db.m_store ";
$sql .= "set store_delete=true,store_id=:store_edit_id ";
$sql .= "where store_id=:store_edit_id and store_delete=false";
$sth = $dbh->prepare($sql);

$sth -> bindValue(":store_edit_id",$_POST["store_edit_id"]);
$sth -> bindValue("true",$_POST["store_edit_delete"]);

$sth->execute();

require_once("store_select.php");
