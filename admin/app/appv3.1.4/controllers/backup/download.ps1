
# powershell:
# Get-ExecutionPolicy
# Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
# cd "C:\Scripts"
# .\tickOrder.ps1
# .\tickOrder.ps1 -OrderID 12345 -FileName "example.pdf"

# powershell.exe -NoProfile -ExecutionPolicy Bypass -File D:\xampp\htdocs\stageofvisualization\admin\app\appv3.1.4\controllers\backup\download.ps1

# Command Prompt:
# powershell -ExecutionPolicy Bypass -File "C:\Scripts\tickOrder.ps1"

$rootFolder = "D:\svbackup\order"
$tokenFile = "D:\svbackup\token.txt"
$apiGetOrder = "https://stageofvisualization.com/admin/backup/send_order_to_local"
$apiTickOrder = "https://stageofvisualization.com/admin/backup/order_set_download_time"
$token = Get-Content $tokenFile
$startTime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"

# 
Write-Host "`n`n`n`n`n`n`n`n`n"
Write-Host "Hello! This is stageofvisualization.com's daily data backup software". -ForegroundColor Green
Write-Host "The program is taking orders...". -ForegroundColor Green

# 
try {
    
    $response = Invoke-WebRequest -Uri $apiGetOrder -Headers @{ "Authorization" = "Bearer $token" }
    $jsonContent = $response.Content

    if($jsonContent -eq '[]') {
        Write-Host "No backup orders at this time!" -ForegroundColor Green
        Write-Host "Press Enter to exit." -ForegroundColor Green
        Read-Host
        exit;
    }
} catch {
    Write-Host "Failed get order list: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Press Enter to exit." -ForegroundColor Red
    Read-Host
    exit
}
# 
try {
    $countDone = 0;
    $orderData = $jsonContent | ConvertFrom-Json
    $orderTotal = ($orderData.PSObject.Properties | Measure-Object).Count

    Write-Host "Found $orderTotal orders to backup. Starting backup file..." -ForegroundColor Green

    foreach ($order in $orderData.PSObject.Properties) {
        # 
        Write-Host "`nTotal order completed: $countDone/$orderTotal" -ForegroundColor Green
        # 
        $orderID = $order.Name
        $fileLinks = $order.Value
        $orderFolder = Join-Path -Path $rootFolder -ChildPath $orderID
        New-Item -ItemType Directory -Path $orderFolder -Force | Out-Null
        # 
        foreach ($fileLink in $fileLinks) {
            Write-Host "Downloading order: #$orderID - File $fileLink" -ForegroundColor Gray
            try {                
                $fileName = [System.IO.Path]::GetFileName($fileLink)                
                $destination = Join-Path -Path $orderFolder -ChildPath $fileName
                Start-BitsTransfer -Source $fileLink -Destination $destination
            }
            catch {
                Write-Host "Failed to download file: $orderID-$fileLink" -ForegroundColor Red
            }
        }
        # 
        # $apiTickOrderWithParams = "$($apiTickOrder)?order=$orderID"
        # $tickDowloaded = Invoke-WebRequest -Uri $apiTickOrderWithParams -Headers @{ "Authorization" = "Bearer $token" }
        # 
        $countDone ++;        
    }
    
    # 
    Write-Host "Downloaded all orders to $rootFolder!" -ForegroundColor Green
    Write-Host "Start time backup: $startTime" -ForegroundColor Green
    Write-Host "End time backup: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")" -ForegroundColor Green
    Write-Host "Press Enter to exit." -ForegroundColor Green
    Read-Host
    exit;
   
} catch {
    Write-Host "Failed to parse JSON $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Press Enter to exit." -ForegroundColor Red
    Read-Host
    exit;
}