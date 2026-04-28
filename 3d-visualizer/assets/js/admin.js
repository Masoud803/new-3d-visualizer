jQuery(document).ready(function($){
    var mediaUploader;

    // Default BG Uploader
    $('.tdv-upload-btn').on('click', function(e) {
        e.preventDefault();
        var targetInput = $('#' + $(this).data('target'));

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: { text: 'Choose Image' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            targetInput.val(attachment.url);
        });

        mediaUploader.open();
    });

    // Model Specific Uploaders (Delegated)
    $('#tdv_models_container').on('click', '.tdv-upload-btn-model', function(e){
        e.preventDefault();
        var index = $(this).data('index');
        var input = $('.tdv-model-file[data-index="'+index+'"]');

        var modelUploader = wp.media({
            title: 'Choose GLB/GLTF File',
            button: { text: 'Choose File' },
            multiple: false,
            library: {
                type: ['model/gltf-binary', 'model/gltf+json', 'application/octet-stream']
            }
        });

        modelUploader.on('select', function() {
            var attachment = modelUploader.state().get('selection').first().toJSON();
            input.val(attachment.url).trigger('change');
        });
        modelUploader.open();
    });

    $('#tdv_models_container').on('click', '.tdv-upload-btn-bg', function(e){
        e.preventDefault();
        var index = $(this).data('index');
        var input = $('.tdv-model-bg[data-index="'+index+'"]');

        var bgUploader = wp.media({
            title: 'Choose Background Image',
            button: { text: 'Choose Image' },
            multiple: false,
            library: { type: 'image' }
        });

        bgUploader.on('select', function() {
            var attachment = bgUploader.state().get('selection').first().toJSON();
            input.val(attachment.url).trigger('change');
        });
        bgUploader.open();
    });
});
