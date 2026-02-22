<section>
    <div class="form-group mb-4">
        <p class="text-sm text-muted font-italic mb-0">
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </div>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="small font-weight-bold text-uppercase text-muted">{{ __('Nama Lengkap') }}</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="small font-weight-bold text-uppercase text-muted">{{ __('Alamat Email') }}</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="d-flex align-items-center mt-4">
            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                <i class="fas fa-save mr-1"></i> {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <span class="ml-3 text-success font-weight-bold small animate__animated animate__fadeIn">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Berhasil disimpan.') }}
                </span>
            @endif
        </div>
    </form>
</section>