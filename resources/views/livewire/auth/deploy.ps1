Write-Host "=== MULAI DEPLOYMENT KE CPANEL ===" -ForegroundColor Cyan

# 1. Pastikan semua perubahan sudah di-commit dan di-push ke GitHub
git add .
$commitMsg = Read-Host "Masukkan pesan commit (contoh: update fitur)"
git commit -m "$commitMsg"
git push origin main

Write-Host "=== KODE BERHASIL DI-PUSH KE GITHUB ===" -ForegroundColor Green
Write-Host "Silakan jalankan perintah pembersihan di cPanel atau gunakan SSH remote jika aktif."