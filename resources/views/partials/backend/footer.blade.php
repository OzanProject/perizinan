<!-- Footer -->
<footer
  class="mt-8 text-center sm:text-left text-sm text-gray-500 pb-4 border-t border-gray-200 pt-4 flex flex-col sm:flex-row justify-between">
  <div>
    <strong>{!! Auth::user()->dinas->footer_text ?? 'Copyright &copy; ' . date('Y') . ' <a class="text-primary hover:underline" href="#">Dinas Pendidikan</a>. All rights reserved.' !!}</strong>
  </div>
  <div class="hidden sm:block">
    <b>Version</b> 1.0.0
  </div>
</footer>