# powershell:
# Get-ExecutionPolicy
# Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
# cd "C:\Scripts"
# .\tickOrder.ps1
# .\tickOrder.ps1 -OrderID 12345 -FileName "example.pdf"

# Command Prompt:
# powershell -ExecutionPolicy Bypass -File "C:\Scripts\tickOrder.ps1"

# API domain
$apiGetOrder = "http://stageofvisualization.local/admin/backup/send_order_to_local"
$apiTickOrder = "http://stageofvisualization.local/admin/backup/order_set_download_time"

# Đọc token từ file token.txt
# $token = Get-Content "E:\khanh-task-schedule\token.txt"
$token = '123';

# Thư mục gốc để chứa các order
$rootFolder = "E:\khanh-task-schedule\order"

# Gửi yêu cầu đến API để lấy danh sách các order và ảnh
try {
    $response = Invoke-WebRequest -Uri $apiGetOrder -Headers @{ "Authorization" = "Bearer $token" }
    $jsonContent = $response.Content
    # Write-Host "Response from API: $jsonContent"
} catch {
    Write-Host "Failed to call API: $($_.Exception.Message)" -ForegroundColor Red
    exit
}

# Chuyển đổi nội dung JSON thành đối tượng PowerShell
try {
    $orderData = $jsonContent | ConvertFrom-Json
      
    # Đếm số lượng ORDER từ json trả về
    $orderCount = 0
    foreach ($order in $orderData.PSObject.Properties) {
        $orderCount++
    }

    # Vòng lặp qua từng order trong dữ liệu JSON
    $countDone = 0;
    Write-Host "Start backup file..."
    foreach ($order in $orderData.PSObject.Properties) {
        
        $orderID = $order.Name
        $fileLinks = $order.Value

        # Tạo thư mục cho order này bên trong thư mục rootFolder
        $orderFolder = Join-Path -Path $rootFolder -ChildPath $orderID
        New-Item -ItemType Directory -Path $orderFolder -Force | Out-Null

        # Vòng lặp qua từng liên kết file trong order
        foreach ($fileLink in $fileLinks) {
            # Bắt đầu tải file
            Write-Host "Downloading: Order #$orderID - File $fileLink" -ForegroundColor Gray
            try {
                
                # Lấy tên file từ URL (tách phần cuối của URL, ví dụ 'qurBP-im.jpg')
                $fileName = [System.IO.Path]::GetFileName($fileLink)                
                $destination = Join-Path -Path $orderFolder -ChildPath $fileName

                # Thực hiện tải file
                Start-BitsTransfer -Source $fileLink -Destination $destination
                
                # File download thành công gọi API để đánh dấu vào server
                # $apiTickOrderWithParams = "$($apiTickOrder)?order=$orderID&filename=$fileName"
                # $tickDowloaded = Invoke-WebRequest -Uri $apiTickOrderWithParams -Headers @{ "Authorization" = "Bearer $token" }
            }
            catch {
                Write-Host "Failed to download file: $orderID-$fileLink" -ForegroundColor Red
            }
        }

        # File download thành công gọi API để đánh dấu vào server
        $apiTickOrderWithParams = "$($apiTickOrder)?order=$orderID"
        $tickDowloaded = Invoke-WebRequest -Uri $apiTickOrderWithParams -Headers @{ "Authorization" = "Bearer $token" }

        # Tiến trình hoàn thành ORDER 
        $countDone ++;
        Write-Host "Total order completed: $countDone/$orderCount" -ForegroundColor Green
    }
   
} catch {
    Write-Host "Failed to parse JSON: $($_.Exception.Message)" -ForegroundColor Red
}

# Dừng script để xem log
Write-Host "Downloaded all orders to $rootFolder! . Press Enter to exit." -ForegroundColor Green
Read-Host
