<?php

/**
* ログイン場面
*
* ログイン場面です。
* ログインが失敗した場合はエラーメッセージを表示されます。
* ログインが成功した場合はトップページに遷移します。
*
* システム名：ログイン場面
* 作成者：朴
* 作成日：2017/05/18
* 最終更新日：2017/05/21
* レビュー担当者：
* レビュー日：
* バージョン：1.0
*/

  require_once("../../function/database_session.php");//関数を読み込んでDBを読み込みます。
  $dbh = connectDB();


  $_SESSION["identify"] = null;//まずSESSIONはFALSEでする。
  $_SESSION["login"]=false;

  if(isset($_POST['login_id'])==false){//まだIDで何もしなかった時の状態をNULLにします。
    $_POST['login_id']=null;
  }
  if(isset($_POST['login_pass'])==false){//まだPASSWORDで何もしなかった時の状態をNULLにします。
    $_POST['login_pass']=null;
  }

  $id = $_POST['login_id'];//IDとパスワードの値をPOSTで読み込みます。
  $pass = $_POST['login_pass'];


  if(empty($_POST["login_id"]) && empty($_POST["login_pass"])){//入力が空欄の場合。
    $msg = " ";
  }elseif(empty($_POST["login_id"]) || empty($_POST["login_pass"])){//一つの入力欄でも空欄の場合。
    $msg = "IDとパスワードを入力してください。";
  }else{//二つの欄が入力された時。
    $sql = "select user_id,user_pw,user_manager from drag_db.m_user where user_id=:id";//MYSQLで入力できたIDが存在するか確認
    $sth = $dbh->prepare($sql);
    $sth->bindValue(":id", $id);
    $sth->execute();
    $row = $sth->fetch(PDO::FETCH_ASSOC);


    if($row['user_id'] == $_POST["login_id"] && $row['user_pw'] == $_POST["login_pass"]){//IDが存在する場合。
      //データベースのPASSWORDが入力されたPASSWORDと同じ場合。
          $_SESSION["identify"] = $id; //IDをセッションにセーブ
          $_SESSION["login"]=true; //loginセッションをtrueにする。
          header("Location:../toppage/toppage.php");//トップページへ移動。


    }else{
      $_SESSION["identify"] = null;
      $_SESSION["login"]=false;
      $msg= "IDまたはパスワードがちがいます。";
    }
  }
require_once("login_main_view.php");
