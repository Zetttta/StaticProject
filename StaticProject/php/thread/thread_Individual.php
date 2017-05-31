<!--
 このファイルの概要説明

 このファイルの詳細説明

 システム名：    個のスレッドページ
 作成者：        伊藤尚輝
 作成日：        2017/05/19/9:25
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
 thread_Individual.css  このページのスタイルシート
 date_str.php           datetimeをエンコードする関数
 resButton.js
 
 使用変数(sql関連以外)
 $threadMax               現在データベースに保管されている最大スレッド数
 $judgeDelete             現在データベースに保管されている最大スレッド数
 $responseCount           現在のthread_idの削除されていない1以上のresponse_idの合計数
 $pageCalcS               ページング処理計算用、開始
 $pageMax                 ページ数
 $pageP                   前のページ
 $pageN                   次のページ
 $pageButtonNum           今書き出すページングボタン
 $watcher                 見ていいのか判断
 -->

<?php
    require_once("../../function/xss.php");          // xss対策外部関数ファイルの読み込み
    require_once("../../function/database_session.php");          // データベース接続関連外部関数ファイルの読み込
    require_once("../../function/gj_function.php");          // GJ処理読み込み
    // 初期化処理
    $threadMax = 0;
    $judgeDelete = 0;
    $watcher = 0;
    // ユーザー情報
    $Identify = getUser();
    // 管理者フラグ取得
    $judge = getManager($Identify);
    // thread_idが空かどうか判断する処理、空ならリダイレクト
    if(empty($_GET["thread_id"])){
        header('location: ../toppage/toppage.php');// リダイレクト
        exit();
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
        header('location: ../toppage/toppage.php');// リダイレクト
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
        // 非公開の場合の処理
        // 非公開の場合、ログインしているユーザーが参加者に存在するかを確認する。
        if($row["thread_fade"] == TRUE){
            // ここに非公開処理
            // 非公開だったらm_entryを調べ、自分がいるならば処理をしない
            // 自分がいないならばリダイレクト
            try {
		        // m_threadのthread_idを抽出
		        $sql = "SELECT * FROM m_entry";
		        $sql .= " WHERE thread_id = :search AND entry_delete = 0";
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
		      if($row["user_id"]==$Identify){
		        $watcher = 1;
		      }
		    }
		    // 自分が参加ユーザーにいないならリダイレクト
		    if($watcher != 1){
		      // 管理者なら関係ない
		      if($judge==0){
		        header('location: ../toppage/toppage.php');// リダイレクト
		      }
		    }
        }
    }
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    
    // page_idが空かどうか判断する処理、空ならリダイレクト
    if(empty($_GET["page_id"])){
        header('location: ../../index.php'); // リダイレクト処理、トップページのurlにあとで変更する
        exit();
    }
    // レスポンスidの削除されていない数を数える
        $dbh = connectDb();
        try {
            // m_responseのresponse_idを抽出
            $sql = "SELECT COUNT(*) FROM m_response";
            // 開いているページのthread_idと同じものを抽出
            $sql .= " WHERE thread_id = :search";
            // 削除されていないものを抽出
            $sql .= " AND response_delete = FALSE";
            // レスなので1以上のものを抽出
            $sql .= " AND response_id > 0";
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
            $responseCount = $row["COUNT(*)"];
        }

?>

<!DOCTYPE html>
<html>
  <head>

    <!-- 文字コード宣言 -->
    <meta charset="utf-8">
    <!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../css/main.css" type="text/css">
    <link rel="stylesheet" href="../../css/thread_Individual.css" type="text/css">
    <!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <script type="text/javascript" src="../../js/jquery-2-1-1-min.js"></script>
    <script type="text/javascript" src="../../js/resButton.js"></script>
    <!-- headMenu(Management版も)の読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    
    <?php
      // 管理者か否かで読み込む、jsファイルを分岐する
      // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
      require_once('../../function/database_session.php');
      // ユーザー情報
      $Identify = getUser();
      // 管理者フラグ取得
      $judge = getManager($Identify);
      if($judge==1){
        echo '<script type="text/javascript" src="../../js/headMenuManagement.js"></script>';
      }else{
        echo '<script type="text/javascript" src="../../js/headMenu.js"></script>';
      }
      loginIdentify();
    ?>
    <title>スレッド</title>
  </head>

  <body>

    <div id="wrapper">
      <?php require_once('../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
      <div id="content">

      <?php
        // とりあえず1ファイルで編集したいので埋め込み、あとで分離します。
        // DBからスレッドタイトルを読み込む
        // DBから投稿者のユーザーIDと削除フラグを読み込む、削除されているなら投稿者を非表示をつけない
        // 投稿者のユーザーIDと自分のユーザーIDが合致した場合は編集を表示
        // レスポンス0番の内容を読み込んで本文に表示
        // GJマスタからスレ-レスでselectし、数を数えてGJ
        // GJマスタのselectした範囲のユーザーidに自分のユーザーidが無いならgjボタンを押せるようにする
      ?>
      <table>
        <?php
        // スレッド本文書き出し処理
        $dbh = connectDb();
        try {
            // m_responseのresponse_idを抽出
            $sql = "SELECT * FROM m_thread t1 LEFT OUTER JOIN m_response t2 ON t1.thread_id = t2.thread_id";
            $sql .= " LEFT OUTER JOIN m_user t3 ON t2.user_id = t3.user_id";
            // 開いているページのthread_idと同じものを抽出
            $sql .= " WHERE t1.thread_id = :search";
            // スレッドだけとりたいので、response_idが0のみ抽出
            $sql .= " AND response_id = 0";
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
        ?>
        <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
          <td class="title"><?php ph("件名：" . $row["thread_title"]); // 件名 ?></td>
          <td>
            <?php echo '<a href="../mypage/mypage_main.php?user_id=' . "{$row['user_id']}" ?>
            <?php echo '">'; ?>
            <?php ph($row["user_name"]); //投稿者名前 ?>
            <?php echo '</a>';?>
          </td>
          <td>
            <?php
            if(($row['user_id'] == $Identify)||($judge==1)){
              echo'<a href="thread_edit/thread_edit.php?thread_id=' . "{$_GET['thread_id']}" . '">';
              ph("編集");
            }
            ?>
            </a>
          </td>
          <td><?php
           ph($row["response_date"]); // 掲載日時 
           ?></td>
        </tr>
        <tr><td colspan="4"><?php ph($row["response_inner"]); // 本文 ?></td></tr>
        <tr><td colspan="3"></td>
        <td class="gj">
        <?php
          $judgeGj=checkGJ($_GET['thread_id'],$row["response_id"]);
          if($judgeGj==TRUE){
            echo"GJ!!!!!";
          // してたら
          }else if($judgeGj==FALSE){
          // してなかったら
            echo'<a href="gj_process.php?thread_id=' . "{$_GET['thread_id']}" . '&response_id='. "{$row['response_id']}" . '">';
            echo"GJする";
            echo"</a>";
          }
        ?>
        <?php
          $countGj=countGJ($_GET['thread_id'],$row["response_id"]);
          echo"{$countGj}件";
        ?>
        </td></tr>
        <?php } ?>
        
        <?php
          // この下はレスポンス関連
          // レスポンスマスタidの1以上の内容をセレクト
          // カウントで数え、10件ずつページングする
          // レス番号にid、編集投稿者掲載日時本文GJ処理は同じ
          // 
          $dbh = connectDb();
            try {
                // m_responseのresponse_idを抽出
                $sql = "SELECT * FROM m_response t1";
                // ユーザーと結合
                $sql .= " LEFT OUTER JOIN m_user t2 ON t1.user_id = t2.user_id";
                // 開いているページのthread_idと同じものを抽出
                $sql .= " WHERE thread_id = :search";
                // 削除されていないものを抽出
                $sql .= " AND response_delete = FALSE";
                // レスなので1以上のものを抽出
                $sql .= " AND response_id > 0";
                // ページング処理
                // スレッドidが11以上の場合のみ処理する
                if($responseCount >= 11){
                  
                  // 開始レスNo：ページid*10+1-10
                  // 終了：ページid*10+10-10
                  // の間だけ(10件)セレクトする。
                  // 既に0は除外されているので+1の処理は不要
                  $pageCalcS = $_GET["page_id"]*10-10;
                  // 開始位置から10件表示する
                  $sql .= " LIMIT 10 offset $pageCalcS";
                }
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
        ?>
        
        
        
        <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) { ?>
          <tr><td class="title"><?php ph($row["response_id"]); // レス番 ?></td>
          <td>
            <?php if($row["user_delete"] == 0){ ?>
            <?php echo '<a href="../mypage/mypage_main.php?user_id=' . "{$row['user_id']}" ?>
            <?php echo '">'; ?>
            <?php ph($row["user_name"]); //投稿者名前 ?>
            <?php echo '</a>';?>
            <?php }else{ ?>
            存在しないユーザー
            <?php }?>
          </td>
          <td>
            <?php
            if(($row['user_id'] == $Identify)||($judge==1)){
              echo'<a href="res_edit/res_edit.php?thread_id=' . "{$_GET['thread_id']}" . "&response_id=" . "{$row['response_id']}" . '">';
              ph("編集");
            }
            ?>
            </a>
          </td>
          <td><?php ph($row["response_date"]); // 掲載日時 ?></td></tr>
          <tr><td colspan="4"><?php ph($row["response_inner"]); // 本文 ?></td></tr>
          <tr><td colspan="3"></td>
          <td class="gj">
          <?php
          $judgeGj=checkGJ($_GET['thread_id'],$row["response_id"]);
          if($judgeGj==TRUE){
            echo"GJ!!!!!";
          // してたら
          }else if($judgeGj==FALSE){
          // してなかったら
            echo'<a href="gj_process.php?thread_id=' . "{$_GET['thread_id']}" . '&response_id='. "{$row['response_id']}" . '">';
            echo"GJする";
            echo"</a>";
          }
        ?>
          <?php
            $countGj=countGJ($_GET['thread_id'],$row["response_id"]);
            echo"{$countGj}件";
          ?>
          </td></tr>
        <?php } ?>
        
        <!-- レスポンス作成画面への遷移、thread_idをgetで送信する、
             非公開や存在の判断は遷移先のレスポンス作成画面で行う。 -->
        
        <tr>
        <td align="left">
        <?php
          echo'<div id ="resButton" onclick="response(' . "{$_GET['thread_id']}" . ')">';
          echo"レス";
          echo"</div>";
        ?>
        </td><td colspan="3"></td>
        </tr>
      </table>
      
      <br><br>
      <?php
          // ページングボタン処理
          // 表示できるレスポンスの最大数/10の結果を切り上げて最大ページ数(ループ数)を取得
          $pageMax = ceil($responseCount/10);
          // <ボタンの書き出し、ページidが1である場合はリンクが無効になる
          if($_GET["page_id"] == 1){
            echo"<";
          }else{
            $pageP = $_GET['page_id'] - 1;
            echo'<a href="thread_Individual.php?thread_id=' . "{$_GET['thread_id']}" . '&page_id=' . "{$pageP}" . '"><</a>';
          }
          // 数字ボタン処理
          // 1ボタンの書き出し、ページidが1である場合はリンクが無効になる
          if($_GET["page_id"] == 1){
            echo"1";
          }else{
            echo'<a href="thread_Individual.php?thread_id=' . "{$_GET['thread_id']}" . '&page_id=1">1</a>';
          }
          // ページ数が3以上の時
          if($pageMax >= 3){
            $pageButtonNum = 2;               // 初期化、2~Max-1なので2から
            while($pageButtonNum < $pageMax){ // Max未満の間処理
              // 2~pageMax-1まで表示
              echo'<a href="thread_Individual.php?thread_id=' . "{$_GET['thread_id']}" . '&page_id=' . "{$pageButtonNum}" . '">' . "{$pageButtonNum}" . '</a>';
              $pageButtonNum += 1;
            }
            
          }
          // 最大ページのボタンの書き出し、ページidが1である場合は処理をしない、ページidが最大のときはリンク無効
          if($_GET["page_id"] != 1){
            if($_GET["page_id"] == $pageMax){
              echo"{$pageMax}";
            }else{
              echo'<a href="thread_Individual.php?thread_id=' . "{$_GET['thread_id']}" . '&page_id=' . "{$pageMax}" . '">' . "{$pageMax}" . '</a>';
            }
          }
          // >ボタンの書き出し、ページidがpageMaxである場合はリンクが無効になる
          if(($_GET["page_id"] == $pageMax)||($responseCount == 0)){
            echo">";
          }else{
            $pageM = $_GET['page_id'] + 1;
            echo'<a href="thread_Individual.php?thread_id=' . "{$_GET['thread_id']}" . '&page_id=' . "{$pageM}" . '">></a>';
          }
          
      ?>
      <br><br><br>
      
      
      
      </div>
      <?php require_once('../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
    </div>
  </body>
</html>