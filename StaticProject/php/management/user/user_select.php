
<!DOCTYPE html>
<html>
<!--
 * このファイルの概要説明：管理者用画面から遷移するユーザー選択画面の記述
 * このファイルの詳細説明：編集したいユーザーを選べる画面を記述する。
 *
 * システム名：管理者用画面
 * 作成者：坂田、朴
 * 作成日：2017/5/19
 * 最終更新日：2017/5/19
 * レビュー担当者：伊藤、日置
 * レビュー日：2017/05/23/1530
 * バージョン：
-->
  <head>
    <!-- 文字コード宣言 -->
    <meta charset="utf-8">
    <!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../../css/main.css" type="text/css">
    <!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->

    <script type="text/javascript" src="../../../js/jquery-2-1-1-min.js"></script>

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
      // 管理者で無いならトップページへ
       Manager($Identify);

    ?>
    <title>index</title>
  </head>

    <body>
      <div id="wrapper">
        <div id="content">
        <?php require_once("../../../header.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>

        <?php
            //セッション
            require_once("../../../function/database_session.php");
            $dbh = connectDb();
            if(isset($_GET["page_id"])) {
              $page = $_GET["page_id"];
            } else {
              $page = 1;
            }
              try {
                  // m_userのuser_idを抽出
                  $sql = "SELECT COUNT(*) FROM m_user";
                  // 削除されていないスレッドのthread_idを抽出
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
                $connection = connectDB();
                //$user = getUser();
                //$manager = getManager();
                //$login = loginIdentify();
                //$manager_decision = Manager();
                try{
                  $sql  = "SELECT * FROM m_user WHERE user_delete=false";
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
        ?>

          <table border="1" >
                <tr><th>ユーザ一覧</th> <th><a href="user_edit_new.php" >新規</a></th></tr>
            <?php while ($row =$sth->fetch(PDO::FETCH_ASSOC)){ ?>
                <tr>
                <td><?php print $row["user_name"]; ?></td>
                <td><a href=<?php print "user_edit.php?id="."{$row["user_id"]}"; ?> >編集</a></td>
                </tr>
            <?php } ?>
         </table>
         <div>
           <?php
               // ページングボタン処理
               // 表示できるレスポンスの最大数/10の結果を切り上げて最大ページ数(ループ数)を取得
               $pageMax = ceil($userCount/10);
               // <ボタンの書き出し、ページidが1である場合はリンクが無効になる
               if($page == 1){
                 echo"<";
               }else{
                 $pageP = $page - 1;
                 echo'<a href="user_select.php?page_id=' . "{$pageP}" . '"><</a>';
               }
               // 数字ボタン処理
               // 1ボタンの書き出し、ページidが1である場合はリンクが無効になる
               if($page == 1){
                 echo"1";
               }else{
                 echo'<a href="user_select.php?&page_id=1">1</a>';
               }
               // ページ数が3以上の時
               if($pageMax >= 3){
                 $pageButtonNum = 2;               // 初期化、2~Max-1なので2から
                 while($pageButtonNum < $pageMax){ // Max未満の間処理
                   // 2~pageMax-1まで表示
                   echo'<a href="user_select.php?&page_id=' . "{$pageButtonNum}" . '">' . "{$pageButtonNum}" . '</a>';
                   $pageButtonNum += 1;
                 }

               }
               // 最大ページのボタンの書き出し、ページidが1である場合は処理をしない、ページidが最大のときはリンク無効
               if($page != 1){
                 if($page == $pageMax){
                   echo"{$pageMax}";
                 }else{
                   echo'<a href="user_select.php?page_id=' . "{$pageMax}" . '">' . "{$pageMax}" . '</a>';
                 }
               }
               // >ボタンの書き出し、ページidがpageMaxである場合はリンクが無効になる
               if($page == $pageMax){
                 echo">";
               }else{
                 $pageM = $page + 1;
                 echo'<a href="user_select.php?page_id=' . "{$pageMax}" . '">' . "{$pageMax}" . '</a>';
               }

           ?>
         </div>
      </div>

        <?php require_once("../../../footer.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
          </div>


    </body>
</html>
