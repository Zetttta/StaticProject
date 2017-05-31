<!--
 このファイルの概要説明

 このファイルの詳細説明

 システム名：    共通ヘッダ
 作成者：        伊藤尚輝
 作成日：        2017/05/18/14:00
 最終更新日：    2017/05/18/17:00
 レビュー担当者：
 レビュー日：    
 バージョン：    
 
 
 ロゴタイプの代用画像作成はhttp://placehold.jp/を参考にしました
 
 
-->

<!-- ヘッダーを宣言 -->
<div id="header">
  <!-- 会社のロゴタイプ -->
  <div id="logotype">
    <!-- ロゴタイプがトップページへの遷移ボタン -->
    <a href="/static_project/php/toppage/toppage.php">
      <img src="/static_project/img/alegria.png" height="100px" width="300px">
    </a>
  </div>
  <!-- メニュー -->
  <div id="headMenu" class="menuRange">
    <!-- それぞれがそれぞれのページへの遷移ボタン -->
    
    <a href="/static_project/php/login/logout.php"><div id="headMenuLogout">ログアウト</div></a>
    <!-- php及びsqlを挟む、SESSIONのidentifyのユーザーIDが管理者IDである場合のみ書き出し処理を行う -->
    <?php
      // 管理者か否かで管理者用画面の表示を切り替える
      // 管理者のときのみ表示
      require_once('function/database_session.php');
      // ユーザー情報
      $Identify = getUser();
      // 管理者フラグ取得
      $judge = getManager($Identify);
      if($judge == 1){
        echo '<a href="/static_project/php/management/management.php"><div id="headMenuManagement">管理者用画面</div></a>';
      }
    ?>
    <?php 
      echo '<a href="/static_project/php/mypage/mypage_main.php?user_id=' . "{$Identify}" . '">';
    ?>
      <div id="headMenuMypage">マイページ</div></a>
    <div id="headMenuTitle">メニュー</div>
  </div>
</div>