<!--
 このファイルの概要説明

 このファイルの詳細説明

 システム名：    スレッド削除用画面
 作成者：        伊藤尚輝
 作成日：        2017/05/23/9:20
 最終更新日：    2017/05/
 レビュー担当者：
 レビュー日：    
 バージョン：    
-->

<!--
 使用ファイル
 main.css           共通スタイルシート
 header.php             ヘッダー表示用
 footer.php             フッター表示用
 headMenu.js            右上メニュー処理
 jquery-2-1-1-min.js    jQuery
 xss.php                XSS攻撃対策用関数
 database_session.php   データベース接続用
 fadeConf.js            非公開設定ボタン処理
 
 使用変数(sql関連以外)
 $thread_title         件名
 $thread_inner         内容
 $msg                  エラーメッセージ
 $privateMenber        非公開参加者配列
 $menberCount          fadeConfループ処理用変数
 $menberCountR         fadeConfループ処理用変数
 $userBoxCount         fadeConfループ処理用変数
 $userCount            ユーザーの人数
 $userCheck            ループの回数
 -->

<?php
  require_once("../../../function/xss.php");          // xss対策外部関数ファイルの読み込み
  require_once("../../../function/database_session.php");          // データベース接続関連外部関数ファイルの読み込み
  // 初期化処理  // ユーザー情報
  $Identify = getUser();
  // 管理者フラグ取得
  $judge = getManager($Identify);
  // GETで飛ばされたスレッドidの判断
    $threadMax = 0;
  $judgeDelete = 0;
    $fadeConfFlag = 0;

  
  // thread_idが空かどうか判断する処理、空ならリダイレクト
  if(empty($_GET["thread_id"])){
    header('location: ../toppage/toppage.php');// リダイレクト
  }
  
  // thread_idが数値かどうか判断する処理、数値で無い文字があったらリダイレクト
  if(ctype_digit ($_GET["thread_id"])==FALSE){
    header('location: ../toppage/toppage.php');// リダイレクト
    exit();
  }
  
  // thread_idが0以下かどうか判断する処理、0以下であるならリダイレクト
  if($_GET["thread_id"] <= 0){
    header('location: ../toppage/toppage.php');// リダイレクト
    exit();
  }
  
  // データベースのm_threadのthread_id最大値より大きいか判断する。大きいなら存在しない為リダイレクト
  // データベースに接続
  $dbh = connectDb();
  try {
    // m_threadのthread_idを抽出、最大値を求めるので、削除されているものも抽出する
    $sql = "SELECT thread_id FROM m_thread";
    // 最大値のみを取得する。
    $sql .= " WHERE  thread_id=(SELECT MAX(thread_id) FROM m_thread)";
    // SQLを準備
    $sth = $dbh->prepare($sql);
    // SQLを発行
    $sth->execute();
  } catch (PDOException $e) {
    exit("SQL発行エラー：{$e->getMessage()}");
  }
  // 現在のスレッド最大idを$threadMaxに格納
  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $threadMax = $row["thread_id"];
  }
  if($_GET["thread_id"] > $threadMax){
    header('location: ../toppage/toppage.php');// リダイレクト
    exit();
  }
  
  // スレッドが削除されているかどうかを判断、されているならリダイレクト
  // データベースに接続
  $dbh = connectDb();
  try {
    // m_threadのthread_idを抽出
    $sql = "SELECT thread_delete FROM m_thread";
    $sql .= " WHERE thread_id = :search";
    // SQLを準備
    $sth = $dbh->prepare($sql);
    // $_GET["thread_id"]がgetされていることを確認
    if(!empty($_GET["thread_id"])){
      // されているのであれば、searchにthread_idを格納
      $sth->bindValue(":search", "{$_GET['thread_id']}");
    }
    // SQLを発行
    $sth->execute();
  } catch (PDOException $e) {
    exit("SQL発行エラー：{$e->getMessage()}");
  }
  // 削除フラグを$judgeDeleteに格納
  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $judgeDelete = $row["thread_delete"];
  }
  // もしも該当スレッドが削除されているならばリダイレクト
  if($judgeDelete == TRUE){
header('location: ../../toppage/toppage.php');// リダイレクト
    exit();
  }
  $dbh = connectDb();
  try {
    // m_threadのthread_fadeを抽出
    $sql = "SELECT thread_fade FROM m_thread";
    $sql .= " WHERE thread_id = :search;";
    // SQLを準備
    $sth = $dbh->prepare($sql);
    // $_GET["thread_id"]がgetされていることを確認
    if(!empty($_GET["thread_id"])){
      // されているのであれば、searchにthread_idを格納
      $sth->bindValue(":search", "{$_GET['thread_id']}");
    }
    // SQLを発行
    $sth->execute();
  } catch (PDOException $e) {
    exit("SQL発行エラー：{$e->getMessage()}");
  }
  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    // 非公開の場合の処理
    // 非公開の場合、ログインしているユーザーが参加者に存在するかを確認する。
    if($row["thread_fade"] == TRUE){
      $fadeConfFlag = 1;
      // ここに非公開処理
      // 非公開だったらm_entryを調べ、自分がいるならば処理をしない
      // 自分がいないならばリダイレクト
    }
  }
  
  echo"{$_GET['thread_id']}";
  
  try {
        // スレッド削除処理
        $sql = "UPDATE drag_db.m_thread";
        $sql .= " SET";
        $sql .= " thread_delete = 1";
        $sql .= " WHERE thread_id = :id;";
        // SQLを準備
        $sth = $dbh->prepare($sql);
        $sth->bindValue(":id", "{$_GET['thread_id']}");
        // SQLを発行
        $sth->execute();
  } catch (PDOException $e) {
    exit("SQL発行エラー：{$e->getMessage()}");
  }
  header('location: ../thread_main.php');// リダイレクト
  exit;
?>