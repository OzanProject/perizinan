<section class="space-y-6">
    <div class="form-group mb-4">
        <p class="text-sm text-danger font-italic mb-3">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda pertahankan.') }}
        </p>
    </div>

    <button type="button" class="btn btn-danger shadow-sm px-4" data-toggle="modal"
        data-target="#confirmUserDeletionModal">
        <i class="fas fa-user-times mr-1"></i> {{ __('Hapus Akun') }}
    </button>

    <!-- AdminLTE / Bootstrap Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteAccountModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0" style="border-radius: 1rem;">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold text-dark" id="deleteAccountModalLabel">
                            {{ __('Konfirmasi Penghapusan Akun') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body py-4">
                        <p class="text-muted small mb-4">
                            {{ __('Apakah Anda yakin ingin menghapus akun Anda? Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                        </p>

                        <div class="form-group">
                            <label for="password" class="sr-only">{{ __('Kata Sandi') }}</label>
                            <input id="password" name="password" type="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="{{ __('Masukkan Kata Sandi Anda') }}" required>
                            @error('password', 'userDeletion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light px-4" data-dismiss="modal">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn btn-danger px-4 shadow-sm">
                            <i class="fas fa-trash mr-1"></i> {{ __('Hapus Akun Permanen') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
        @push('scripts')
            <script>
                $(document).ready(function () {
                    $('#confirmUserDeletionModal').modal('show');
                });
            </script>
        @endpush
    @endif
</section>