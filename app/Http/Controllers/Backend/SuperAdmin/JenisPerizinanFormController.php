<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\JenisPerizinan;
use Illuminate\Http\Request;

class JenisPerizinanFormController extends Controller
{
    public function edit(JenisPerizinan $jenisPerizinan)
    {
        // Auto-extract [DATA:KEY] from template_html
        $extractedKeys = [];
        if (!empty($jenisPerizinan->template_html)) {
            // Regex to match [DATA:KEY] where KEY can be letters, numbers, underscores
            preg_match_all('/\[DATA:([A-Za-z0-9_]+)\]/', $jenisPerizinan->template_html, $matches);
            if (!empty($matches[1])) {
                // Get unique keys, lowercased
                $extractedKeys = array_unique(array_map('strtolower', $matches[1]));
            }
        }
        
        $currentConfig = $jenisPerizinan->form_config ?? [];
        $currentKeys = array_column($currentConfig, 'name');
        
        // Find which extracted keys are missing from current config
        $missingKeys = array_diff($extractedKeys, $currentKeys);
        
        $suggestedFields = [];
        foreach ($missingKeys as $key) {
            $suggestedFields[] = [
                'name' => $key,
                'label' => ucwords(str_replace('_', ' ', $key)),
                'type' => 'text',
                'required' => '1',
            ];
        }

        return view('backend.super_admin.jenis_perizinan.form', compact('jenisPerizinan', 'suggestedFields'));
    }

    public function update(Request $request, JenisPerizinan $jenisPerizinan)
    {
        // If there are no fields submitted, default to empty array
        $fields = $request->input('fields', []);
        
        if (!empty($fields)) {
            $request->validate([
                'fields' => 'required|array',
                'fields.*.name' => 'required|string|alpha_dash',
                'fields.*.label' => 'required|string',
                'fields.*.type' => 'required|in:text,number,date,textarea',
            ]);
        }

        $jenisPerizinan->update([
            'form_config' => $fields,
        ]);

        return redirect()->route('super_admin.jenis_perizinan.index')
            ->with('success', 'Konfigurasi formulir berhasil diperbarui.');
    }
}
