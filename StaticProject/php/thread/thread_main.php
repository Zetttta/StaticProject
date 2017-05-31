<!--
 このファイルの概要説明
スレッド一覧表示
 このファイルの詳細説明

 システム名：    共通表示確認
 作成者：        内海洋樹
 作成日：        2017/05/19/14:00
 最終更新日：    2017/05/18/17:00
 レビュー担当者：
 レビュー日：
 バージョン：
-->

<!--
 使用ファイル
 css/main.css           共通スタイルシート
 header.php             ヘッダー表示用
 footer.php             フッター表示用
 headMenu.js            右上メニュー処理
 jquery-2-1-1-min.js    jQuery
 -->
<!DOCTYPE html>
<html>
  <head>
    <!-- 文字コード宣言 -->
    <meta charset="utf-8">
    <!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../css/main.css" type="text/css">
    <!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <script  src="../../js/jquery-2-1-1-min.js" type="text/javascript"></script>

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
      // ログインしてないならログインページへ
      loginIdentify();

    require_once('../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい
    require_once('../../function/xss.php');
    require_once('../../function/database_session.php');
    // 初期化処理
    $threadMax = 0;
    $judgeDelete = 0;
    $watcher = 0;
    $thread_title = 0;
    $thread_id = 0;
    $response_date = 0;
    //ユーザー情報
    $Identify = getUser();
    // 管理者フラグ取得
    // $judge = getManager($Identify);
    $dbh = connectDb();
try {
    // m_threadのthread_idを抽出
    $sql = "SELECT COUNT(*) FROM m_thread";
    // 削除されていないスレッドのthread_idを抽出
    $sql .= " WHERE thread_delete = FALSE";
    $sql .= " AND thread_id > 0";
    // SQLを準備
    $sth = $dbh->prepare($sql);
    // SQLを発行
    $sth->execute();
} catch (PDOException $e) {
    exit("SQL発行エラー：{$e->getMessage()}");
}
while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $threadCount = $row["COUNT(*)"];
}

try {
    // m_responseのresponse_idを抽出
    $sql = "SELECT *,t1.thread_id as thread_id_main FROM m_thread t1";
    // レスポンス、ｇｊ、user,と結合
    $sql .= " LEFT OUTER JOIN m_response t2 ON t1.thread_id = t2.thread_id";
    //レスポンスマスタと結合
    $sql .= " LEFT OUTER JOIN m_gj t3 ON t2.thread_id = t3.thread_id AND t2.response_id = t3.response_id";
    //GJマスタと結合
    $sql .= " LEFT OUTER JOIN m_user t4 ON t3.user_id = t4.user_id";
    //
    $sql .= " LEFT OUTER JOIN m_entry t5 ON t1.thread_id = t5.thread_id";
    //ユーザマスタと結
    $sql .= " WHERE t5.user_id = :user OR t1.thread_fade = 0  OR $judge = 1";
    $sql .= " GROUP BY t1.thread_id";
    //スレッドID
    $sql .= " HAVING t1.thread_delete = false";
    $sql .= " AND t1.thread_id > 0";
    $sql .= " ORDER BY t2.response_date, t1.thread_id DESC";

    // 削除されていないものを抽出
    if($threadCount >= 11){

      // 開始レスNo：ページid*10+1-10
      // 終了：ページid*10+10-10
      // の間だけ(10件)セレクトする。
      // 既に0は除外されているので+1の処理は不要
      $pageCalcS = $_GET["page_id"]*10-10;
      // 開始位置から10件表示する
      $sql .= " LIMIT 10 offset $pageCalcS";
    }
       var_dump($sql);
    // SQLを準備
    $sth = $dbh->prepare($sql);

      $sth->bindValue(":user", "{$Identify}");
    // SQLを発行
    $sth->execute();
} catch (PDOException $e) {
    exit("SQL発行エラー：{$e->getMessage()}");
}

  ?>
    <title>スレッド一覧</title>
  </head>
  <body>
    <div id="wrapper">
      <div id="content">
        <br><br><br>
        <input type="button" onclick="location.href='thread_edit/thread_create.php'"value="新規作成">
        <table border="1">
            <tr>
                <th>スレッド一覧</th>
            </tr>
            <?php
              while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
             <tr>
               <?php

               $thread_title = $row["thread_title"];
               $thread_id = $row['thread_id_main'];
               $response_date = $row["response_date"];
?>

            <td><?php echo'<a href="thread_Individual.php?thread_id=' . "{$thread_id}" . '&page_id=1">'?>
            <?php ph("$thread_title");?></a></td>
            <th><?php ph("$response_date");?></th>
        </tr><?php
            }
            ?>

        </table>
        <?php




            // ページングボタン処理
            // 表示できるレスポンスの最大数/10の結果を切り上げて最大ページ数(ループ数)を取得
            $pageMax = ceil($threadCount/10);
            // <ボタンの書き出し、ページidが1である場合はリンクが無効になる
            if($_GET["page_id"] == 1){
              echo"<";
            }else{
              $pageP = $_GET['page_id'] - 1;
              echo'<a href="thread_main.php?page_id=' . "{$pageP}" . '"><</a>';
            }
            // 数字ボタン処理
            // 1ボタンの書き出し、ページidが1である場合はリンクが無効になる
            if($_GET["page_id"] == 1){
              echo"1";
            }else{
              echo'<a href="thread_main.php?&page_id=1">1</a>';
            }
            // ページ数が3以上の時
            if($pageMax >= 3){
              $pageButtonNum = 2;               // 初期化、2~Max-1なので2から
              while($pageButtonNum < $pageMax){ // Max未満の間処理
                // 2~pageMax-1まで表示
                echo'<a href="thread_main.php?&page_id=' . "{$pageButtonNum}" . '">' . "{$pageButtonNum}" . '</a>';
                $pageButtonNum += 1;
              }

            }
            // 最大ページのボタンの書き出し、ページidが1である場合は処理をしない、ページidが最大のときはリンク無効
            if($_GET["page_id"] != 1){
              if($_GET["page_id"] == $pageMax){
                echo"{$pageMax}";
              }else{
                echo'<a href="thread_main.php?page_id=' . "{$pageMax}" . '">' . "{$pageMax}" . '</a>';
              }
            }
            // >ボタンの書き出し、ページidがpageMaxである場合はリンクが無効になる
            if($_GET["page_id"] == $pageMax){
              echo">";
            }else{
              $pageM = $_GET['page_id'] + 1;
              echo'<a href="thread_main.php?page_id=' . "{$pageMax}" . '">' . "{$pageMax}" . '</a>';
            }
        ?>

        <br><br><br><br><br><br><br><br>
      </div>
      <?php require_once('../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
    </div>
  </body>
</html>
