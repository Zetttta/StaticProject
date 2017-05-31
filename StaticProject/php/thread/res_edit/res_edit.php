<!--
 このファイルの概要説明
レスポンスを作成する画面です
 このファイルの詳細説明

 システム名：    スレッド作成画面
 作成者：        内海洋樹
 作成日：        2017/05/22/10:00
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
 $res_text             内容
 $msg                  エラーメッセージ
 $threadMax            スレッドidの最大値
 $newId                作成するスレッドのid
 $nowDate              作成した日時
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
  // 初期化処理
  $msg = "";
  $judgeTF = 0;
     //ユーザー情報
    $Identify = getUser();
    // 管理者フラグ取得
    $judge = getManager($Identify);
  // thread_idが空かどうか判断する処理、空ならリダイレクト
  if(empty($_GET["thread_id"])){
    header('location: ../../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
    exit();
  }

  // thread_idが数値かどうか判断する処理、数値で無い文字があったらリダイレクト
  if(ctype_digit ($_GET["thread_id"])==FALSE){
    header('location: ../../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
    exit();
  }

  // thread_idが0以下かどうか判断する処理、0以下であるならリダイレクト
  if($_GET["thread_id"] <= 0){
    header('location: ../../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
    exit();
  }



  // レスポンスが削除されているかどうかを判断、されているならリダイレクト
  // データベースに接続
  $dbh = connectDb();
  try {
    // m_responseのrsponse_idを抽出
    $sql = "SELECT * FROM m_response";
    $sql .= " WHERE response_id = :search AND thread_id = :thread_search";
    // SQLを準備
    $sth = $dbh->prepare($sql);
    // $_GET["thread_id"]がgetされていることを確認
    if(!empty($_GET["response_id"])){
      // されているのであれば、searchにthread_idを格納
      $sth->bindValue(":search", "{$_GET['response_id']}");
    }
    if(!empty($_GET["thread_id"])){
      // されているのであれば、searchにthread_idを格納
      $sth->bindValue(":thread_search", "{$_GET['thread_id']}");
    }
    // SQLを発行
    $sth->execute();
  } catch (PDOException $e) {
    exit("SQL発行エラー：{$e->getMessage()}");
  }
  // 削除フラグを$judgeDeleteに格納
  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $judgeDelete = $row["response_delete"];
    $user = $row["user_id"];
      if($user == $Identify){
        $judgeTF = 1;
      }else{
      }
  }
  // もしも該当スレッドが削除されているならばリダイレクト
  if($judgeDelete == 1){
    header('location: ../../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
    exit();
  }
    if($judgeTF == 1 || $judge == 1){
    }else{
         header('location: ../../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
         exit();
    }



  // POSTで情報が飛ばされているかの判断
  if($_POST){
      // 内容にデータが入っている場合はスレッドを作成
      // そうで無い場合はこのページに戻る
        // 内容が入力されている場合の処理
        // データベースに接続
        $dbh = connectDb();
        if(empty ($_POST['res_text'])){
          // データベースに接続
          $dbh = connectDb();
          try {
            // m_responseのresponse_innerを抽出
            $sql = "SELECT response_inner FROM m_response t1";
            $sql .= " LEFT OUTER JOIN m_thread t2 ON t1.thread_id = t2.thread_id";
            //レスポンスマスタと結合$sql
            $sql .= " WHERE t2.thread_id = :search AND t1.response_id = :response;";
            // SQLを準備
            $sth = $dbh->prepare($sql);
            // $_GET["thread_id"]がgetされていることを確認
            if(!empty($_GET["thread_id"])){
              // されているのであれば、searchにthread_idを格納
              $sth->bindValue(":thread", "{$_GET['thread_id']}");
            }
            if(!empty($_GET["response_id"])){
              // されているのであれば、searchにthread_idを格納
              $sth->bindValue(":response", "{$_GET['response_id']}");
            }
            // SQLを発行
            $sth->execute();
          } catch (PDOException $e) {
            exit("SQL発行エラー：{$e->getMessage()}");
          }
          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $response_inner = $row["response_inner"];
          }
        }else{
          $response_inner = $_POST['res_text'];
        }


        // 日時の代入
        $nowDate = new DateTime();
        $nowDate = date ('Y/m/d');

        // データベースに接続
        $dbh = connectDb();
        try {
            // レスポンスを作成
            $sql = "UPDATE drag_db.m_response";
            // レスポンスID response_id
            $sql .= " SET";
            // thread_idはGETから
            $sql .= " response_inner = :inner";
            // 削除フラグ
            $sql .= " WHERE thread_id = :thread";
            $sql .= " AND response_id = :response;";
            // SQLを準備
            $sth = $dbh->prepare($sql);

            $sth->bindValue(":inner", "{$_POST['res_text']}");
            $sth->bindValue(":thread", "{$_GET['thread_id']}");
            $sth->bindValue(":response", "{$_GET['response_id']}");
            // SQLを発行
            $sth->execute();
        } catch (PDOException $e) {
            exit("SQL発行エラー：{$e->getMessage()}");
        }
        // 個のスレッドへ飛ばす
        header("location: ../thread_Individual.php?thread_id=" . "{$_GET['thread_id']}" . "&page_id=1"); // リダイレクト処理、トップページのurlにあとで変更する
        exit;
    }else{
        // 内容が空の場合の処理
      $response_text = "";
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- 文字コード宣言 -->
    <meta charset="utf-8">
    <!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../../css/main.css" type="text/css">
    <link rel="stylesheet" href="../../../css/response_id_create.css" type="text/css">
    <!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <script type="text/javascript" src="../../../js/jquery-2-1-1-min.js"></script>
    <!-- fadeConfの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <script type="text/javascript" src="../../../js/fadeConf.js"></script>
    <!-- headMenu(Management版も)の読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    
    <?php
      // 管理者か否かで読み込む、jsファイルを分岐する
      // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
      require_once('../../../function/database_session.php');
      // ユーザー情報
      $Identify = getUser();
      // 管理者フラグ取得
      $judge = getManager($Identify);
      if($judge==1){
        echo '<script type="text/javascript" src="../../../js/headMenuManagement.js"></script>';
      }else{
        echo '<script type="text/javascript" src="../../../js/headMenu.js"></script>';
      }
      // ログインしてないならログインページへ
      loginIdentify();

    ?>
    <title>レスポンス作成</title>
  </head>

  <body>
    <div id="wrapper">
      <?php require_once('../../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
      <div id="content">

          <br><br><br>
          <span id="title">レスポンス作成</span><br>
          <form action="" method="post" class="res_edit">
            内容：<br>
            <?php
            try {
                // m_responseのresponse_idを抽出、最大値を求めるので、削除されているものも抽出する
                $sql = "SELECT response_inner FROM m_response";
                //
                $sql .= " WHERE  response_id = :res_id AND thread_id = :thread_id"; // れすid判断してください
                // SQLを準備
                $sth = $dbh->prepare($sql);
                $sth->bindValue(":res_id", "{$_GET['response_id']}"); //
                $sth->bindValue(":thread_id", "{$_GET['thread_id']}"); //
                // SQLを発行
                $sth->execute();
            } catch (PDOException $e) {
                exit("SQL発行エラー：{$e->getMessage()}");
            }
            //
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $response_inner = $row["response_inner"];
            }?>
            <textarea cols=67 rows=15 name="res_text"><?php

                  ph($response_inner); ?></textarea><br>

            <!-- SESSIONでログインしているユーザーのユーザーidを飛ばします -->
            <input type="hidden" name="user_id" value="abc">
            <!-- 今は仮でuser_idという名前でabc(静的悦子)を飛ばしてます -->
            <input type="submit" value="作成">
          </form>
          </div>
        </div>

      <br><br>

      <br><br><br>



      </div>
      <?php require_once('../../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
    </div>
  </body>
</html>
