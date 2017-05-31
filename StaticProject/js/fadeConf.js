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

window.onload = function(){
    $("#fadeConfBox").hide();
    $(".user_box").hide();
    $("#user_box0").show();
}

$(function() {
    // 色変え処理
    $('#fadeConf').hover(function() {
        // 色を変更
        $("#fadeConf").css('background', '#8DC1DD');
    // マウスが離れた時の処理
    }, function() {
        // 色を戻す
        $("#fadeConf").css('background', '');
    });
    
    // #fadeCongがクリックされた時の処理
    $('#fadeConf').on('click', function() {
        // fadeConfBoxの表示を切り替える
        $("#fadeConfBox").toggle();
    });
    
});

// ページング用のボタン処理
function change(user_box){
    $(".user_box").hide();
    $("#user_box" + user_box).show();
}