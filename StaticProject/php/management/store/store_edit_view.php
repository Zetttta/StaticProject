<!--
/**
* 店舗編集表示場面
*
* 店舗編集表示場面です。
* 店舗の名前を押せば個別店舗の情報を表示されます。
* 編集ボタンを押して情報を編集することができます。
*
* システム名：店舗のリスト機能
* 作成者：朴
* 作成日：2017/05/23
* 最終更新日：2017/05/23
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/-->
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
<title><?php ph("店舗修正画面")?></title>
<link href="../../../css/store_main.css" rel="Stylesheet" type="text/css" />
<!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
<link rel="stylesheet" href="../../../css/main.css" type="text/css">

</head>
<body>
  <div id="wrapper">
    <?php require_once("../../../header.php") ?>
    <div id="content">
      <form method="post">
        <div id="store_edit_form">
            <div id="edit_title">店舗編集</div>
            <div class="edit_row">
              <div class="edit_title"><span>店舗名</span></div>
              <div class="edit_input"><input type="text" id="shop_edit_name" name="shop_edit_name" value="<?php ph($row["store_name"]); ?>"></div>
            </div>
            <div class="edit_row">
              <div class="edit_title"><span>住所</span></div>
              <div class="edit_input"><input type="text" id="shop_edit_add" name="shop_edit_add" value="<?php ph($row["store_address"]); ?>"></div>
            </div>
            <div class="edit_row">
              <div class="edit_title"><span>電話番号</span></div>
              <div class="edit_input"><input type="text" id="shop_edit_phone" name="shop_edit_phone" value="<?php ph($row["store_phone"]); ?>"></div>
            </div>
            <input type="hidden" id="store_edit_id" name="store_edit_id" value="<?php ph($row["store_id"]); ?>">
            <input type="hidden" id="store_edit_delete" name="store_edit_delete" value="<?php ph($row["store_delete"]); ?>">
            <div id="store_edit_submit">
              <input type="submit" formaction="store_edit_exec.php" value="編集">
              <input type="submit" formaction="store_delete.php" value="削除" onclick="deleteConfirm();">
            </div>
        </div>
      </form>
      <script type="text/javascript" src="../../../js/news.js"></script>
    </div>
    <?php require_once("../../../footer.php");?>
  </div>
</body>
</html>
