<?php
/**
 * 削除の進行
 *
 * 編集ページから削除ボタンをクリックされば発動します。
 * confirmの後削除フラグがTRUEになってお知らせが見えなくなります。
 * 実際にデータは削除されません。
 *
 *
 *
 * システム名：削除機能
 * 作成者：朴
 * 作成日：2017/05/18
 * 最終更新日：2017/05/22
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
 */

require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
$dbh = connectDB();

$sql = "update drag_db.m_notice ";
$sql .= "set notice_delete=true,notice_id=:news_edit_id ";
$sql .= "where notice_id=:news_edit_id and notice_delete=false";
$sth = $dbh->prepare($sql);

$sth -> bindValue(":news_edit_id",$_POST["news_edit_id"]);
$sth -> bindValue("true",$_POST["news_edit_delete"]);

$sth->execute();

require_once("news_select.php");
