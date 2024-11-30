-- DANH SÁCH FILE CHAT NỘI BỘ
SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_discuss, b.file
FROM tbl_order as a
INNER JOIN tbl_order_discuss as b ON a.id_order = b.id_order AND b.file != '{}'
INNER JOIN tbl_user as c ON c.id_user = a.id_user
WHERE
1=1
-- AND a.`status` = 9
-- AND MONTH(a.create_time) = 3 AND YEAR(a.create_time) = YEAR(CURDATE())
AND a.done_qc_time < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
ORDER BY a.id_order;


-- DANH SÁCH FILE CHAT NỘI BỘ
SELECT a.id_order, a.create_time, c.username, c.id_user, b.id_discuss, b.file
FROM tbl_order as a
INNER JOIN tbl_order_discuss as b ON a.id_order = b.id_order AND b.file != '{}'
INNER JOIN tbl_user as c ON c.id_user = a.id_user
WHERE
1=1
-- AND a.`status` = 9
-- AND MONTH(a.create_time) = 3 AND YEAR(a.create_time) = YEAR(CURDATE())
AND a.done_qc_time < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
ORDER BY a.id_order;