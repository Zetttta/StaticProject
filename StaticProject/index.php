<!--
 このファイルの概要説明

 このファイルの詳細説明

 システム名：    共通表示確認
 作成者：        伊藤尚輝
 作成日：        2017/05/18/14:00
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
    <link rel="stylesheet" href="css/main.css" type="text/css">
    <!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <script type="text/javascript" src="js/jquery-2-1-1-min.js"></script>
    
    <!-- headMenu(Management版も)の読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    
    <?php
      // 管理者か否かで読み込む、jsファイルを分岐する
      // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
      require_once('function/database_session.php');
      // ユーザー情報
      $Identify = getUser();
      // 管理者フラグ取得
      $judge = getManager($Identify);
      if($judge==1){
        echo '<script type="text/javascript" src="js/headMenuManagement.js"></script>';
      }else{
        echo '<script type="text/javascript" src="js/headMenu.js"></script>';
      }
      // ログインしてないならログインページへ
      loginIdentify();
      // そうでないならトップページへ
      header( "Location:/static_project/php/toppage/toppage.php" );
      exit;
    ?>
    <title>index</title>
  </head>

  <body>
    <div id="wrapper">

      <?php require_once('header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
      <div id="content">
        <h1>「ipアドレス/static_project」にアクセスしたときにここにきます</h1>
        <?php
         require_once('function/database_session.php');
         // ユーザーid取得
         $Identify=getUser();
         echo"{$Identify}";
         // 管理者フラグ取得
         $judge=getManager($Identify);
         echo"{$judge}";
         // 管理者権限が必要なページに記述
         // Manager($Identify);
         // ログインしてたら飛ばす処理
         // loginIdentify();
        ?>
      </div>
      <?php require_once('footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?> 
    </div>
  </body>
</html>