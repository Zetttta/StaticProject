<!--
/**
 * このファイルの概要説明
 *
 * このファイルの詳細説明
 *
 * システム名：編集機能(読み込む)
 * 作成者：朴
 * 作成日：2017/05/18
 * 最終更新日：2017/05/18
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
 */
-->
<?php

require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
require_once("../../../function/xss.php");
$dbh = connectDB();
$sql = "select * from drag_db.m_notice where notice_id =:id and notice_delete=false";
$sth = $dbh->prepare($sql);
$sth -> bindValue(":id",$_GET["notice_id"]);
$sth->execute();//sql発行
$row=$sth->fetch(PDO::FETCH_ASSOC);//結果データをを利得

if(empty($row)){//結果データがない場合。
  exit("不正なアクセスをしました。");
}

require_once("news_edit_view.php");
