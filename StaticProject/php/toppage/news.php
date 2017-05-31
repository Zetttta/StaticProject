<?php
/**
 * 個別のお知らせを表示します。
 *
 * お知らせを表示する。
 *
 * システム名：ログインシステム
 * 作成者：朴
 * 作成日：2017/05/18
 * 最終更新日：2017/05/18
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
 */

 require_once("../../function/database_session.php");//関数を読み込んでDBを読み込みます。
 require_once("ph.php");
 $dbh = connectDB();
 $sql = "select * from drag_db.m_notice";
 $sql .= " where notice_id =:notice_id and notice_delete=false";
 $sth = $dbh->prepare($sql);
 $sth -> bindValue(":notice_id",$_GET["notice_id"]);
 $sth->execute();//sql発行
 $row=$sth->fetch(PDO::FETCH_ASSOC);//結果データをを利得



 require_once("news_view.php");
