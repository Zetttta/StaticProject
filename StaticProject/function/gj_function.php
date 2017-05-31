<?php
/**
 * このファイルの概要説明:GJで使う関数
 *
 * このファイルの詳細説明：GJ関数が記述されています。。
 *
 *
 *
 * システム名：管理者用画面
 * 作成者：内海洋樹
 * 作成日：2017/5/23
 * 最終更新日：2017/5/23
 * レビュー担当者：
 * レビュー日：2017/5/
 * バージョン：1.0
 */


//このファイルで使われる関数
//$db:DBの接続情報を格納する変数
//$user:DBから受け取ったユーザIDを格納する変数


//GJされた回数を数える関数です。返り値を値で返す。
function countGJ($thread_id,$response_id)
{
  try {
    $dbh = connectDb();//データベースへの接続
    // m_gjのgj_idを抽出
    $sql = "SELECT COUNT(*) FROM m_gj";
    $sql .= " WHERE thread_id=:thread";
    $sql .= " AND response_id=:response";
    //レスポンスとスレッドが同じものを探す
    //SQLの発行準備します。
    $sth = $dbh->prepare($sql);
    //安全なSQLを発行します。
    $sth->bindValue(":thread", $thread_id);
    $sth->bindValue(":response", $response_id);
    //SQLを発行します。
    $sth->execute();
    }catch(PDOException $e){
    //SQLが発行出来なかった場合はこちらの処理を行います。
    print "SQL発行エラー:{$e->getMessage()}";
    //処理を終了します。
    exit;
    }
    // データベースの情報を代入
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
    // $結果を返します。
    return $row["COUNT(*)"];
  }
}


//GJしたのが今ログインしているユーザであるかを確認する関数です。返り値をTRUE,FALSE。
function checkGJ($thread_id,$response_id)
{
  $judgeTF = 0;
  try {
    $dbh = connectDb();//データベースへの接続
    // m_gjのgj_idを抽出
    $sql = "SELECT t4.user_id FROM m_thread t1";
    // レスポンス、ｇｊ、user,と結合
    $sql .= " LEFT OUTER JOIN m_response t2 ON t1.thread_id = t2.thread_id";
    //レスポンスマスタと結合
    $sql .= " LEFT OUTER JOIN m_gj t3 ON t2.thread_id = t3.thread_id AND t2.response_id = t3.response_id";
    //GJマスタと結合
    $sql .= " LEFT OUTER JOIN m_user t4 ON t3.user_id = t4.user_id";
    //ユーザマスタと結合
    $sql .= " WHERE t3.thread_id=:thread";
    $sql .= " AND t3.response_id=:response";
    $sql .= " GROUP BY t4.user_id";
      //SQLの発行準備します。
      $sth = $dbh->prepare($sql);
      //安全なSQLを発行します。
      $sth->bindValue(":thread", "{$thread_id}");
      $sth->bindValue(":response", "{$response_id}");
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
      if($user == $_SESSION['identify']){
        $judgeTF = 1;

      }else{

      }
    }
    if($judgeTF == 1){
      return TRUE;
    }else{
        return FALSE;
    }
    
}
