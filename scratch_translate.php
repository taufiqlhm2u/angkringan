<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$iterator = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$translations = [
    "__('Login')" => "__('Masuk')",
    "__('Register')" => "__('Daftar')",
    "__('Log in')" => "__('Masuk')",
    "__('Email')" => "__('Email')",
    "__('Email address')" => "__('Alamat Email')",
    "__('Password')" => "__('Kata Sandi')",
    "__('Remember me')" => "__('Ingat saya')",
    "__('Forgot your password?')" => "__('Lupa kata sandi Anda?')",
    "__('Welcome')" => "__('Selamat Datang')",
    "__('Dashboard')" => "__('Dasbor')",
    "__('Settings')" => "__('Pengaturan')",
    "__('Log out')" => "__('Keluar')",
    "__('Profile')" => "__('Profil')",
    "__('Security')" => "__('Keamanan')",
    "__('Appearance')" => "__('Tampilan')",
    "__('Update Password')" => "__('Perbarui Kata Sandi')",
    "__('Ensure your account is using a long, random password to stay secure.')" => "__('Pastikan akun Anda menggunakan kata sandi acak dan panjang agar tetap aman.')",
    "__('Current Password')" => "__('Kata Sandi Saat Ini')",
    "__('New Password')" => "__('Kata Sandi Baru')",
    "__('Confirm Password')" => "__('Konfirmasi Kata Sandi')",
    "__('Save')" => "__('Simpan')",
    "__('Saved.')" => "__('Tersimpan.')",
    "__('Delete Account')" => "__('Hapus Akun')",
    "__('Delete your account and all of its resources')" => "__('Hapus akun Anda dan semua datanya')",
    "__('Delete account')" => "__('Hapus akun')",
    "__('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')" => "__('Setelah akun dihapus, semua datanya akan dihapus permanen. Silakan masukkan kata sandi untuk konfirmasi penghapusan akun Anda.')",
    "__('Cancel')" => "__('Batal')",
    "__('Are you sure you want to delete your account?')" => "__('Apakah Anda yakin ingin menghapus akun Anda?')",
    "__('Two Factor Authentication')" => "__('Autentikasi Dua Langkah')",
    "__('Add additional security to your account using two factor authentication.')" => "__('Tingkatkan keamanan akun menggunakan autentikasi dua faktor.')",
    "__('Name')" => "__('Nama')",
    "__('Update your account\\'s profile information and email address.')" => "__('Perbarui informasi profil dan alamat email akun Anda.')",
    "__('Unsaved changes')" => "__('Perubahan belum disimpan')",
    "__('You have unsaved changes.')" => "__('Anda memiliki perubahan yang belum disimpan.')",
    "__('Verify')" => "__('Verifikasi')",
    "__('Send Verification Email')" => "__('Kirim Email Verifikasi')",
    "__('A new verification link has been sent to the email address you provided during registration.')" => "__('Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat mendaftar.')",
    "__('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\\'t receive the email, we will gladly send you another.')" => "__('Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi alamat email dengan mengklik tautan yang baru saja kami kirim? Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkannya kembali.')",
    "__('Resend Verification Email')" => "__('Kirim Ulang Email Verifikasi')",
    "__('Secure Area')" => "__('Area Aman')",
    "__('This is a secure area of the application. Please confirm your password before continuing.')" => "__('Ini adalah area aman aplikasi. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.')",
    "__('Confirm')" => "__('Konfirmasi')",
    "__('Reset Password')" => "__('Atur Ulang Kata Sandi')",
    "__('Forgot Password')" => "__('Lupa Kata Sandi')",
    "__('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')" => "__('Lupa kata sandi Anda? Tidak masalah. Beri tahu kami alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.')",
    "__('Email Password Reset Link')" => "__('Kirim Tautan Atur Ulang')",
    "__('Already registered?')" => "__('Sudah terdaftar?')",
    "__('Update your account\\'s appearance settings.')" => "__('Perbarui pengaturan tampilan akun Anda.')",
    "__('Theme')" => "__('Tema')",
    "__('Light')" => "__('Terang')",
    "__('Dark')" => "__('Gelap')",
    "__('System')" => "__('Sistem')",
    "__('Finish enabling two factor authentication.')" => "__('Selesaikan pengaktifan autentikasi dua faktor.')",
    "__('You have enabled two factor authentication.')" => "__('Anda telah mengaktifkan autentikasi dua faktor.')",
    "__('You have not enabled two factor authentication.')" => "__('Anda belum mengaktifkan autentikasi dua faktor.')",
    "__('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\\'s Google Authenticator application.')" => "__('Saat autentikasi dua langkah diaktifkan, Anda akan diminta kode aman dan acak saat masuk. Anda dapat mengambil kode ini dari aplikasi Google Authenticator di ponsel Anda.')",
    "__('To finish enabling two factor authentication, scan the following QR code using your phone\\'s authenticator application or enter the setup key and provide the generated OTP code.')" => "__('Untuk menyelesaikan pengaturan autentikasi dua langkah, pindai kode QR berikut atau masukkan kunci pengaturan dan berikan kode OTP.')",
    "__('Two factor authentication is now enabled. Scan the following QR code using your phone\\'s authenticator application or enter the setup key.')" => "__('Autentikasi dua langkah sekarang diaktifkan. Pindai kode QR berikut menggunakan aplikasi Authenticator ponsel Anda.')",
    "__('Setup Key')" => "__('Kunci Pengaturan')",
    "__('Code')" => "__('Kode')",
    "__('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.')" => "__('Simpan kode pemulihan ini di manajer kata sandi yang aman. Kode dapat digunkan untuk memulihkan akses ke akun jika perangkat autentikasi Anda hilang.')",
    "__('Enable')" => "__('Aktifkan')",
    "__('Regenerate Recovery Codes')" => "__('Buat Ulang Kode Pemulihan')",
    "__('Show Recovery Codes')" => "__('Tampilkan Kode Pemulihan')",
    "__('Cancel')" => "__('Batal')",
    "__('Disable')" => "__('Nonaktifkan')",
    "__('Please confirm access to your account by entering the authentication code provided by your authenticator application.')" => "__('Harap konfirmasi akses ke akun Anda dengan memasukkan kode autentikasi yang diberikan oleh aplikasi Anda.')",
    "__('Please confirm access to your account by entering one of your emergency recovery codes.')" => "__('Harap konfirmasi akses ke akun Anda dengan memasukkan salah satu kode pemulihan darurat Anda.')",
    "__('Use a recovery code')" => "__('Gunakan kode pemulihan')",
    "__('Use an authentication code')" => "__('Gunakan kode autentikasi')",
    "__('Recovery Code')" => "__('Kode Pemulihan')"
];

$count = 0;
foreach($files as $file) {
    if (is_array($file)) { $file = $file[0]; }
    $content = file_get_contents($file);
    $newContent = str_replace(array_keys($translations), array_values($translations), $content);
    
    // Custom replacements for welcome.blade.php
    if (strpos($file, 'welcome.blade.php') !== false) {
        $newContent = str_replace(
            ["Let's get started", "Laravel has an incredibly rich ecosystem. <br>We suggest starting with the following.", "Read the", ">Documentation<", "Watch video tutorials at", ">Laracasts<", "Deploy now"],
            ["Mari Memulai", "Aplikasi Sistem Angkringan siap untuk dikembangkan. <br>Berikut adalah petunjuk dokumentasinya.", "Baca", ">Dokumentasi<", "Tonton tutorial video di", ">Laracasts<", "Mulai Deploy"],
            $newContent
        );
    }
    
    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        $count++;
    }
}
echo "Replaced text in $count files.\n";

?>