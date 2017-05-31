
<!DOCTYPE html>
<html>
<!--
 * このファイルの概要説明：管理者がユーザー編集を行う
 * このファイルの詳細説明：ユーザー選択画面から遷移し、当画面にて編集を行う。
 *
 * システム名：管理者ユーザー編集画面
 * 作成者：坂田恵未
 * 作成日：2017/5/22
 * 最終更新日：2017/5/22
 * レビュー担当者：
 * レビュー日：
 * バージョン：1.0
-->
  <head>
    <!-- 文字コード宣言 -->
    <meta charset="utf-8">
    <!-- スタイルシートの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->
    <link rel="stylesheet" href="../../../css/main.css" type="text/css">
    <!-- jQueryの読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->

    <script type="text/javascript" src="../../../js/jquery-2-1-1-min.js"></script>

    <!-- headMenu(Management版も)の読み込み 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい-->

    <?php
      // 管理者か否かで読み込む、jsファイルを分岐する
      echo '<script type="text/javascript" src="../../../js/headMenuManagement.js"></script>';
      // echo '<script type="text/javascript" src="js/headMenu.js"></script>';
    ?>
    <title>ユーザー情報編集画面</title>
  </head>

    <body>
      <div id="wrapper">
        <div id="content">
        <?php require_once("../../../header.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>

        <?php
            //セッション
            require_once("../../../function/database_session.php");
            require_once("../../../function/xss.php");
                $connection = connectDB();
                $user = getUser();
                $manager = getManager();
                $login = loginIdentify();
                $manager_decision = Manager($user);
                try{
                  $sql  = "USE drag_db";
                /*  $sql  = "SELECT
                          t1.store_id,
                          t1.corner_id,
                          t1.post_id,
                          t1.user_name,
                          t1.user_right,
                          t1.user_profile,
                          t1.user_id,
                          t1.user_email,
                          t1.user_pw,
                          t1.user_delete,
                          t1.user_manager,
                          t2.store_id,
                          t2.store_name,
                          t3.corner_id,
                          t3.corner_name,
                          t4.post_id,
                          t4.post_name
                          FROM ";
                  $sql .= "(m_user t1 LEFT OUTER JOIN m_store t2 ON t1.store_id=t2.store_id ";
                  $sql .= "LEFT OUTER JOIN m_corner t3 ON t1.corner_id=t3.corner_id) ";
                  $sql .= "LEFT OUTER JOIN m_post t4  ON t1.post_id=t4.post_id ";
                  $sql .= "WHERE user_id = :id ";
*/
                  $sql ="SELECT * FROM m_user WHERE user_id=:id";

                  $sth  = $connection->prepare($sql);
                  $sth->bindValue(":id", $_GET["id"]);
                  $sth->execute();

                 //所属店舗のデータを受け取る
                  $sql2 ="SELECT * FROM m_store";
                  $sth2  = $connection->prepare($sql2);
                  // $sth2->bindValue(":id", $_GET["id"]);
                  $sth2->execute();

                  // $store = $sth2->fetch(PDO::FETCH_ASSOC);

                  //役職のデータを受け取る
                  $sql3 ="SELECT * FROM m_post";
                  $sth3  = $connection->prepare($sql3);
                  $sth3->bindValue(":id", $_GET["id"]);
                  $sth3->execute();

              //   $post = $sth3->fetch(PDO::FETCH_ASSOC);

                  //売り場のデータを受け取る
                  $sql4 ="SELECT * FROM m_corner";
                  $sth4  = $connection->prepare($sql4);
                  $sth4->bindValue(":id", $_GET["id"]);
                  $sth4->execute();

              //   $corner = $sth4->fetch(PDO::FETCH_ASSOC);

                }catch (PDOException $e){
                  exit("SQL発行エラー:{$e->getMessage()}");
                }


        ?>
        <!--ユーザー情報を飛ばすID-->
        <form method="post">
          <?PHP while ($row = $sth->fetch(PDO::FETCH_ASSOC)){?>

          <?php //echo "{$row['user_manager']}" ?>
          <table border="1">
            <tr><td>名前：              <input type="text" name="user_edit_name" value="<?php print "{$row["user_name"]}"; ?>"></td></tr>
            <tr><td>資格：              <input type="text" name="user_edit_quali" value="<?php print "{$row["user_right"]}"; ?>"></td></tr>
            <tr><td>一言：              <input type="text" name="user_edit_text" value="<?php print "{$row["user_profile"]}"; ?>"></td></tr>
            <tr><td>ID：                <input type="text" name="user_edit_id" value="<?php print "{$row["user_id"]}"; ?>"></td></tr>
            <tr><td>メールアドレス：     <input type="text" name="user_edit_mail" value="<?php print "{$row["user_email"]}"; ?>"></td></tr>
            <tr><td>パスワード：         <input type="text" name="user_edit_pass" value="<?php print "{$row["user_pw"]}";?>"></td></tr>
            <tr><td>管理者資格： <select name="user_edit_manager">
                                <option value="0">非管理者</option>
                                <option value="1" <?php if($row["user_manager"]==1){
                                                          echo "selected";
                                }?> >管理者</option>
                                </select></td></tr>


            <tr><td>所属店舗：<select name="user_edit_store">
                            <?php while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)) { ?>
                              <option value="<?php ph($row2["store_id"]);?>"
                              <?php
                              if($row2["store_id"] == $row["store_id"]){
                                  echo 'selected="selected"';
                              }
                              ?> ><?php ph($row2["store_name"]);?></option>
                            <?php } ?>
                             </select>
                           </td></tr>

            <tr><td>役職：   <select name="user_edit_position">
                              <?php while($row3 = $sth3->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php ph($row3["post_id"]);?>"
                                <?php if($row3["post_id"] == $row["post_id"]){
                                  echo "selected";
                                }
                                ?> ><?php ph($row3["post_name"]);?></option>
                              <?php } ?>
                            </select></td></tr>

            <tr><td>売り場：  <select name="user_edit_department">
                              <?php while($row4 = $sth4->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php ph($row4["corner_id"]);?>"
                                <?php if($row4["corner_id"] == $row["corner_id"]){
                                  echo "selected";
                                }
                                ?> ><?php ph($row4["corner_name"]);?></option>
                              <?php } ?>
                            </select></td></tr>



            <input type="hidden" name="user_id_delete" value="<?php ph($row["user_delete"]); ?>">
            <script src="../../../js/user_edit_alert.js"></script>
            <tr><td><input type="submit" formaction="user_edit_update.php" value="編集">
                    <?php echo '<input type="button"  value="当該ユーザの削除" onclick="confirmUseredit('."'{$_GET['id']}'".')">'; ?></td></tr>
          </table>
        <?php } ?>
        </form>
        </div>





        <?php require_once("../../../footer.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
          </div>


    </body>
</html>
