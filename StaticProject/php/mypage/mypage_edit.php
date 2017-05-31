<!--
 このファイルの概要説明
 マイページを編集する。
 このファイルの詳細説明
 名前、所属店舗、役職、売場、資格を表示し、コメントを編集する。
 システム名：マイページ編集
 作成者：        日置泰治
 作成日：        2017/05/19/13:00
 最終更新日：    2017/05/23/18:00
 レビュー担当者：
 レビュー日：
 バージョン：  0.1
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
     // 管理者か否かで読み込む、jsファイルを分岐する
     // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
     require_once('../../function/database_session.php');
     
     // ユーザー情報
     $Identify = getUser();
     // 管理者フラグ取得
     $judge = getManager($Identify);
     loginIdentify();
     if($judge==1){
       echo '<script type="text/javascript" src="../../js/headMenuManagement.js"></script>';
     }else{
       echo '<script type="text/javascript" src="../../js/headMenu.js"></script>';
     }
      require_once('../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい
      require_once('../../function/xss.php');
      ?>
      <?php if($Identify <> $_GET["user_id"]){
        header('location: ../toppage/toppage.php');
      }
      $dbh = connectDb();
      try {
          // SQLを構築
          $sql = "SELECT * FROM m_user";
          $sql .= " WHERE user_id = :Identify";//誰のマイページを開くのか。
          $sth = $dbh->prepare($sql); // SQLを準備
          $sth->bindValue(":Identify", $Identify);
          // SQLを発行
          $sth->execute();

      } catch (PDOException $e) {
          exit("SQL発行エラー：{$e->getMessage()}");
      }
      /*
      $dbh = connectDb();
      try {
            // プレースホルダ付きSQLを構築
              $sql = "UPDATE  m_user SET user_profile = :profile";
              $sql .=" WHERE user_id = :Identify";
              $sth = $dbh->prepare($sql);// SQLを準備
              // プレースホルダに値をバインド
              $sth->bindValue(":profile", $_POST["mypage_text"]);
              $sth->bindValue(":Identify", $Identify);
              // SQLを発行
              $sth->execute();
          } catch (PDOException $e) {
              exit("SQL発行エラー：{$e->getMessage()}");
          }
       */
       loginIdentify();
       ?>
      <title>マイページ編集</title>
   </head>

   <body>
     <div id="wrapper">
       <?php require_once('../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
       <div id="content">
         <br><br><br>
         <table>
           <?php
           // マイページ内容書き出し処理。名前込み
           $dbh = connectDb();
           try {
               // SQLを構築
               $sql = "SELECT * FROM m_user";
               $sql .= " WHERE user_id = :user";//誰のマイページを開くのか。
               $sth = $dbh->prepare($sql); // SQLを準備
               $sth->bindValue(":user", "{$_GET['user_id']}");
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
                 <th>名前</th>
                 <td><?php ph($username);?></td>
             </tr>
             <tr>
             <?php
               // 所属店舗書き出し
               $dbh = connectDb();
               try {
                   // SQLを構築
                   $sql = "SELECT * FROM m_store WHERE store_id = :storeid";//ユーザテーブルでとったストアID
                   $sth = $dbh->prepare($sql); // SQLを準備
                   $sth->bindValue(":storeid", "$storeid");
                   // SQLを発行
                   $sth->execute();

               } catch (PDOException $e) {
                   exit("SQL発行エラー：{$e->getMessage()}");
               }
             ?>
                 <?php while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
                 <th>所属</th>
                 <td><?php ph($row["store_name"]);?></td>
                 <?php } ?>
             </tr>
             <tr>
             <?php
               // 役職書き出し
               $dbh = connectDb();
               try {
                   // SQLを構築
                   $sql = "SELECT * FROM m_post WHERE post_id = :postid";//ユーザテーブルでとったポストID
                   $sth = $dbh->prepare($sql); // SQLを準備
                   $sth->bindValue(":postid", "$postid");
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
                   $sql = "SELECT * FROM m_corner WHERE corner_id = :cornerid";//ユーザテーブルでとったポストID
                   $sth = $dbh->prepare($sql); // SQLを準備
                   $sth->bindValue(":cornerid", "$cornerid");
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
             <form action="mypage_main.php?user_id=<?php echo $Identify;?>" method="post">
             <tr>
               <!--コメント書き出し-->
                 <th>コメント</th>
                 <td><textarea name="mypage_text" cols="50" rows="3"><?php ph($profile);?></textarea></td>
             </tr>
         </table>
             <input type="hidden" name="login_id" value="<?php ph($row["login_id"]);?>">
             <input type="submit">
             </form>
         <br><br><br>

       </div>
       <?php require_once('../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
     </div>
   </body>
 </html>
