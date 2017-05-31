<!--
 このファイルの概要説明
レスポンスを作成する画面です
 このファイルの詳細説明

 システム名：    スレッド作成画面
 作成者：        内海
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
   //ユーザー情報
    $Identify = getUser();
 
  
  // POSTで情報が飛ばされているかの判断
  if($_POST){
      // 内容にデータが入っている場合はスレッドを作成
      // そうで無い場合はこのページに戻る

        // 内容が入力されている場合の処理
        // データベースに接続
        $dbh = connectDb();
        try {
            // m_responseのresponse_idを抽出、最大値を求めるので、削除されているものも抽出する
            $sql = "SELECT response_id FROM m_response";
            // 最大値のみを取得する。
            $sql .= " WHERE  response_id=(SELECT MAX(response_id) FROM m_response)";
            // SQLを準備
            $sth = $dbh->prepare($sql);
            // SQLを発行
            $sth->execute();
        } catch (PDOException $e) {
            exit("SQL発行エラー：{$e->getMessage()}");
        }
        // 現在のスレッド最大idを$responseMaxに格納
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $responseMax = $row["response_id"];
        }
        // 作成するレスポンスのidは現在作られているレスポンスのidの最大値+1
        $newId = $responseMax + 1;
        // 日時の代入
        $nowDate = new DateTime();
        $nowDate = date ('Y/m/d');

        // データベースに接続
        $dbh = connectDb();
        try {
            // レスポンスを作成
            $sql = "INSERT INTO drag_db.m_response";
            // レスポンスID response_id
            $sql .= " VALUES(:id,";
            // thread_idはGETから
            $sql .= ":sid,";
            // user_id
            $sql .= ":user,";
            // 日時
            $sql .= ":date,";
            // 本文
            $sql .= ":inner,";
            // 削除フラグ
            $sql .= "FALSE);";
            // SQLを準備
            $sth = $dbh->prepare($sql);
            $sth->bindValue(":id", "{$newId}");
            $sth->bindValue(":sid", "{$_GET['thread_id']}");
            $sth->bindValue(":user", "{$Identify}"); //
            $sth->bindValue(":date", "{$nowDate}");
            $sth->bindValue(":inner", "{$_POST['res_text']}");
            // SQLを発行
            $sth->execute();
        } catch (PDOException $e) {
            exit("SQL発行エラー：{$e->getMessage()}");
        }
        // スレッド一覧へ飛ばす
        header('location: ../thread_Individual.php'); // リダイレクト処理、トップページのurlにあとで変更する
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
          <form action="" method="post" class="res_create">
            内容：<br>
            <textarea cols=67 rows=15 name="res_text"><?php ph($response_text) ?></textarea><br>
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
