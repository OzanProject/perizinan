<x-mail::message>
  # Permintaan Atur Ulang Kata Sandi

  Halo,

  Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di **{{ config('app.name') }}**. Gunakan kode OTP
  di bawah ini untuk melanjutkan proses reset password:

  <x-mail::panel>
    ## {{ $otp }}
  </x-mail::panel>

  Kode ini akan kadaluarsa dalam **15 menit**. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.

  Terima kasih,<br>
  Tim IT {{ config('app.name') }}
</x-mail::message>