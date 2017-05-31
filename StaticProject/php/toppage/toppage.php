<!--
 このファイルの概要説明
トップページ
 このファイルの詳細説明

 システム名：    共通表示確認
 作成者：        内海洋樹
 作成日：        2017/05/19/14:00
 最終更新日：    2017/05/19/17:00
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
    require_once('../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい
    require_once('../../function/xss.php');
    require_once('../../function/database_session.php');

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
      // そうでないならトップページへ


    $dbh = connectDB();
    try {
        // SQLを構築
        $sql = "SELECT * FROM m_notice ";
        $sql .= " WHERE notice_delete = false and (curdate() between notice_start and notice_end) ";
        $sql .= " ORDER BY notice_start DESC";
        $sth = $dbh->prepare($sql); // SQLを準備

        // SQLを発行
        $sth->execute();

    } catch (PDOException $e) {
        exit("SQL発行エラー：{$e->getMessage()}");
    }
  ?>
    <title>トップページ</title>
  </head>

  <body>
    <div id="wrapper">

      <div id="content">
      <br><br><br>
        <table border="1">
            <tr>
                <th>新着お知らせ</th>
                <th>掲載日時</th>
            </tr>
            <?php
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
            <tr>
                <td><a href="news.php?notice_id=<?php ph($row["notice_id"]);?>"><?php ph($row["notice_content"]);?></a></td>
                <td><?php ph($row["notice_start"]);?></td>
            </tr>
            <?php } ;?>
        </table>
        <br><br><br>
        <table border="1">
            <tr>
                <th><a href="../thread/thread_main.php?page_id=1">スレッド一覧へ</a></th>
            </tr>
            <?php
            try {
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
     $sql .= " LIMIT 10 offset 0";
                $sth = $dbh->prepare($sql); // SQLを準備
$sth->bindValue(":user", "{$Identify}");
                // SQLを発行
                $sth->execute();

            } catch (PDOException $e) {
                exit("SQL発行エラー：{$e->getMessage()}");
            }
             while($row = $sth->fetch(PDO::FETCH_ASSOC)){
                ?>
            <tr>
                <td>
                <?php
                  echo'<a href="../thread/thread_Individual.php?thread_id=';
                  ph($row["thread_id_main"]);
                  echo'&page_id=1">';
                ?>
                <?php ph($row["thread_title"]);?></a></td>
            </tr>
            <?php

          }?>
        </table>
      </div>
      <br><br><br><br><br><br><br><br><br><br>
      <?php require_once('../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
    </div>
  </body>
</html>
