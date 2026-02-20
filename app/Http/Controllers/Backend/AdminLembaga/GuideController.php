<?php

namespace App\Http\Controllers\Backend\AdminLembaga;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuideController extends Controller
{
  /**
   * Tampilkan halaman panduan sistem.
   */
  public function index()
  {
    return view('backend.admin_lembaga.guide.index');
  }
}
