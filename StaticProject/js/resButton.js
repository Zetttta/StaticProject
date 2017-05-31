/**
 * このファイルの概要説明
 * 非公開設定メニューの展開処理
 * 
 * このファイルの詳細説明
 * クリックした際に非公開設定メニューを表示
 * それぞれのメニューにマウスを重ねた際に背景色を変化させる
 *
 * システム名：    headMenu用JavaScript
 * 作成者：        伊藤尚輝
 * 作成日：        2017/05/22/13:00
 * 最終更新日：
 * レビュー担当者：
 * レビュー日：    
 * バージョン：    
 */
 
// 読み込み時設定パネルを非表示に

$(function() {
    // 色変え処理
    $('#resButton').hover(function() {
        // 色を変更
        $("#resButton").css('background', '#8DC1DD');
    // マウスが離れた時の処理
    }, function() {
        // 色を戻す
        $("#resButton").css('background', '');
    });
    
});

// レスボタンクリック処理
function response(thread_id){
    location.href = "res_edit/res_create.php?thread_id=" + thread_id;
}