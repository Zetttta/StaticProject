<!--
/**
 * 編集の場面
 *
 * 編集の機能が連結されているPOSTとSUBMITの入力欄が出力されている場面です。
 * 件名、表示時間、表示終了時間、編集ボタンが一列づつ出力されています。
 * また、IDと削除プラグは見えないようにできています。
 *
 *
 * システム名：編集場面
 * 作成者：朴
 * 作成日：2017/05/18
 * 最終更新日：2017/05/22
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
<title><?php ph("お知らせ修正画面")?></title>
<link href="../../../css/login_main.css" rel="Stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../css/main.css" type="text/css">
</head>
<body>
    <div id="wrapper">

      <?php require_once('../../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
      <div id="content">
  <form method="post">
    <div id="news_edit_form">
      <div id="news_edit_title">お知らせ編集</div>
        <input type="text" id="news_edit_name" name="news_edit_name" value="<?php ph($row["notice_title"]); ?>">
        <input type="text" id="news_edit_start" name="news_edit_start" value="<?php ph($row["notice_start"]); ?>">
        <input type="text" id="news_edit_end" name="news_edit_end" value="<?php ph($row["notice_end"]); ?>">
        <input type="hidden" id="news_edit_id" name="news_edit_id" value="<?php ph($row["notice_id"]); ?>">
        <input type="hidden" id="news_edit_delete" name="news_edit_delete" value="<?php ph($row["notice_delete"]); ?>">
        <input type="textarea" id="news_edit_text" name="news_edit_text" value="<?php ph($row["notice_content"]); ?>">
        <div id="news_edit_submit">
          <input type="submit" formaction="news_edit_exec.php" value="編集">
          <input type="submit" formaction="news_delete.php" value="削除" onclick="deleteNews();">
        </div>
    </div>
  </form>
<script type="text/javascript" src="../../../js/delete.js"></script>
      </div>
      <?php require_once('../../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
    </div>
</body>
</html>
