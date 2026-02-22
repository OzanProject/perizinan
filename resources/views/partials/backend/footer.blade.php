<!-- Footer -->
<footer class="main-footer">
  <strong>{!! Auth::user()->dinas->footer_text ?? 'Copyright &copy; ' . date('Y') . ' <a href="#">Dinas Pendidikan</a>. All rights reserved.' !!}</strong>
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 1.0.0
  </div>
</footer>