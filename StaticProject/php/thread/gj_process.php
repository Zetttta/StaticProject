<?php
require_once('../../function/xss.php');
require_once('../../function/database_session.php');
 $user = getUser();//セッションからユーザIDを取得

//thread_idがはいっているかどうか判断する処理、数値が無かったらリダイレクト
if(empty($_GET["thread_id"])){
    header('location: ../toppage/toppage.php'); // リダイレクト処理、トップページへ
    exit();
}
// thread_idが数値かどうか判断する処理、数値で無い文字があったらリダイレクト
if(ctype_digit ($_GET["thread_id"])==FALSE){
    header('location: ../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
    exit();
}

// thread_idが0以下かどうか判断する処理、0以下であるならリダイレクト
if($_GET["thread_id"] <= 0){
    header('location: ../toppage/toppage.php'); // リダイレクト処理、トップページのurlにあとで変更する
    exit();
}
$dbh = connectDb();
try {
    // m_threadのthread_idを抽出
    $sql = "SELECT t1.thread_delete,t2.response_delete FROM m_thread t1";
    // レスポンス、ｇｊ、user,と結合
    $sql .= " LEFT OUTER JOIN m_response t2 ON t1.thread_id = t2.thread_id";
    //
    $sql .= " LEFT OUTER JOIN m_gj t3 ON t2.response_id = t3.response_id";
    //
    $sql .= " LEFT OUTER JOIN m_user t4 ON t3.user_id = t4.user_id";
    //
    $sql .= " WHERE t1.thread_id = :thread";
    //
    $sql .= " AND t2.response_id = :response";
    // SQLを準備
    $sth = $dbh->prepare($sql);
    // $_GET["thread_id"]がgetされていることを確認

    // されているのであれば、searchにthread_idを格納
    $sth->bindValue(":thread", $_GET['thread_id']);


    // されているのであれば、searchにthread_idを格納
    $sth->bindValue(":response", $_GET['response_id']);


    // SQLを発行
    $sth->execute();
} catch (PDOException $e) {
    header('location: ../toppage/toppage.php'); // リダイレクト処理、
    exit();
}
// 削除フラグを$judgeDeleteに格納
while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $judgeDelete = $row["thread_delete"];
}
// もしも該当スレッドが削除されているならばリダイレクト
if($judgeDelete == TRUE){
    header('location: ../toppage/toppage.php'); // リダイレクト処理、
    exit();
}
try {
    // $user = getUser();//セッションからユーザIDを取得
    // m_gjにthread_id,response_id,ユーザIDを挿入
    $sql = "INSERT INTO drag_db.m_gj(gj_id,thread_id,response_id,user_id)";
    // gj_id最大値+1
    $sql .= " SELECT COALESCE(MAX(gj_id)+1,1),";
    // thread_id
    $sql .= ":thread,";
    // response_idは同じ
    $sql .= ":response,";
    // ユーザID
    $sql .= ":user";
    $sql .= " FROM m_gj;";
    // SQLを準備
    $sth = $dbh->prepare($sql);
    // :threadに挿入
    $sth->bindValue(":thread", "{$_GET['thread_id']}");
    //:responceに挿入
    $sth->bindValue(":response", "{$_GET['response_id']}");
    $sth->bindValue(":user", "{$user}");

    // SQLを発行
    $sth->execute();
} catch (PDOException $e) {
    
    header('location: ../toppage/toppage.php'); // リダイレクト処理、
    exit("SQL発行エラー：{$e->getMessage()}");
}
header('location: thread_Individual.php?thread_id=' . "{$_GET['thread_id']}" . '&page_id=1'); // リダイレクト処理、
exit();
?>
