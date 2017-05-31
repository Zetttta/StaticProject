<!--
/**
 * 個別のお知らせを表示。
 *
 * 個別のお知らせを表示します。
 * 一覧からクリックされた件名のIDで、
 * テーブルから本文の各名、表示期間、内容を読み込んで出力します。
 *
 *
 * システム名：各お知らせ場面
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
<title><?php ph($row["notice_title"]); ?></title>
<link href="../../../css/login_main.css" rel="stylesheet" type="text/css" />
<link href="../../../css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
        <?php require_once("../../../header.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
          <div id="content">
  <div id="news_wrap">
    <div id="news_title">
      <div id="news_name"><span><?php ph($row["notice_title"]); ?></span></div>
      <div id="news_start"><span><?php ph($row["notice_start"]); ?></span></div>
    </div>
    <div id = "news_text"><?php ph($row["notice_content"]); ?></div>
    <input type="button" id="to_news_select"value="一覧に戻る" onclick="self.location='news_select.php'">
  </div>


            </div>
            <br>
            <br><br><br><br><br><br><br><br><br>
      <?php require_once('../../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
    </div>
</body>
</html>
