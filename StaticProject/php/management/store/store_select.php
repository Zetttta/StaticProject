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
* 最終更新日：2017/05/23
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/
require_once("../../../function/database_session.php");//関数を読み込んでDBを読み込みます。
require_once("../../../function/xss.php");
    $dbh = connectDB();
      if(isset($_GET["page_id"])) {
        $page = $_GET["page_id"];
      } else {
        $page = 1;
      }
      try {
        // m_userのuser_idを抽出
        $sql = "SELECT COUNT(*) FROM m_store";
        // 削除されていないスレッドのthread_idを抽出
        $sql .= " WHERE store_delete = FALSE";

        // SQLを準備
        $sth = $dbh->prepare($sql);
        // SQLを発行
        $sth->execute();
      } catch (PDOException $e) {
        exit("SQL発行エラー：{$e->getMessage()}");
      }
      while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
        $userCount = $row["COUNT(*)"];
      }
      $connection=connectDB();
      try{
      $sql  = "SELECT * FROM m_store WHERE store_delete=false";
      if($userCount >= 11){

        // 開始レスNo：ページid*10+1-10
        // 終了：ページid*10+10-10
        // の間だけ(10件)セレクトする。
        // 既に0は除外されているので+1の処理は不要
        $pageCalcS = $page*10-10;
        // 開始位置から10件表示する
        $sql .= " LIMIT 10 offset $pageCalcS";
      }
      $sth  = $connection->prepare($sql);
      $sth->execute();
    }catch (PDOException $e){
      exit("SQL発行エラー:{$e->getMessage()}");
    }
    if(empty($page)){
      $page = 1;
    }

 require_once("store_select_view.php");
