<!--
/**
 * お知らせ一覧を表示する。
 *
 * お知らせ一覧を表示する。
 * お知らせの件名
 *
 * システム名：ログインシステム
 * 作成者：朴
 * 作成日：2017/05/18
 * 最終更新日：2017/05/21
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
 */
-->
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
<script type="text/javascript" src="../../../js/jquery-2-1-1-min.js"></script>
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
<title><?php ph("お知らせ選択画面")?></title>
<link href="../../../css/login_main.css" rel="Stylesheet" type="text/css" />
<link href="../../../css/main.css" rel="Stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
        <?php require_once("../../../header.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
          <div id="content">
  <div id="news_table">
    <div id="table_head">
      <div id="news_list_title" colspan="2">お知らせ一覧</div>
      <div id="news_list_newst"><a href="news_insert_view.php">新規</a></div>
    </div>

    <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) { ?>
    <div class="table_row">
      <div class="news_title"><a href="news.php?notice_id=<?php ph($row["notice_id"]); ?>"><?php ph($row["notice_title"]); ?></a></div>
      <div class="news_start"><a><?php ph($row["notice_start"]); ?></a></div>
      <div class="news_edit"><a href="news_edit.php?notice_id=<?php ph($row["notice_id"]); ?>">編集</a></div>
    </div>
    <?php } ?>

  </div>

  <div id="news_numbering">
    <img src="">
    <div></div>
    <img src="">
  <div>
              </div>
              <br><br><br><br><br><br><br><br><br><br><br>
      <?php require_once('../../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
    </div>
</body>
</html>
