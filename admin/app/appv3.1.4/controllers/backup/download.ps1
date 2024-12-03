# API domain
$api = "http://stageofvisualization.local/cronjob/backup-order-file/get_files.php"

# Đọc token từ file token.txt
$token = Get-Content "E:\khanh-task-schedule\token.txt"

# Gửi yêu cầu đến API để lấy danh sách các order và ảnh
try {
    $response = Invoke-WebRequest -Uri $api -Headers @{ "Authorization" = "Bearer $token" }
    $jsonContent = $response.Content
    # Write-Host "Response from API: $jsonContent"
} catch {
    Write-Host "Failed to call API: $($_.Exception.Message)" -ForegroundColor Red
    exit
}

# Chuyển đổi nội dung JSON thành đối tượng PowerShell
try {
    $orderData = $jsonContent | ConvertFrom-Json
    # Write-Host "Parsed JSON content: $($orderData)"  
      
    # Đếm số lượng ORDER từ json trả về
    $orderCount = 0
    foreach ($order in $orderData.PSObject.Properties) {
        $orderCount++
    }

    # Thư mục gốc để chứa các order
    $rootFolder = "E:\khanh-task-schedule"
    #Write-Host "Using root folder: $rootFolder"

    # Tạo thư mục với tên dạng YYYY-mm-dd_HH-mm-ss bên trong rootFolder
    $dateTimeFolderName = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
    $dateTimeFolder = Join-Path -Path $rootFolder -ChildPath "Backup_$dateTimeFolderName"
    New-Item -ItemType Directory -Path $dateTimeFolder -Force | Out-Null
    #Write-Host "Created timestamped directory: $dateTimeFolder"

    # Vòng lặp qua từng order trong dữ liệu JSON
    $countDone = 0;
    foreach ($order in $orderData.PSObject.Properties) {
        $countDone ++;
        $orderName = $order.Name
        $imageLinks = $order.Value

        # Tạo thư mục cho order này bên trong thư mục timestamped
        $orderFolder = Join-Path -Path $dateTimeFolder -ChildPath $orderName
        New-Item -ItemType Directory -Path $orderFolder -Force | Out-Null
        #Write-Host "Created directory: $orderFolder"

        # Vòng lặp qua từng liên kết ảnh trong order
        foreach ($imageLink in $imageLinks) {
            try {
                # Gửi yêu cầu tải ảnh
                $response = Invoke-WebRequest -Uri $imageLink -Headers @{ "Authorization" = "Bearer $token" }

                # Lấy tên file từ liên kết ảnh
                $fileName = [System.IO.Path]::GetFileName($imageLink)

                # Tạo đường dẫn đầy đủ cho ảnh trong thư mục order
                $filePath = Join-Path -Path $orderFolder -ChildPath $fileName

                # Sử dụng WriteAllBytes để lưu ảnh nhanh hơn
                [System.IO.File]::WriteAllBytes($filePath, $response.Content)
                $downloadedImages++
                # Write-Host "Downloaded image: $fileName to $orderFolder"
            }
            catch {
                Write-Host "Failed to download image: $imageLink" -ForegroundColor Red
            }
        }

        # Tiến trình hoàn thành ORDER 
        Write-Host "PROGRESS: $countDone/$orderCount"
    }
   
} catch {
    Write-Host "Failed to parse JSON: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "Downloaded all orders to $rootFolder!"

# Dừng script để xem log
Write-Host "Script execution completed. Press Enter to exit."
Read-Host
