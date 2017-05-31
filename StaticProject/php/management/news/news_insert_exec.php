<?php
/**
* お知らせ新規作成機能
*
* お知らせ新規作成します。
* お知らせ件名。
* 掲示開始時間。
* 掲示終了時間。
* お知らせ内容。
* を作成します。
* 作成ボタンを押すと作成できます。
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
    $sql = "INSERT INTO drag_db.m_notice(notice_id,notice_title,notice_start,notice_end,notice_content,notice_delete)";
    //店舗の情報を入力します。
    $sql .= " SELECT COALESCE(MAX(notice_id)+1,1),";
    //店舗のIDが自動で作られます。全ての店舗の数がら1を添えてIDが決定されます。
    $sql .= ":title,";
    $sql .= ":start,";
    $sql .= ":end,";
    $sql .= ":content,";
    $sql .= "0";
    $sql .= " FROM m_notice;";

    $sth = $dbh->prepare($sql);
    $sth->bindValue(":title", $_POST["news_insert_name"]);
    $sth->bindValue(":start", $_POST["news_insert_start"]);
    $sth->bindValue(":end", $_POST["news_insert_end"]);
    $sth -> bindValue(":content", $_POST["news_insert_text"]);
    $sth->execute();//sql発行


   }catch (PDOException $e) {
      exit("SQL発行エラー：{$e->getMessage()}");
   }


require_once("news_select.php");
