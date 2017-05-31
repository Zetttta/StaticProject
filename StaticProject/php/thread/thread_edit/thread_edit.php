<!--
 このファイルの概要説明

 このファイルの詳細説明

 システム名：    スレッド編集画面
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
  // 初期化処理
  $msg = "";
  $menberCount = 0;
  $menberCountR = 0;
  $userBoxCount = 0;
  $userCheck = 0;
  $fadeConfFlag = 0;
  $fadeConfFlag = 0;
    // ユーザー情報
  $Identify = getUser();
  // 管理者フラグ取得
  $judge = getManager($Identify);
  // GETで飛ばされたスレッドidの判断
  // 初期化処理
  $threadMax = 0;
  $judgeDelete = 0;
  
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
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  // 非公開かどうかの判定、ログイン処理が必要なので一時的
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
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  /////////////////////////////////////////////////////
  //スレッド作成者とアクセスしたユーザーが同じかの判断/

  // POSTで情報が飛ばされているかの判断
  if($_POST){
    // 両方にデータが入っている場合はスレッドを更新
    // そうで無い場合はこのページに戻る
    // どちらかが空の場合の処理
    if(empty ($_POST['thread_title'] && $_POST['thread_inner'])){
      // 件名
      if(empty ($_POST['thread_title'])){
        $dbh = connectDb();
        try {
          // m_threadのthread_titleを抽出
          $sql = "SELECT thread_title FROM m_thread";
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
            $thread_title = $row["thread_title"];
        }
      }else{
        $thread_title = $_POST['thread_title'];
      }
      // 内容
      if(empty ($_POST['thread_inner'])){
        // データベースに接続
        $dbh = connectDb();
        try {
          // m_responseのresponse_innerを抽出
          $sql = "SELECT response_inner FROM m_response";
          $sql .= " WHERE thread_id = :search AND response_id = 0;";
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
          $thread_inner = $row["response_inner"];
        }
      }else{
        $thread_inner = $_POST['thread_inner'];
      }
      $msg = "件名と内容を両方入力して下さい";
    }else{
      // 両方入力されている場合の処理
      // データベースに接続
      $dbh = connectDb();
      try {
        // スレッドを更新
        $sql = "UPDATE drag_db.m_thread";
        $sql .= " SET";
        // 件名の更新
        $sql .= " thread_title = :title";
        // スレッドを指定
        $sql .= " WHERE thread_id = :id;";
        // SQLを準備
        $sth = $dbh->prepare($sql);
        $sth->bindValue(":title", "{$_POST['thread_title']}");
        $sth->bindValue(":id", "{$_GET['thread_id']}");
        // SQLを発行
        $sth->execute();
      } catch (PDOException $e) {
        exit("SQL発行エラー：{$e->getMessage()}");
      }
    }
        // データベースに接続
        $dbh = connectDb();
        try {
            // レスポンス(スレッド本文)を作成
            $sql = "UPDATE drag_db.m_response";
            $sql .= " SET";
            // 本文
            $sql .= " response_inner = :inner";
            // スレッド本文なのでresponse_idは0固定SQLを準備
            $sql .= " WHERE thread_id = :id AND response_id = 0;";
            
            $sth = $dbh->prepare($sql);
            $sth->bindValue(":id", "{$_GET['thread_id']}");
            $sth->bindValue(":inner", "{$_POST['thread_inner']}");
            // SQLを発行
            $sth->execute();
        } catch (PDOException $e) {
            exit("SQL発行エラー：{$e->getMessage()}");
        }
        // 非公開フラグがTRUEであれば、非公開参加者メンバーを飛ばす処理
        // $fadeConfFlagが1なら処理
        if($fadeConfFlag == 1){
          // まずは該当スレッドの非公開設定を削除
          
            // データベースに接続
	        $dbh = connectDb();
	        try {
	            // m_entryの該当thread_idを抽出
	            $sql = "UPDATE drag_db.m_entry";
	            $sql .= " SET";
	            // 削除フラグの更新
	            $sql .= " entry_delete = 1";
	            // スレッドを指定
	            $sql .= " WHERE thread_id = :id;";

	            // SQLを準備
	            $sth = $dbh->prepare($sql);
	            $sth->bindValue(":id", "{$_GET['thread_id']}");
	            // SQLを発行
	            $sth->execute();
	        } catch (PDOException $e) {
	            exit("SQL発行エラー：{$e->getMessage()}");
	        }
          
          $private_member = $_POST["private_member"];
          // スレッド作成者も追加
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
                $sth->bindValue(":id", "{$_GET['thread_id']}");
                // SQLを発行
                $sth->execute();
            } catch (PDOException $e) {
                exit("SQL発行エラー：{$e->getMessage()}");
            }
          }
        }
        
        
        
        header('location: ../thread_Individual.php?thread_id=' . "{$_GET['thread_id']}" . "&page_id=1");// リダイレクト
        exit;
      
  }else{
    $dbh = connectDb();
    try {
      // m_threadのthread_titleを抽出
      $sql = "SELECT thread_title FROM m_thread";
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
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
      $thread_title = $row["thread_title"];
    }
    // データベースに接続
    $dbh = connectDb();
    try {
      // m_responseのresponse_innerを抽出
      $sql = "SELECT response_inner FROM m_response";
      $sql .= " WHERE thread_id = :search AND response_id = 0";
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
      $thread_inner = $row["response_inner"];
    }
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
    <title>スレッド編集</title>
  </head>

  <body>
    <div id="wrapper">
      <?php require_once('../../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
      <div id="content">
        <div id="createBox">
          <?php if($fadeConfFlag == 1){ ?>
          <div id="fadeConf">
          メンバー変更
          </div>
          <?php } ?>
          <span id="title">スレッド作成</span><br>
          <?php echo"{$msg}"; ?>
          <form action="" method="post" class="thread_create">
          <?php if($fadeConfFlag == 1){ ?>
          <div id="fadeConfBox">
            <!-- 非公開設定パネル -->
              設定パネル<br>
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
            <?php } ?>
            件名：<br>
            <input type="text" size=66 rows=1 name="thread_title" value="<?php ph($thread_title) ?>"><br>
            内容：<br>
            <textarea cols=67 rows=15 name="thread_inner"><?php ph($thread_inner) ?></textarea><br>
            <input type="submit" value="作成">
          </form>
          <script>
          function deleteCheck(){
              ret = confirm("削除します。\n本当に宜しいですか？");
              if (ret == true){
              location.href = "thread_delete.php?thread_id=" + <?php echo"{$_GET['thread_id']}"; ?>;
            }
          }
          </script>
          <input type="button" value="削除" onclick="deleteCheck()">
        </div>
      
      <br><br>
      
      <br><br><br>
      
      
      
      </div>
      <?php require_once('../../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
    </div>
  </body>
</html>