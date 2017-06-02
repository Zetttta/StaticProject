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
<?php
  // 管理者か否かで管理者用画面の表示を切り替える
  // 管理者のときのみ表示

  ?>
<!-- ヘッダーを宣言 -->
<div id="header">
  <!-- 会社のロゴタイプ -->
  <div id="logotype">
    <!-- ロゴタイプがトップページへの遷移ボタン -->
    <a href="/static_project/php/toppage/toppage.php">
      <img src="/static_project/img/alegria.png" height="100px" width="300px">
    </a>
    <div id="user_info">
      <table>
      <?php
      // マイページ内容書き出し処理。名前込み
      $dbh = connectDb();
      try {
          // SQLを構築
          $sql = "SELECT * FROM m_user";
          $sql .= " WHERE user_id = :user";//誰のマイページを開くのか。
          $sth = $dbh->prepare($sql); // SQLを準備
          $sth->bindValue(":user", "{$_SESSION["identify"]}");
          // SQLを発行
          $sth->execute();

      } catch (PDOException $e) {
          exit("SQL発行エラー：{$e->getMessage()}");
      }
    ?>
        <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
          $username = $row["user_name"];
          $userid = $row["user_id"];
          $storeid = $row["store_id"];
          $postid = $row["post_id"];
          $cornerid = $row["corner_id"];
          $userright = $row["user_right"];
          $profile = $row["user_profile"];

        }?>
        <tr>
            <td colspan="2" id="profile_name"><?php ph($username);?></td>
        </tr>
        <tr>
        <?php
          // 所属店舗書き出し
          $dbh = connectDb();
          try {
              // SQLを構築
              $sql = "SELECT * FROM m_store WHERE store_id = $storeid";//ユーザテーブルでとったストアID
              $sth = $dbh->prepare($sql); // SQLを準備

              // SQLを発行
              $sth->execute();

          } catch (PDOException $e) {
              exit("SQL発行エラー：{$e->getMessage()}");
          }
        ?>
            <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
            <td colspan="2" id="profile_store"><img src=""><?php ph($row["store_name"]);?></td>
            <?php } ?>
        </tr>
        <tr>
        <?php
          // 役職書き出し
          $dbh = connectDb();
          try {
              // SQLを構築
              $sql = "SELECT * FROM m_post WHERE post_id = $postid";//ユーザテーブルでとったポストID
              $sth = $dbh->prepare($sql); // SQLを準備

              // SQLを発行
              $sth->execute();

          } catch (PDOException $e) {
              exit("SQL発行エラー：{$e->getMessage()}");
          }
        ?>
            <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
            <th>役職</th>
            <td><?php ph($row["post_name"]);?></td>
            <?php } ?>
        </tr>
        <tr>
        <?php
          // 売場書き出し
          $dbh = connectDb();
          try {
              // SQLを構築
              $sql = "SELECT * FROM m_corner WHERE corner_id = $cornerid";//ユーザテーブルでとったポストID
              $sth = $dbh->prepare($sql); // SQLを準備

              // SQLを発行
              $sth->execute();

          } catch (PDOException $e) {
              exit("SQL発行エラー：{$e->getMessage()}");
          }
        ?>
            <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
            <th>売場</th>
            <td><?php ph($row["corner_name"]);?></td>
            <?php } ?>
        </tr>
        <tr>
          <!--資格書き出し-->
            <th>資格</th>
            <td><?php ph($userright);?></td>
        </tr>
        <tr>
          <!--コメント書き出し-->
            <th>コメント</th>
            <td><?php if(isset($profile)) {
                         ph($profile);
                      } else { ?>
                        無し
               <?php } ?></td>
        </tr>
    </table>
    </div>
  </div>
  <!-- メニュー -->
  <div id="headMenu" class="menuRange">
    <!-- それぞれがそれぞれのページへの遷移ボタン -->

    <a href="/static_project/php/login/logout.php"><div id="headMenuLogout">ログアウト</div></a>
    <!-- php及びsqlを挟む、SESSIONのidentifyのユーザーIDが管理者IDである場合のみ書き出し処理を行う -->
    <?php
      // ユーザー情報
      $Identify = getUser();
      // 管理者フラグ取得
      $judge = getManager($Identify);
      if($judge == 1){
        echo '<a href="/static_project/php/management/management.php?"><div id="headMenuManagement">管理者用画面</div></a>';
      }
    ?>
    <?php
      echo '<a href="/static_project/php/mypage/mypage_main.php?user_id=' . "{$Identify}" . '">';
    ?>
      <div id="headMenuMypage">マイページ</div></a>
    <div id="headMenuTitle">メニュー</div>
  </div>
</div>
