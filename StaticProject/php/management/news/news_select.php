<?php
/**
 * お知らせ一覧を表示する。
 *
 * お知らせ一覧を表示する。
 * お知らせのTITLEをクリックすれば個別のお知らせページへ移動。
 *
 * システム名：ログインシステム
 * 作成者：朴
 * 作成日：2017/05/18
 * 最終更新日：2017/05/21
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
 */

 require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
 require_once("../../../function/xss.php");
 $dbh = connectDB();
 $sql = "select * from drag_db.m_notice where notice_delete=false order by notice_start desc";
 $sth = $dbh->prepare($sql);
 $sth->execute();




 require_once("news_select_view.php");
