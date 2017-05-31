
/* INSERT */

INSERT INTO drag_db.m_store VALUES(1,'浜松町店','東京都港区浜松町','0363098784',false);/* 店舗 */
INSERT INTO drag_db.m_corner VALUES(1,'くすり',false);/*売り場  */
INSERT INTO drag_db.m_post VALUES(0,'未所属',false);/* 役職 */
INSERT INTO drag_db.m_post VALUES(1,'一般',false);/* 役職 */
INSERT INTO drag_db.m_post VALUES(2,'情報',false);/* 役職 */
INSERT INTO drag_db.m_user VALUES('abc','password1','static@static.com','静的悦子',1,'',false,false,1,1,'英検3級');/* 一般ユーザ */
INSERT INTO drag_db.m_user VALUES('abd','password2','yorokobi@static.com','静的悦男',1,'',true,false,1,2,'2級小型船舶');/* 管理者 */
INSERT INTO drag_db.m_notice VALUES('1','お知らせ1',20170519,20170526,'プロジェクトの成果発表は26日です。',false);/* お知らせ */
INSERT INTO drag_db.m_notice VALUES('2','お知らせ2',20170522,20170526,'24日は予備日です。',false);/* お知らせ */



INSERT INTO drag_db.m_thread VALUES(0,'テストスレッド0(ダミー)',FALSE,FALSE);
INSERT INTO drag_db.m_thread VALUES(1,'テストスレッド1',FALSE,FALSE);
INSERT INTO drag_db.m_thread VALUES(2,'テストスレッド2',FALSE,TRUE);
INSERT INTO drag_db.m_thread VALUES(3,'テストスレッド3',TRUE,FALSE);

INSERT INTO drag_db.m_response VALUES(0,0,'abc','2017-05-20 00:00:00','テストスレッド0です。\nこのスレッドは管理者であってもアクセスできません(ダミーデータ)',FALSE);
INSERT INTO drag_db.m_response VALUES(0,1,'abc','2017-05-20 00:00:00','テストスレッド1です。\nこのスレッドは公開スレッドで、削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(1,1,'abd','2017-05-20 00:00:00','レストレスポンス1です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(2,1,'abd','2017-05-20 00:00:00','レストレスポンス2です。\nこのレスポンスは削除されています',TRUE);
INSERT INTO drag_db.m_response VALUES(3,1,'abd','2017-05-20 00:00:00','レストレスポンス3です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(4,1,'abd','2017-05-20 00:00:00','レストレスポンス4です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(5,1,'abd','2017-05-20 00:00:00','レストレスポンス5です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(6,1,'abd','2017-05-20 00:00:00','レストレスポンス6です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(7,1,'abd','2017-05-20 00:00:00','レストレスポンス7です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(8,1,'abd','2017-05-20 00:00:00','レストレスポンス8です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(9,1,'abd','2017-05-20 00:00:00','レストレスポンス9です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(10,1,'abd','2017-05-20 00:00:00','レストレスポンス10です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(11,1,'abd','2017-05-20 00:00:00','レストレスポンス11です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(12,1,'abd','2017-05-20 00:00:00','レストレスポンス12です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(13,1,'abd','2017-05-20 00:00:00','レストレスポンス13です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(14,1,'abd','2017-05-20 00:00:00','レストレスポンス4です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(15,1,'abd','2017-05-20 00:00:00','レストレスポンス5です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(16,1,'abd','2017-05-20 00:00:00','レストレスポンス6です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(17,1,'abd','2017-05-20 00:00:00','レストレスポンス7です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(18,1,'abd','2017-05-20 00:00:00','レストレスポンス8です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(19,1,'abd','2017-05-20 00:00:00','レストレスポンス9です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(20,1,'abd','2017-05-20 00:00:00','レストレスポンス9です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(21,1,'abd','2017-05-20 00:00:00','レストレスポンス1です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(22,1,'abd','2017-05-20 00:00:00','レストレスポンス2です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(23,1,'abd','2017-05-20 00:00:00','レストレスポンス3です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(24,1,'abd','2017-05-20 00:00:00','レストレスポンス4です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(25,1,'abd','2017-05-20 00:00:00','レストレスポンス5です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(26,1,'abd','2017-05-20 00:00:00','レストレスポンス6です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(27,1,'abd','2017-05-20 00:00:00','レストレスポンス7です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(28,1,'abd','2017-05-20 00:00:00','レストレスポンス8です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(29,1,'abd','2017-05-20 00:00:00','レストレスポンス9です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(30,1,'abd','2017-05-20 00:00:00','レストレスポンス10です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(31,1,'abd','2017-05-20 00:00:00','レストレスポンス11です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(32,1,'abd','2017-05-20 00:00:00','レストレスポンス12です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(33,1,'abd','2017-05-20 00:00:00','レストレスポンス13です。\nこのレスポンスは削除されていません',FALSE);
INSERT INTO drag_db.m_response VALUES(0,2,'abc','2017-05-20 00:00:00','テストスレッド2です。\nこのスレッドは公開スレッドで、削除されています',FALSE);
INSERT INTO drag_db.m_response VALUES(0,3,'abc','2017-05-20 00:00:00','テストスレッド3です。\nこのスレッドは非公開スレッドで、削除されていません',FALSE);


/*
  UPDATE drag_db.m_response SET response_inner = 'テストレスポンス3です。\nこのレスポンスは削除されていません' WHERE thread_id = 1 AND response_id = 3;
*/
commit;

INSERT INTO drag_db.m_thread VALUES(3,'テストスレッド3',TRUE,FALSE);
INSERT INTO drag_db.m_response VALUES(0,3,'abc','2017-05-20 00:00:00','テストスレッド3です。\nこのスレッドは非公開スレッドで、削除されていません',FALSE);