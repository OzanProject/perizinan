const TemplateEditor = (function() {
    let editorInstance = null;
    let config = {};

    function init(setupConfig) {
        config = setupConfig || window.TemplateEditorConfig;
        
        // Auto-close sidebar
        $('body').addClass('sidebar-collapse');

        initTinyMCE();
        bindEvents();
        initDefaultFrame();
    }

    function initTinyMCE() {
        tinymce.init({
            selector: '#editor-canvas',
            inline: true,
            fixed_toolbar_container: '#toolbar-container',
            toolbar_persist: true,

            plugins: 'advlist autolink lists link charmap preview searchreplace visualblocks code fullscreen table help wordcount directionality nonbreaking paste',

            toolbar: [
                'undo redo | fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat',
                'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | charmap code preview',
                'tableprops tablecellprops | addcolbefore addcolafter deleterow deletecol | tableinsertrowbefore tableinsertrowafter'
            ],

            table_column_resizing: 'resizetable',
            table_resize_bars: true,
            table_default_attributes: { border: '0', style: 'width:100%; border-collapse:collapse;' },
            table_default_styles: { 'width': '100%', 'border-collapse': 'collapse' },
            font_family_formats: 'Times New Roman=times new roman,times,serif; Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; Tahoma=tahoma,arial,helvetica,sans-serif;',
            font_size_formats: '8pt 10pt 11pt 12pt 14pt 18pt 24pt 36pt 48pt',
            menubar: false,

            content_style: "body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.15; } .var-badge { display: inline-block; padding: 1px 4px; font-weight: bold; color: #004085; background: #cce5ff; border: 1px solid #b8daff; border-radius: 3px; font-family: monospace; }",

            paste_as_text: false,
            paste_data_images: false,
            paste_webkit_styles: "none",
            paste_merge_formats: true,

            convert_urls: false,
            relative_urls: false,
            remove_script_host: false,

            extended_valid_elements: 'img[src|alt|style|width|height|contenteditable|data-logo|data-mce-src]',

            setup: function(editor) {
                editorInstance = editor;
                editor.on('init', function() {
                    const currentData = editor.getContent();
                    editor.setContent(processVariablesForView(currentData));
                    updatePaperSize();
                });
            }
        });
    }

    function bindEvents() {
        // Toggle Variable Panel
        const togglePanelBtn = document.getElementById('toggle-var-panel');
        if (togglePanelBtn) {
            togglePanelBtn.addEventListener('click', toggleVariablePanel);
        }

        // Paper Size & Orientation change
        const sizeSelector = document.getElementById('paper-size-selector');
        const orientationSelector = document.getElementById('paper-orientation');
        if (sizeSelector) sizeSelector.addEventListener('change', updatePaperSize);
        if (orientationSelector) orientationSelector.addEventListener('change', updatePaperSize);

        // Border Checkbox
        const borderCheckbox = document.getElementById('use-border-checkbox');
        if (borderCheckbox) {
            borderCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                const overlay = document.getElementById('frame-overlay');
                const frameGroup = document.getElementById('frame-selector-group');
                const borderInput = document.getElementById('use-border-input');

                if (overlay) overlay.style.display = isChecked ? 'block' : 'none';
                if (frameGroup) frameGroup.style.display = isChecked ? 'flex' : 'none';
                if (borderInput) borderInput.value = isChecked ? '1' : '0';
            });
        }

        // Watermark Checkbox
        const watermarkCheckbox = document.getElementById('use-watermark-checkbox');
        if (watermarkCheckbox) {
            watermarkCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                const overlay = document.getElementById('watermark-overlay');
                const watermarkInput = document.getElementById('use-watermark-input');

                if (overlay) overlay.style.display = isChecked ? 'block' : 'none';
                if (watermarkInput) watermarkInput.value = isChecked ? '1' : '0';
            });
        }

        // Frame Type Buttons
        document.querySelectorAll('.btn-change-frame').forEach(btn => {
            btn.addEventListener('click', function() {
                changeFrame(this.dataset.frameType);
            });
        });

        // Search Variables
        const searchInput = document.getElementById('search-var');
        if (searchInput) {
            searchInput.addEventListener('input', e => {
                const term = e.target.value.toLowerCase();
                document.querySelectorAll('.var-btn').forEach(btn => {
                    btn.style.display = btn.innerText.toLowerCase().includes(term) ? 'inline-block' : 'none';
                });
            });
        }

        // Insert Variables
        document.querySelectorAll('.var-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const varData = this.dataset.insertVar;
                if (varData === 'GARIS_KOP') {
                    insertGarisKop();
                } else if (varData) {
                    insertVar(varData);
                }
            });
        });

        // Submit Action
        const btnSubmit = document.getElementById('btn-submit-template');
        if (btnSubmit) {
            btnSubmit.addEventListener('click', submitTemplate);
        }
    }

    function openPresetModal() {
        if (typeof $ === 'undefined') {
            alert('jQuery belum termuat, tidak bisa membuka modal.');
            return;
        }
        $('#presetModal').modal('show');
    }

    function toggleVariablePanel() {
        $('#variable-content').slideToggle('fast', function() {
            const icon = $('#icon-toggle-var');
            if ($(this).is(':visible')) {
                icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
            } else {
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }
        });
    }

    function updatePaperSize() {
        const sizeSelector = document.getElementById('paper-size-selector');
        const orientationSelector = document.getElementById('paper-orientation');
        if (!sizeSelector || !orientationSelector) return;

        const size = sizeSelector.value.toLowerCase();
        const orient = orientationSelector.value;
        const canvas = document.getElementById('editor-canvas');

        canvas.classList.remove('paper-a4-portrait', 'paper-a4-landscape', 'paper-f4-portrait', 'paper-f4-landscape');
        canvas.classList.add(`paper-${size}-${orient}`);

        const sizeInput = document.getElementById('paper-size-input');
        const orientInput = document.getElementById('orientation-input');
        if (sizeInput) sizeInput.value = size;
        if (orientInput) orientInput.value = orient;
    }

    function changeFrame(type) {
        const overlay = document.getElementById('frame-overlay');
        const borderTypeInput = document.getElementById('border-type-input');

        if (overlay && config.framePaths && config.framePaths[type]) {
            overlay.style.backgroundImage = `url('${config.framePaths[type]}')`;
            if (borderTypeInput) borderTypeInput.value = type;

            ['default', 'paud', 'lkp'].forEach(t => {
                const btn = document.getElementById(`btn-frame-${t}`);
                if (btn) {
                    if (t === type) {
                        btn.classList.add('active');
                        if (t === 'default') btn.className = 'btn btn-secondary font-weight-bold active btn-change-frame';
                        if (t === 'paud') btn.className = 'btn btn-primary font-weight-bold active btn-change-frame';
                        if (t === 'lkp') btn.className = 'btn btn-info font-weight-bold active btn-change-frame';
                    } else {
                        btn.classList.remove('active');
                        if (t === 'default') btn.className = 'btn btn-outline-secondary font-weight-bold btn-change-frame';
                        if (t === 'paud') btn.className = 'btn btn-outline-primary font-weight-bold btn-change-frame';
                        if (t === 'lkp') btn.className = 'btn btn-outline-info font-weight-bold btn-change-frame';
                    }
                }
            });
        }
    }

    function initDefaultFrame() {
        const savedType = config.savedBorderType;
        if (savedType) {
            changeFrame(savedType);
        } else {
            const namaIzin = config.namaIzin;
            if (namaIzin.includes('paud') || namaIzin.includes('tk')) {
                changeFrame('paud');
            } else if (namaIzin.includes('lkp')) {
                changeFrame('lkp');
            } else {
                changeFrame('default');
            }
        }
    }

    function applyPreset(key) {
        if (!config || !config.presets) {
            // Coba ambil ulang config kalau belum ada
            config = window.TemplateEditorConfig;
            if (!config || !config.presets) {
                alert('Konfigurasi preset tidak ditemukan.');
                return;
            }
        }

        const preset = config.presets[key];
        if (!preset) {
            alert('Data preset tidak ditemukan untuk key: ' + key);
            return;
        }

        if (!editorInstance) {
            alert('Editor TinyMCE belum siap atau gagal dimuat. Silakan tunggu beberapa saat atau refresh halaman.');
            return;
        }

        if (confirm(`Apakah Anda yakin ingin mengganti desain saat ini dengan preset "${preset.name}"? Semua teks manual yang belum disimpan akan terganti.`)) {
            try {
                editorInstance.setContent(processVariablesForView(preset.html));


            const sizeSelector = document.getElementById('paper-size-selector');
            const orientationSelector = document.getElementById('paper-orientation');
            
            if (sizeSelector) sizeSelector.value = preset.paper_size || 'F4';
            if (orientationSelector) orientationSelector.value = preset.orientation || 'portrait';
            updatePaperSize();

            const borderCb = document.getElementById('use-border-checkbox');
            const borderInput = document.getElementById('use-border-input');
            const frameOl = document.getElementById('frame-overlay');

            if (preset.use_border) {
                if (borderCb) borderCb.checked = true;
                if (borderInput) borderInput.value = '1';
                if (frameOl) frameOl.style.display = 'block';
            } else {
                if (borderCb) borderCb.checked = false;
                if (borderInput) borderInput.value = '0';
                if (frameOl) frameOl.style.display = 'none';
            }

            if (typeof $ !== 'undefined') {
                $('#presetModal').modal('hide');
            }
            
            alert('Preset berhasil diaplikasikan!');
        } catch (e) {
            alert('Terjadi kesalahan saat mengaplikasikan preset: ' + e.message);
        }
        }
    }

    function insertVar(val) {
        if (!editorInstance) return;
        editorInstance.execCommand('mceInsertContent', false, `<span class="var-badge" contenteditable="false">${val}</span>&nbsp;`);
    }

    function insertGarisKop() {
        if (!editorInstance) return;
        const htmlLined = '<hr style="border: none; border-top: 3px solid black; border-bottom: 1px solid black; height: 5px; background: transparent; margin: 10px 0;">';
        editorInstance.execCommand('mceInsertContent', false, htmlLined);
    }

    function processVariablesForView(html) {
        if (!html) return '';
        if (config.logoUrl) {
            html = html.replace(/\[LOGO_DINAS\]/g, `<img src="${config.logoUrl}" data-logo="1" style="width:75px; height:auto; display:inline-block;" contenteditable="false">`);
        }
        return html.replace(/\[([A-Z0-9_:]+)\]/g, function(match, p1) {
            if (p1 === 'LOGO_DINAS') return match;
            return `<span class="var-badge" contenteditable="false">[${p1}]</span>`;
        });
    }

    function revertVariablesForSave(html) {
        if (!html) return '';
        if (config.logoUrl) {
            html = html.replace(/<img[^>]*data-logo=["']?1["']?[^>]*>/gi, '[LOGO_DINAS]');
            const escapedUrl = config.logoUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`<img[^>]*src=["']${escapedUrl}["'][^>]*>`, 'gi');
            html = html.replace(regex, '[LOGO_DINAS]');
        }
        html = html.replace(/<span[^>]*class="[^"]*var-badge[^"]*"[^>]*>\[([A-Z0-9_:]+)\]<\/span>/g, '[$1]');
        return html;
    }

    function submitTemplate() {
        if (!editorInstance) return;
        const input = document.getElementById('template-input');
        const form = document.getElementById('template-form');
        
        if (input && form) {
            input.value = revertVariablesForSave(editorInstance.getContent());
            form.submit();
        }
    }

    // Public API
    return {
        init: init,
        openPresetModal: openPresetModal,
        applyPreset: applyPreset
    };

})();

// Run init immediately (safe because script is pushed at the end of body)
// Fallback to DOMContentLoaded just in case
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', TemplateEditor.init);
} else {
    TemplateEditor.init();
}
