<script src="{{ $path_presenter::backend_assets('tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
    tinymce.init({
        selector: ".tinymce",
        min_height: 300,
        language: "zh_TW",
        relative_urls: false,

        {{-- 是否驗證 html --}}
        @if (!config('site.editor_verify_html', true))
            verify_html : false,
        @endif

        {{-- 是否使用自訂樣式 --}}
        @if (config('site.editor_style', false))
            content_css : ["{{ implode('","', config('site.editor_style')) }}"],
        @endif
        forced_root_block: "",
        image_dimensions: false,
        font_formats : "Andale Mono=andale mono,times;"+
            "Arial=arial,helvetica,sans-serif;"+
            "Arial Black=arial black,avant garde;"+
            "Book Antiqua=book antiqua,palatino;"+
            "Comic Sans MS=comic sans ms,sans-serif;"+
            "Courier New=courier new,courier;"+
            "Georgia=georgia,palatino;"+
            "Helvetica=helvetica;"+
            "Impact=impact,chicago;"+
            "Symbol=symbol;"+
            "Tahoma=tahoma,arial,helvetica,sans-serif;"+
            "Terminal=terminal,monaco;"+
            "Times New Roman=times new roman,times;"+
            "Trebuchet MS=trebuchet ms,geneva;"+
            "Verdana=verdana,geneva;"+
            "Webdings=webdings;"+
            "Wingdings=wingdings,zapf dingbats",

        {{-- 是否使用樣版 --}}
        @if (config('site.editor_template_method', false))
            templates: "{{ url(config('backend.uri') . '/' . config('site.editor_template_method')) }}",
        @endif
        plugins: [
             "advlist autolink link image imagetools lists charmap print preview hr anchor pagebreak",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table colorpicker contextmenu directionality emoticons paste textcolor filemanager {{ config('site.editor_template_method', false) ? 'template' : '' }}"
        ],
        toolbar: "undo redo rotateleft | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | fontselect fontsizeselect | link table insertfile image media template | print preview fullpage emoticons filemanager",
        external_filemanager_path:"/{{ config('site.url_prefix') . $path_presenter::$assets_path . '/' . config('backend.view_path') }}/tinymce/plugins/filemanager/",
        filemanager_title:"檔案管理" ,
        external_plugins: { "filemanager" : "{{ $path_presenter::backend_assets('tinymce/plugins/filemanager/plugin.min.js') }}"}
    });
</script>
