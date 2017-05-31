<?php
/**
 * このファイルの概要説明:全ての画面で共通して使う関数
 *
 * このファイルの詳細説明：次に関する関数が記述されています。不要な部分に関しては、適宜削除してください。
 *                      データベースに接続する関数/
 *                      ユーザIDをDBから受け取る関数/管理者フラグをDBから受け取る関数/
 *                      セッションからログイン情報を受け取る関数/管理者フラグを判定する関数
 *                      また、1番上に記述されているsession_start();は消さないでください・
 *
 * システム名：管理者用画面
 * 作成者：坂田恵未
 * 作成日：2017/5/19
 * 最終更新日：2017/5/19
 * レビュー担当者：伊藤尚輝
 * レビュー日：2017/5/19
 * バージョン：1.0
 */


//このファイルで使われる関数
//$db:DBの接続情報を格納する変数
//$user:DBから受け取ったユーザIDを格納する変数



session_start();//ここでセッションを開始します。


//データベースに接続する関数です。全てのページで使います。
function connectDB()
{

    //データベースの名前とIPアドレスを格納
    $dbn     = 'mysql:dbname=drag_db; host=localhost; charset=utf8';//192.168.20.2
    //データベースのユーザー名格納
    $DBuser ='root';
    //データベースのパスワード格納
    $DBpass ='password';

    //データベースへの接続
    try{
      $ddb = new PDO($dbn, $DBuser, $DBpass);
      //
      $ddb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(Exception$e){
      print "データベース接続エラー:{$e->getMessage()}";
      //処理を終了します。
      exit;
    }
    //エラーが無ければ$ddbに結果を格納
    return $ddb;
}

//データベースからユーザーIDの情報を受け取る関数です。
function getUser()
{
  //データベースと接続します。
  $dbh = connectDB();
  //トライとキャッチで例外処理をします。
    try{
      //m_userテーブルのuser_idレコードを読み出します。
      $sql = "SELECT user_id FROM m_user WHERE user_id=:user";
      //SQLの発行準備します。
      $sth = $dbh->prepare($sql);
      //安全なSQLを発行します。
      $sth->bindValue(":user", $_SESSION["identify"]);
      //SQLを発行します。
      $sth->execute();
    }catch(PDOException $e){
      //SQLが発行出来なかった場合はこちらの処理を行います。
      print "SQL発行エラー:{$e->getMessage()}";
      //処理を終了します。
      exit;
    }
    //$userにデータベースの情報を代入
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
      $user = $row["user_id"];
    }
    //$userに結果を返します。
    return $user;
}


//DBから管理者フラグの情報を受け取る関数です。管理者判断が要らない場合は消してください。
function getManager()
{
    //データベースと接続します。
    $dbh = connectDB();
    //トライとキャッチで例外処理をします。
      try{
        //m_userテーブルのuser_managerレコードを読み出します。
        $sql = "SELECT user_manager FROM m_user";
        $sql.=" WHERE user_id=:user";
        //SQLの発行準備します。
        $sth = $dbh->prepare($sql);
        $sth->bindValue(":user", $_SESSION["identify"]);
        //SQLを発行します。
        $sth->execute();
      }catch(PDOException $e){
        //SQLが発行出来なかった場合はこちらの処理を行います。
        print "SQL発行エラー:{$e->getMessage()}";
        //処理を終了します。
        exit;
      }
      //$managerにデータベースの情報を代入
      while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
        $manager = $row["user_manager"];
      }
      //$managerに結果を返します。
      return $manager;
}


//セッションからログインしているかどうかの情報を受け取る関数です。
function loginIdentify()
{
    try{
        //セッションに格納されているlodin情報とidentify情報が空でないか確認します。
        if (empty($_SESSION['login'] && $_SESSION['identify'] )){
          //空の場合はログインページにリダイレクトします。
          header( "Location:/static_project/php/login/login_main.php" );
          //処理を終了します。
          exit;
        }
        //セッションが格納されている場合は何も動作しません。
    }catch(Exception$e){
        //セッションエラーが出た場合はこちらの処理を実行します。
      print "セッションエラー:{$e->getMessage()}";
      //処理を終了します。
      exit;
    }

}


//管理者フラグを判定する関数です。不要な場合は消してください。
function Manager($manager)
{
    try{
        //getManager関数から、管理者フラグの情報を受け取ります。
        $judge=getManager($manager);
        //管理者かどうかを判断します。
        if ($judge == false){
          //管理者でない場合はトップページにリダイレクトします。
          header( "Location:/static_project/php/toppage/toppage.php" );
          exit;
        }
        //セッションが格納されている場合は何も動作しません。

    }catch(Exception$e){
      //エラーが出た場合はこちらの処理を実行します。
      print "エラー:{$e->getMessage()}";
      //処理を終了します。
      exit;
    }

}
