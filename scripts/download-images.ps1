# Recovery script for original flower photos; usually unnecessary because photos are committed.
$ErrorActionPreference = "Stop"
$root = Split-Path -Parent $PSScriptRoot
$target = Join-Path $root "assets\images"
New-Item -ItemType Directory -Path $target -Force | Out-Null
$names = @("garden.png","lam-tinh.png","hoa-ly.png","ly-xanh.png","lily.png","mot-bo-hoa.png","cam-tu-cau.png","phi-yen.png","son-sac-thuy-chung.png")
foreach ($name in $names) {
  Invoke-WebRequest -UseBasicParsing -Uri "https://raw.githubusercontent.com/tbao4151/Lap_trinh_web/main/assets/images/$name" -OutFile (Join-Path $target $name)
  Write-Host "Downloaded: $name"
}
