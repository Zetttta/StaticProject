 SELECT * FROM m_thread t1
INNER JOIN m_response t2 ON t1.thread_id = t2.thread_id
INNER JOIN m_gj t3 ON t2.response_id = t3.response_id
INNER JOIN m_user t4 ON t3.user_id = t4.user_id
;

SELECT t1.thread_delete,t2.response_delete
 FROM m_thread t1
LEFT OUTER JOIN m_response t2 ON t1.thread_id = t2.thread_id
LEFT OUTER JOIN m_gj t3 ON t2.response_id = t3.response_id
LEFT OUTER JOIN m_user t4 ON t3.user_id = t4.user_id;