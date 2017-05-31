
<!DOCTYPE html>
<html>
<!--
 * このファイルの概要説明：管理者用画面から遷移するユーザー選択画面の記述
 * このファイルの詳細説明：編集したいユーザーを選べる画面を記述する。
 *
 * システム名：管理者用画面
 * 作成者：坂田
 * 作成日：2017/5/19
 * 最終更新日：2017/5/19
 * レビュー担当者：
 * レビュー日：
 * バージョン：
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
    <title>新規ユーザー追加</title>
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
                loginIdentify();
                $manager_decision = Manager($user);
                try{

                   $sql = "USE drag_db";

                  //所属店舗のデータを受け取る
                   $sql2 ="SELECT * FROM m_store ";
                   $sth2  = $connection->prepare($sql2);
                   $sth2->execute();

                   //$store = $sth2->fetch(PDO::FETCH_ASSOC);

                   //役職のデータを受け取る
                   $sql3 ="SELECT * FROM m_post ";
                   $sth3  = $connection->prepare($sql3);
                   $sth3->execute();

                   //$post = $sth3->fetch(PDO::FETCH_ASSOC);

                   //売り場のデータを受け取る
                   $sql4 ="SELECT * FROM m_corner ";
                   $sth4  = $connection->prepare($sql4);
                   $sth4->execute();

                //   $corner = $sth4->fetch(PDO::FETCH_ASSOC);

                }catch (PDOException $e){
                  exit("SQL発行エラー:{$e->getMessage()}");
                }
        ?>

        <form action="user_edit_insert.php" method="post">
          <table border="1">
            <tr><td>名前：<input type="text" name="user_edit_name"></td></tr>
            <tr><td>所属店舗：<select name="user_edit_store">
                            <?php while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)) { ?>
                              <option value="<?php ph($row2["store_id"]);?>"><?php ph($row2["store_name"]);?></option>
                            <?php } ?>
                             </select>
                           </td></tr>
            <tr><td>役職：   <select name="user_edit_position">
                            <?php while($row3 = $sth3->fetch(PDO::FETCH_ASSOC)) { ?>
                              <option value="<?php ph($row3["post_id"]);?>"><?php ph($row3["post_name"]);?></option>
                            <?php } ?>
                            </select></td></tr>
            <tr><td>売り場：  <select name="user_edit_department">
                            <?php while($row4 = $sth4->fetch(PDO::FETCH_ASSOC)) { ?>
                              <option value="<?php ph($row4["corner_id"]);?>"><?php ph($row4["corner_name"]);?></option>
                            <?php } ?>
                            </select></td></tr>
            <tr><td>資格:           <input type="text" name="user_edit_quali"></td></tr>
            <tr><td>一言：          <input type="text" name="user_edit_text"></td></tr>
            <tr><td>ID：            <input type="text" name="user_edit_id"></td></tr>
            <tr><td>メールアドレス： <input type="text" name="user_edit_mail"></td></tr>
            <tr><td>パスワード：     <input type="text" name="user_edit_pass"></td></tr>
            <input type="hidden" name="user_edit_delete" value="0"></td></tr>
            <tr><td>管理者資格： <select name="user_edit_manager">
                                <option value="0">非管理者</option>
                                <option value="1">管理者</option>
                                </select></td></tr>
            <tr><td><input type="submit" value="追加"></td></tr>
          </table>
        </form>
        <?php //var_dump($row2); ?><br>
        <?php //var_dump($row3); ?><br>
        <?php //var_dump($row4); ?><br>
        <?php require_once("../../../footer.php"); // 参照先は必要に応じて/や..でstatic_project直下のファイルを参照して下さい ?>
          </div>
        </div>


    </body>
</html>
