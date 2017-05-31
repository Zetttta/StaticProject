<!--
/**
* 店舗のリスト表示場面
*
* 店舗のリスト表示場面です。
* 店舗の名前を押せば個別店舗の情報を表示されます。
* 編集ボタンを押して情報を編集することができます。
*
* システム名：店舗のリスト場面
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
<title>店舗選択画面</title>
<link href="../../../css/store_main.css" rel="Stylesheet" type="text/css" />
<!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
<link rel="stylesheet" href="../../../css/main.css" type="text/css">

</head>
<body>
  <div id="wrapper">
    <div id="content">
      <?php require_once("../../../header.php") ?>
      <div id="store_table">
        <div id="store_table_head">
          <div id="store_list_title" colspan="2">店舗一覧</div>
          <div id="store_list_insert"><a href="store_insert_view.php">新規</a></div>
        </div>
        <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="table_row">
          <div class="store_name"><?php ph($row["store_name"]); ?></div>
          <div class="store_edit"><a href="store_edit.php?id=<?php ph($row["store_id"]); ?>">編集</a></div>
        </div>
        <?php } ?>
      </div>


        <div id="store_paging">
          <?php
          // ページングボタン処理
          // 表示できるレスポンスの最大数/10の結果を切り上げて最大ページ数(ループ数)を取得
          $pageMax = ceil($userCount/10);
          // <ボタンの書き出し、ページidが1である場合はリンクが無効になる
          if($page == 1){
            echo"<";
          }else{
            $pageP = $page - 1;
            echo'<a href="store_select.php?page_id=' . "{$pageP}" . '"><</a>';
          }
          // 数字ボタン処理
          // 1ボタンの書き出し、ページidが1である場合はリンクが無効になる
          if($page == 1){
            echo"1";
          }else{
            echo'<a href="store_select.php?&page_id=1">1</a>';
          }
          // ページ数が3以上の時
          if($pageMax >= 3){
            $pageButtonNum = 2;               // 初期化、2~Max-1なので2から
            while($pageButtonNum < $pageMax){ // Max未満の間処理
              // 2~pageMax-1まで表示
              echo'<a href="store_select.php?&page_id=' . "{$pageButtonNum}" . '">' . "{$pageButtonNum}" . '</a>';
              $pageButtonNum += 1;
            }

          }
          // 最大ページのボタンの書き出し、ページidが1である場合は処理をしない、ページidが最大のときはリンク無効
          if($page != 1){
            if($page == $pageMax){
              echo"{$pageMax}";
            }else{
              echo'<a href="store_select.php?page_id=' . "{$pageMax}" . '">' . "{$pageMax}" . '</a>';
            }
          }
          // >ボタンの書き出し、ページidがpageMaxである場合はリンクが無効になる
          if($page == $pageMax){
            echo">";
          }else{
            $pageM = $page + 1;
            echo'<a href="store_select.php?page_id=' . "{$pageMax}" . '">' . "{$pageMax}" . '</a>';
          }
          ?>

        </div>
      </div>
    </div>
  </div>
  <?php require_once("../../../footer.php");?>
</div>


</body>
</html>
