jQuery(document).ready(function ($) {
    let cropper, image, modal;

    // Run for all ACF image upload buttons
    $(document).on('click', '.acf-field-image .acf-button[data-name="add"]', function (e) {
        e.preventDefault();

        const $field = $(this).closest('.acf-field-image');
        const width  = parseInt($field.data('width')) || 780;
        const height = parseInt($field.data('height')) || 1080;

        const frame = wp.media({
            title: 'Select or Upload Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();

            // Modal overlay
            $('body').append(`
                <div id="acfCropModal" style="position:fixed;top:0;left:0;width:100%;height:100%;
                    background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:9999;">
                    <div style="background:#fff;padding:20px;max-width:90%;max-height:90%;overflow:auto;">
                        <img id="acfCropImage" src="${attachment.url}" style="max-width:100%;"/>
                        <div style="margin-top:10px;text-align:right;">
                            <button id="acfCropConfirm" class="button button-primary">Crop & Save</button>
                            <button id="acfCropCancel" class="button">Cancel</button>
                        </div>
                    </div>
                </div>
            `);

            image = document.getElementById('acfCropImage');
            cropper = new Cropper(image, {
                aspectRatio: width / height,
                viewMode: 1,
                movable: true,
                zoomable: true,
                cropBoxResizable: false,
                minCropBoxWidth: width,
                minCropBoxHeight: height,
            });

            // Save cropped
            $('#acfCropConfirm').on('click', function () {
                const canvas = cropper.getCroppedCanvas({
                    width: width,
                    height: height,
                });

                canvas.toBlob(function (blob) {
                    const formData = new FormData();
                    formData.append('action', 'acf_cropper_upload');
                    formData.append('nonce', acfCropperAjax.nonce);
                    formData.append('file', blob, 'cropped-' + Date.now() + '.jpg');

                    $.ajax({
                        url: acfCropperAjax.ajax_url,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            if (res.success) {
                                // Update ACF preview + hidden input
                                const newUrl = res.data.url;
                                const newId  = res.data.id;

                                $field.find('img').attr('src', newUrl).show();
                                $field.find('input[type="hidden"]').val(newId);
                                $('#acfCropModal').remove();
                            } else {
                                alert(res.data || 'Upload failed.');
                            }
                        }
                    });
                }, 'image/jpeg');
            });

            $('#acfCropCancel').on('click', function () {
                $('#acfCropModal').remove();
            });
        });

        frame.open();
    });
});
