<!--
 このファイルの概要説明
 マイページを表示する。
 このファイルの詳細説明
 名前、所属店舗、役職、売場、資格、コメントを表示する。自分のマイページであれば編集ボタン、投稿履歴、GJ履歴を表示する。
 システム名：マイページ表示
 作成者：        日置泰治
 作成日：        2017/05/19/9:30
 最終更新日：    2017/05/23/19:00
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
    require_once('../../function/xss.php');//XSS対策
    ?>
    <!--そもそも存在するユーザか、あるいは消されたユーザどうか判断-->
    <?php if(empty($_GET["user_id"])){
          header('location: ../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
          exit();
           }
           // データベースに接続
           $dbh = connectDb();
           try {
               $sql = "SELECT user_delete FROM m_user";
               $sql .= " WHERE user_id = :search";
               // SQLを準備
               $sth = $dbh->prepare($sql);
               // $_GET["user_id"]がgetされていることを確認
               if(!empty($_GET["user_id"])){
                   // されているのであれば、searchにuser_idを格納
                   $sth->bindValue(":search", "{$_GET['user_id']}");
               }
               // SQLを発行
               $sth->execute();
           } catch (PDOException $e) {
               exit("SQL発行エラー：{$e->getMessage()}");
           }
           // 削除フラグを$judgeDeleteに格納
           while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
               $judgeDelete = $row["user_delete"];
           }
           // もしも該当ユーザが削除されているならばリダイレクト
           if($judgeDelete == TRUE){
               header('location: ../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
               exit();
           }?>

    <!--$_POST["mypage_text"]に何か入っていればUPDATE。入っていなければ自身のページへリダイレクト-->
    <?php if(!empty($_POST["mypage_text"])){
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
    } else {
      // header("location: mypage_main.php?user_id="."$Identify"); // 自分のページにリダイレクト
    }
    
    ?>
    <title>マイページ</title>
  </head>

  <body>
    <div id="wrapper">
      <?php require_once('../../header.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
      <div id="content">
        <br><br><br>
        <!--自分のマイページかどうか判断-->
        <?php
        if($Identify == $_GET['user_id']){?>
        <input type="button" onclick="location.href='mypage_edit.php?user_id=<?php echo $_GET['user_id']; ?>'"value="編集">
        <?php } ?>
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
                  $sql = "SELECT * FROM m_store WHERE store_id = $storeid";//ユーザテーブルでとったストアID
                  $sth = $dbh->prepare($sql); // SQLを準備

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
        <!--自分のマイページか判断-->
        <?php if($Identify == $_GET['user_id']){?>
        <table border="0"><tr><td valign="top">
        <table border="1">
            <tr>
                <th>スレッド投稿履歴</th>
            </tr>
            <?php
            $dbh = connectDb();
            try {
                $sql = "SELECT * FROM m_thread t1";
                $sql .= " LEFT OUTER JOIN m_response t2 ON t1.thread_id = t2.thread_id";
                $sql .= " LEFT OUTER JOIN m_user t3 ON t2.user_id = t3.user_id";
                $sql .= " WHERE t2.user_id = :user";
                // スレッドだけとりたいので、response_idが0のみ抽出
                $sql .= " AND response_id = 0";
                //削除されていないものを抽出
                $sql .= " AND thread_delete = false";
                $sql .= " AND t1.thread_id != 0";//スレッドID0は非表示
                $sql .= " ORDER BY t2.response_date ASC";//新着順
                $sql .= " LIMIT 10";//１０件抜き出し
                // SQLを準備
                $sth = $dbh->prepare($sql);
                $sth->bindValue(":user", "{$_GET['user_id']}");
                // SQLを発行
                $sth->execute();
            } catch (PDOException $e) {
                exit("SQL発行エラー：{$e->getMessage()}");
            }
             while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
            <tr>
                <td><?php ph($row["response_date"]); ?><a href="../thread/thread_Individual.php?thread_id=<?php ph($row["thread_id"]);?>&page_id=1">
                  <?php ph($row["thread_title"]); ?></a></td>
            </tr>
            <?php } ?>
          </table></td>
           <br>
          <td valign="top"><table border="1">
               <tr>
                   <th>GJ履歴</th>
               </tr>
               <?php
               $dbh = connectDB();
               try {
                   $sql = "SELECT t2.thread_id,t2.response_id,t2.user_id,t4.user_name,t2.response_inner,t1.thread_title FROM m_thread t1";
                   // スレッドをレスポンス、ｇｊ、user,と結合
                   $sql .= " LEFT OUTER JOIN m_response t2 ON t1.thread_id = t2.thread_id";
                   $sql .= " LEFT OUTER JOIN m_gj t3 ON (t2.thread_id = t3.thread_id AND t2.response_id = t3.response_id)";
                   $sql .= " LEFT OUTER JOIN m_user t4 ON t3.user_id = t4.user_id";
                   $sql .= " WHERE t3.user_id = :user";
                   // 削除されていないものを抽出
                   $sql .= " AND t1.thread_delete = false";
                   $sql .= " AND t2.response_delete = false";

                   $sql .= " AND t1.thread_id != 0";//スレッドID＝0は非表示

                   $sql .= " GROUP BY t3.gj_id";
                   $sql .= " ORDER BY t2.response_date ASC";//新着順
                   $sql .= " LIMIT 10";//10行だけ抜き出し
                   // SQLを準備
                   $sth = $dbh->prepare($sql);
                   $sth->bindValue(":user", "{$_GET['user_id']}");//後でSESSIONのを入れる
                   // SQLを発行
                   $sth->execute();
               } catch (PDOException $e) {
                   exit("SQL発行エラー：{$e->getMessage()}");
               }
                 while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {?>
                <tr>
                    <td>
                    
                    <?php
                    $id_check = $row["user_id"];
                   try {
                   $sql2 = "SELECT user_name FROM m_user";
                   $sql2 .= " WHERE user_id = :id";
                   // SQLを準備 
                   $sth2 = $dbh->prepare($sql2);
                   $sth2->bindValue(":id", "$id_check");	
                   // SQLを発行
                   $sth2->execute();
                   } catch (PDOException $e) {
                   exit("SQL発行エラー：{$e->getMessage()}");
                   }
                    ?>
                    
                    
                    <a href="../mypage/mypage_main.php?user_id=<?php ph($row["user_id"]);?>">
                    <?php
                      while ($row2 = $sth2->fetch(PDO::FETCH_ASSOC)) {
                      ph($row2["user_name"]);
                      }
                    ?>
                    </a>
                    
                    
                    <a href="../thread/thread_Individual.php?thread_id=<?php ph($row["thread_id"]);?>&page_id=1">
                        <?php
                        if($row["response_id"]==0){
                          ph(mb_substr($row["thread_title"], 0, 10) . "・・・");
                        }else{
                          ph(mb_substr($row["response_inner"], 0, 10) . "・・・");
                        }
                          echo"</a></td>";
                        ?>
                </tr>
                <?php } ?>
      </table></td></tr>
      </table>
      <?php } ?>
              <input type="hidden" name="login_id" value="<?php ph($row["login_id"]);?>">
      </div>
      <br><br><br>
      <?php require_once('../../footer.php'); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
    </div>
  </body>
</html>
