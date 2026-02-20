@if(session('success') || session('error') || session('info') || session('warning'))
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: "{{ session('success') }}",
          timer: 3000,
          showConfirmButton: false
        });
      @endif

      @if(session('error'))
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: "{{ session('error') }}",
        });
      @endif

      @if(session('info'))
        Swal.fire({
          icon: 'info',
          title: 'Informasi',
          text: "{{ session('info') }}",
        });
      @endif

      @if(session('warning'))
        Swal.fire({
          icon: 'warning',
          title: 'Peringatan',
          text: "{{ session('warning') }}",
        });
      @endif
      });
  </script>
@endif