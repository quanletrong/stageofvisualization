## 📌 Task 1: INSERT những đơn đủ điều kiện vào bảng `tbl_bak_order`

```
- Tần suất: 00:00 mỗi ngày
- Endpoint: /cronjob/backup-order-file/get_files.php
NOTE: chuyển sang /admin/backup/order_insert
- Điều kiện 1: đơn hoàn thành. Xét trường `status` = 9
- Điều kiện 1: đơn hoàn thành trước hiện tại 3 tháng. Xét trường `done_qc_time`
```

- Tham khảo:
  - insert_bak_order_job.php
  - insert_bak_order_rework.php
  - insert_bak_order_discuss.php

## 📌 Task 2: unlink() file discuss của đơn.

```
- Tần suất: 01:00 mỗi ngày
- Endpoint: /cronjob/backup-order-file/get_files.php
  Endpoint Mới: /admin/backup/order_discuss_unlink
```

### 1. Tạo danh sách file discuss cần xóa theo điều kiện:

- Điều kiện 1: trường `file_type` = 5
- Điều kiện 2: trường `bak_date_time` = NULL

### 2. Tạo thumb danh sách

### 3. unlink() danh sách

## 📌 ✅ Task 3: unlink() file rác trong đơn.

```
- Tần suất: 02:00 mỗi ngày
- Endpoint: Mới /admin/backup/order_trash_unlink
```

### 1. Tạo danh sách **$order_file_db**

- Loop đơn đã hoàn thành:
  - Loop danh sách job:
    - Lấy file chính lưu vào **$order_file_db**
    - Lấy file ref lưu vào **$order_file_db**
    - Lấy danh sách file hoàn thành. lưu vào **$order_file_db**
  - Loop danh sách rework
    - Lấy danh sách file ref lưu vào **$order_file_db**
    - Lấy danh sách file hoàn thành lưu vào **$order_file_db**

### 2. Tạo danh sách **$order_file_folder**

- Quét thư mục đơn lưu vào **$order_file_folder**

### 3. Tạo danh sách **$order_file_unlink**

**$order_file_unlink** = **$order_file_folder** - **$order_file_db**

### 4. unlink() danh sách

## 📌 Task 4: Backup đơn về máy local.

### 1. Phía server tạo 2 api:

1. api trả về danh sách file cần backup cho local.

- Endpoint: /admin/backup/order_file_list
- Điều kiện 1 trường `file_type` = main, ref, complete, rework
- Điều kiện 2 trường `bak_date_time` = null

2. api cập nhật trường `bak_date_time` sau khi local tải xong 1 file

- Endpoint: /admin/backup/order_file_bak_date_time_update

### 2. Phía local tải file về máy

- Tần suất: 03:00 mỗi ngày
- Tạo sh và cấu hình Scheduler call sh theo tần suất
  - Call api thứ nhất để lấy danh sách file để tải
  - Loop danh sách file
    - Tải file
    - Tải thành công call api thứ 2 để đánh dấu đã tải xong

## 📌Task 5: unlink() file order đã backup thành công.

```
- Tần suất: 04:00 mỗi ngày
- Endpoint: /admin/backup/order_file_unlink
- Lấy danh sách file để unlink() điều kiện trường `bak_date_time` khác NULL
- Tạo thumb cho danh sách
- unlink() danh sách
```

Thứ tự chạy Task:

```
┌ 📌 Task 1 00h:00 INSERT những đơn đủ điều kiện vào bảng `tbl_bak_order`
├── 📌 Task 2 01h:00 unlink() file discuss của đơn.
├── 📌 Task 3 02h:00 unlink() file rác trong đơn.
├── 📌 Task 4 03h:00 Backup đơn về máy local.
├──── 📌 Task 5 04h:00 unlink() file order đã backup thành công.

```
