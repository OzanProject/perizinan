<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use App\Models\CetakPreset;
use App\Services\CertificateTemplateService;
use App\Services\TemplateSanitizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisPerizinanTemplateController extends Controller
{
    public function edit(JenisPerizinan $jenisPerizinan)
    {
        $presets = CertificateTemplateService::getPresets();
        $dinas = Auth::user()->dinas;
        $logoUrl = $dinas && $dinas->logo ? asset('storage/' . $dinas->logo) : null;
        $watermarkUrl = $dinas && $dinas->watermark_img ? asset('storage/' . $dinas->watermark_img) : $logoUrl;
        $frameUrl = $dinas && $dinas->watermark_border_img ? asset('storage/' . $dinas->watermark_border_img) : null;
        $watermarkEnabled = $dinas->watermark_enabled ?? true;
        $watermarkOpacity = $dinas->watermark_opacity ?? 0.1;

        $activePreset = CetakPreset::where('dinas_id', $dinas->id)
            ->where('is_active', true)
            ->first();

        // Siapkan HTML dengan logo yang sudah dirender (jika ada [LOGO_DINAS])
        $templateHtml = $jenisPerizinan->template_html ?? '';
        if ($logoUrl) {
            $templateHtml = str_replace(
                '[LOGO_DINAS]', 
                '<img src="' . $logoUrl . '" data-logo="1" style="width:75px; height:auto; display:inline-block;" contenteditable="false">', 
                $templateHtml
            );
        }
        $jenisPerizinan->template_html = $templateHtml;

        return view('backend.super_admin.jenis_perizinan.template', compact(
            'jenisPerizinan',
            'presets',
            'logoUrl',
            'watermarkUrl',
            'frameUrl',
            'watermarkEnabled',
            'watermarkOpacity',
            'activePreset',
            'dinas'
        ));
    }

    public function update(Request $request, JenisPerizinan $jenisPerizinan, TemplateSanitizationService $sanitizationService)
    {
        $request->validate([
            'template_html' => 'required|string',
        ]);

        try {
            $jenisPerizinan->validateTemplate($request->template_html);

            $dinas = Auth::user()->dinas;
            $html = $sanitizationService->execute($request->template_html, $dinas);

            $dataToUpdate = [
                'template_html' => $html,
                'use_border' => $request->has('use_border') ? ($request->use_border == '1') : false,
                'border_type' => $request->border_type,
                'paper_size' => $request->paper_size,
                'orientation' => $request->orientation,
                'use_watermark' => $request->has('use_watermark') ? ($request->use_watermark == '1') : false,
            ];

            if ($request->hasFile('template_word')) {
                $file = $request->file('template_word');
                $filename = 'word_template_' . $jenisPerizinan->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('templates/word', $filename, 'public');
                $dataToUpdate['template_word_path'] = $path;
            }

            $jenisPerizinan->update($dataToUpdate);

            return redirect()->route('super_admin.jenis_perizinan.index')
                ->with('success', 'Template sertifikat berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
