<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use App\Models\PerizinanDiscussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerizinanDiscussionController extends Controller
{
  public function store(Request $request, Perizinan $perizinan)
  {
    $request->validate([
      'message' => 'required|string',
    ]);

    $perizinan->discussions()->create([
      'user_id' => Auth::id(),
      'message' => $request->message,
    ]);

    return back()->with('success', 'Pesan berhasil dikirim.');
  }
}
