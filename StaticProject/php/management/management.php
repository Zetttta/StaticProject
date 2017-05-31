
<!DOCTYPE html>
<html>
<!--
 * このファイルの概要説明：管理者用画面のHTMLで処理する部分の記述。
 * このファイルの詳細説明：このファイルでは、ログインしている管理者のみがアクセス
 *
 * システム名：管理者用画面
 * 作成者：坂田
 * 作成日：2017/5/18
 * 最終更新日：2017/5/18
 * レビュー担当者：伊藤、日置
 * レビュー日：2017/05/23/1530
 * バージョン：
-->
  <head>
    <!-- 文字コード宣言 -->
    <meta charset="utf-8">
    <!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../css/main.css" type="text/css">
    <!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../css/management.css" type="text/css">
    <!-- 管理者画面で使うCSSの指定先-->
    <script type="text/javascript" src="../../js/jquery-2-1-1-min.js"></script>

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
      // 管理者で無いならトップページへ
       Manager($Identify);
    ?>
    <title>index</title>
  </head>

    <body>
      <div id="wrapper">
        <?php require_once("../../header.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
          <div id="content">
            <div class="management_button"><a href="store/store_select.php">店舗選択画面へ</a></div><br>
            <div class="management_button"><a href="user/user_select.php" >ユーザ選択画面へ</a></div><br>
            <div class="management_button"><a href="news/news_select.php">お知らせ選択画面へ</a></div><br>
          </div>
        <?php require_once("../../footer.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
      </div>

            </div>
      <?php require_once('../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
    </div>
    </body>
</html>
