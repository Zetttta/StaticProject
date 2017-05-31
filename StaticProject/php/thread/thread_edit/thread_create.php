<!--
 このファイルの概要説明

 このファイルの詳細説明

 システム名：    スレッド作成画面
 作成者：        伊藤尚輝
 作成日：        2017/05/21/10:00
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
  $menberCount = 0;
  $menberCountR = 0;
  $userBoxCount = 0;
  $userCheck = 0;
  // ユーザー情報
  $Identify = getUser();
  // 管理者フラグ取得
  $judge = getManager($Identify);
  // POSTで情報が飛ばされているかの判断
  if($_POST){
      // 両方にデータが入っている場合はスレッドを作成
      // そうで無い場合はこのページに戻る
      // どちらかが空の場合の処理
      if(empty ($_POST['thread_title'] && $_POST['thread_inner'])){
        if(empty ($_POST['thread_title'])){
          $thread_title = "";
        }else{
          $thread_title = $_POST['thread_title'];
        }
        if(empty ($_POST['thread_inner'])){
          $thread_inner = "";
        }else{
          $thread_inner = $_POST['thread_inner'];
        }
        $msg = "件名と内容を両方入力して下さい";
      }else{
        // 両方入力されている場合の処理
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
        // 作成するスレッドのidは現在作られているスレッドのidの最大値+1
        $newId = $threadMax + 1;
        // 日時の代入
        $nowDate = new DateTime();
        $nowDate = date ('Y/m/d');
        // データベースに接続
        $dbh = connectDb();
        try {
            // スレッドを作成
            $sql = "INSERT INTO drag_db.m_thread";
            $sql .= " VALUES(:id,";
            // 件名
            $sql .= ":title,";
            // 非公開フラグ
            // $_POST["fade_flag"]が投げられているかを判断
            if($_POST["fade_flag"]){
              // 投げられているならTRUEか判断
              if($_POST["fade_flag"] == TRUE){
                $sql .= "TRUE,";
              }else{
                // TRUEでないならFALSE
                $sql .= "FALSE,";
              }
            }else{
              // 投げられていないならFALSE
              $sql .= "FALSE,";
            }
            // 削除フラグ
            $sql .= "FALSE);";
            // SQLを準備
            $sth = $dbh->prepare($sql);
            $sth->bindValue(":id", "{$newId}");
            $sth->bindValue(":title", "{$_POST['thread_title']}");
            // SQLを発行
            $sth->execute();
        } catch (PDOException $e) {
            exit("SQL発行エラー：{$e->getMessage()}");
        }
        // データベースに接続
        $dbh = connectDb();
        try {
            // レスポンス(スレッド本文)を作成
            $sql = "INSERT INTO drag_db.m_response";
            // スレッド本文なのでresponse_idは0固定
            $sql .= " VALUES(0,";
            // thread_idは同じ
            $sql .= ":id,";
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
            $sth->bindValue(":user", "$Identify"); // 仮でPOST、SESSIONに変える
            $sth->bindValue(":date", "{$nowDate}");
            $sth->bindValue(":inner", "{$_POST['thread_inner']}");
            // SQLを発行
            $sth->execute();
        } catch (PDOException $e) {
            exit("SQL発行エラー：{$e->getMessage()}");
        }
        // 非公開フラグがTRUEであれば、非公開参加者メンバーを飛ばす処理
        // 投げられているならTRUEか判断
        if($_POST["fade_flag"] == TRUE){
          // ここに飛んできていることは分かった
          // ここに$_POST["private_member"]で受け取った配列を飛ばす処理
          $private_member = $_POST["private_member"];
          // スレッド作成者も追加
          // $private_member = array_push ($private_member, $_POST["user_id"]); // 仮にポストでユーザーidを送っている
          $private_member = array_merge((array)$private_member, (array)$Identify);
          foreach($private_member as $value){
          // データベースに接続
            $dbh = connectDb();
            try {
                // 参加者マスタに参加者をインサート
                $sql = "INSERT INTO drag_db.m_entry(entry_id,user_id,thread_id,entry_delete)";
                // 最大値+1
                $sql .= " SELECT COALESCE(MAX(entry_id)+1,1),";
                
                // user_id
                $sql .= ":user,";
                
                // thread_idは同じ
                $sql .= ":id,";
                // 削除フラグ
                $sql .= "0";
                $sql .= " FROM m_entry;";
                // SQLを準備
                $sth = $dbh->prepare($sql);
                $sth->bindValue(":user", "{$value}");
                $sth->bindValue(":id", "{$newId}");
                // SQLを発行
                $sth->execute();
            } catch (PDOException $e) {
                exit("SQL発行エラー：{$e->getMessage()}");
            }
          }
        }
        
        
        
        
        // スレッド一覧へ飛ばす
        header('location: ../thread_main.php?page_id=1');// リダイレクト
        exit;
      }
    }else{
      $thread_title = "";
      $thread_inner = "";
    }
    // ユーザーの人数を数える
    
        // レスポンスidの削除されていない数を数える
        $dbh = connectDb();
        try {
            // m_userを抽出
            $sql = "SELECT COUNT(*) FROM m_user";
            // 開いているページのthread_idと同じものを抽出
            $sql .= " WHERE user_delete = FALSE";
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
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- 文字コード宣言 -->
    <meta charset="utf-8">
    <!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../../css/main.css" type="text/css">
    <link rel="stylesheet" href="../../../css/thread_create.css" type="text/css">
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
    ?>
    <title>スレッド作成</title>
  </head>

  <body>
    <div id="wrapper">
      <?php require_once('../../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
      <div id="content">
        <div id="createBox">
          <div id="fadeConf">
          非公開設定
          </div>
          <span id="title">スレッド作成</span><br>
          <?php echo"{$msg}"; ?>
          <form action="" method="post" class="thread_create">
          <div id="fadeConfBox">
            <!-- 非公開設定パネル -->
              設定パネル<br>
              <input type="checkbox" name="fade_flag" value=TRUE>非公開にする<br>
              
              <?php
                  // ここから名前書き出し処理
                  // データベースに接続
                  $dbh = connectDb();
                  try {
                      // ユーザーidと名前を取得
                      $sql = "SELECT user_id,user_name FROM m_user";
                      // 削除されていないもののみ取得
                      $sql .= " WHERE user_delete = FALSE";
                      // SQLを準備
                      $sth = $dbh->prepare($sql);
                      // SQLを発行
                      $sth->execute();
                  } catch (PDOException $e) {
                      exit("SQL発行エラー：{$e->getMessage()}");
                  }
                  while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                    // 最初のだけ処理
                    if($menberCount == 0 && $menberCountR == 0){
                      echo'<div class="user_box" id="user_box' . "{$userBoxCount}" . '">';
                      echo"<table>";
                    }
                    if($menberCount == 0){
                      echo"<tr>";
                    }
                    // 配列private_member[]で送る、valueはユーザーidが入る
                    echo '<td><input type="checkbox" name="private_member[]" value="';
                    // ここにユーザーid
                    ph($row["user_id"]);
                    echo '">';
                    // ここにユーザーネーム
                    ph($row["user_name"]);
                    echo '</td>';
                    $menberCount += 1;
                    // チェックした人数を増やす
                    $userCheck +=1;
                    // 4人テーブルを作成したら
                    if($menberCount == 4){
                      // テーブルを改行して
                      echo"</tr>";
                      // $menberCountをリセットし次の行へ
                      $menberCount = 0;
                      // 行数を増やす
                      $menberCountR += 1;
                    }
                    // 行数が8行なら
                    if($menberCountR == 8){
                      $menberCountR = 0;
                      $menberCount = 0;
                      echo"</table>";
                      echo"</div>";
                      $userBoxCount += 1;
                      
                    }else if($userCount == $userCheck){
                    // 全員分チェックしたのならば
                      echo"</table>";
                      echo"</div>";
                    }
                  }
              ?>
              <div id="paging">
              <?php
                // ページングボタン処理
                for($i=0; $i<=$userBoxCount; $i++){
                  echo'<a onclick="change(' . "{$i}" . ')">';
                  echo"{$i}";
                  echo"</a>";
                }
              ?>
              </div>
              
              
            </div>
            件名：<br>
            <input type="text" size=66 rows=1 name="thread_title" value="<?php ph($thread_title) ?>"><br>
            内容：<br>
            <textarea cols=67 rows=15 name="thread_inner"><?php ph($thread_inner) ?></textarea><br>
            <!-- SESSIONでログインしているユーザーのユーザーidを飛ばします -->
            <input type="hidden" name="user_id" value="abc">
            <!-- 今は仮でuser_idという名前でabc(静的悦子)を飛ばしてます -->
            <input type="submit" value="作成">
          </form>
        </div>
      
      <br><br>
      
      <br><br><br>
      
      
      
      </div>
      <?php require_once('../../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
    </div>
  </body>
</html>