<?php
/**
 * 編集の進行
 *
 * 編集の機能を進行するプログラム。
 * news_edit.phpで読み込んでいる値を
 * news_edit_view.phpで入力された各値をここで入力した値で帰ります。
 *
 *
 *
 * システム名：編集機能(編集進行)
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
$sql .= "set notice_title=:news_edit_name, notice_start=:news_edit_start, notice_end=:news_edit_end,notice_content=:news_edit_text ";
$sql .= "where notice_id=:news_edit_id and notice_delete=false";
$sth = $dbh->prepare($sql);

$sth->bindValue(":news_edit_name", $_POST["news_edit_name"]);//修正された値を投げる。
$sth->bindValue(":news_edit_start", $_POST["news_edit_start"]);
$sth->bindValue(":news_edit_end", $_POST["news_edit_end"]);
$sth->bindValue(":news_edit_text", $_POST["news_edit_text"]);
$sth -> bindValue(":news_edit_id",$_POST["news_edit_id"]);



$sth->execute();

require_once("news_select.php");
