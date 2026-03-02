<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Mail\ResetPasswordOtp;
use App\Helpers\MailConfigHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
  public function showLinkRequestForm()
  {
    return view('auth.forgot-password');
  }

  public function sendOtp(Request $request)
  {
    $request->validate(['email' => 'required|email|exists:users,email']);

    $user = User::where('email', $request->email)->first();
    $otp = sprintf("%06d", mt_rand(1, 999999));

    // Delete old OTPs for this email
    PasswordResetOtp::where('email', $request->email)->delete();

    // Create new OTP
    PasswordResetOtp::create([
      'email' => $request->email,
      'otp' => $otp,
      'expires_at' => Carbon::now()->addMinutes(15),
    ]);

    // Send Email
    try {
      $dinas = \App\Models\Dinas::first(); // Assuming global settings from first dinas
      MailConfigHelper::set($dinas);
      Mail::to($request->email)->send(new ResetPasswordOtp($otp));
    } catch (\Exception $e) {
      return back()->withErrors(['email' => 'Gagal mengirim email OTP: ' . $e->getMessage()]);
    }

    return redirect()->route('password.otp.verify', ['email' => $request->email])
      ->with('success', 'Kode OTP telah dikirim ke email Anda.');
  }

  public function showVerifyForm(Request $request)
  {
    return view('auth.verify-otp', ['email' => $request->email]);
  }

  public function verifyOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email',
      'otp' => 'required|string|size:6',
    ]);

    $otpRecord = PasswordResetOtp::where('email', $request->email)
      ->where('otp', $request->otp)
      ->first();

    if (!$otpRecord || $otpRecord->isExpired()) {
      return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
    }

    // Generate a temporary signed URL for the reset form to ensure security
    $resetUrl = URL::temporarySignedRoute(
      'password.reset.form',
      now()->addMinutes(30),
      ['email' => $request->email]
    );

    // Delete OTP after successful verification
    $otpRecord->delete();

    return redirect($resetUrl);
  }

  public function showResetForm(Request $request)
  {
    if (!$request->hasValidSignature()) {
      abort(403, 'Tautan reset password sudah kadaluarsa atau tidak valid.');
    }

    return view('auth.reset-password', ['email' => $request->email]);
  }

  public function reset(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users,email',
      'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->route('login')->with('success', 'Password berhasil diatur ulang. Silakan login.');
  }
}
