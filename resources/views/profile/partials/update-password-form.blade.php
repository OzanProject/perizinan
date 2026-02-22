<section>
    <div class="form-group mb-4">
        <p class="text-sm text-muted font-italic mb-0">
            {{ __("Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.") }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password"
                class="small font-weight-bold text-uppercase text-muted">{{ __('Kata Sandi Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password"
                class="small font-weight-bold text-uppercase text-muted">{{ __('Kata Sandi Baru') }}</label>
            <input id="update_password_password" name="password" type="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password">
            @error('password', 'updatePassword')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation"
                class="small font-weight-bold text-uppercase text-muted">{{ __('Konfirmasi Kata Sandi') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="d-flex align-items-center mt-4">
            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                <i class="fas fa-key mr-1"></i> {{ __('Perbarui Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <span class="ml-3 text-success font-weight-bold small animate__animated animate__fadeIn">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Password berhasil diganti.') }}
                </span>
            @endif
        </div>
    </form>
</section>