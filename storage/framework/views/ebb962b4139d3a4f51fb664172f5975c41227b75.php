<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond.min.js')); ?>"></script>
<!-- include FilePond plugins -->
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond-plugin-image-preview.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond-plugin-file-validate-type.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond-plugin-image-crop.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond-plugin-image-resize.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond-plugin-image-validate-size.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond-plugin-image-transform.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond-plugin-image-edit.js')); ?>"></script>
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/fr_locale.js')); ?>"></script>
<!-- <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script> -->
<!-- include FilePond jQuery adapter -->
<script src="<?php echo e(asset('admin_assets/custom/filepond/js/filepond.jquery.js')); ?>"></script>
<script>
$(function(){

    FilePond.registerPlugin(
          FilePondPluginImagePreview,
          FilePondPluginFileValidateType,
          FilePondPluginImageCrop,
          FilePondPluginImageResize,
          FilePondPluginImageValidateSize,
          FilePondPluginImageTransform,
          FilePondPluginImageEdit,
    );

    const input = document.querySelector('.filepond_img');
    const pond = FilePond.create(input,{
         
          imagePreviewMaxHeight: 200,
          imagePreviewMaxWidth: 200,
          storeAsFile:true,
          maxFiles:20,
          credits:false,
          allowImageCrop:true,
          allowImageTransform:true,
          // imageCropAspectRatio:'1:1',
          acceptedFileTypes:['image/png', 'image/jpeg','image/jpg'],
          allowImageResize:false,
          // imageResizeTargetWidth:400,
          // imageResizeTargetHeight:400,
          imageResizeUpscale:false,
          // imageValidateSizeMinWidth:300,
          // imageValidateSizeMinHeight:300,     
    });

    const input2 = document.querySelector('.filepond_img2');
    const pond2 = FilePond.create(input2,{
         
          imagePreviewMaxHeight: 200,
          imagePreviewMaxWidth: 200,
          storeAsFile:true,
          maxFiles:20,
          credits:false,
          allowImageCrop:true,
          allowImageTransform:true,
          // imageCropAspectRatio:'1:1',
          acceptedFileTypes:['image/png', 'image/jpeg','image/jpg'],
          allowImageResize:false,
          // imageResizeTargetWidth:400,
          // imageResizeTargetHeight:400,
          imageResizeUpscale:false,
          // imageValidateSizeMinWidth:300,
          // imageValidateSizeMinHeight:300,     
    });

    const input3 = document.querySelector('.filepond_pdf');
    const pond3 = FilePond.create(input3,{
          acceptedFileTypes:['application/pdf'],
          allowPdfPreview: true,
          pdfPreviewHeight: 320,
          pdfComponentExtraParams: 'toolbar=0&view=fit&page=1'     
    });

    const input4 = document.querySelector('.filepond_pdf2');
    const pond4 = FilePond.create(input4,{
          acceptedFileTypes:['application/pdf'],
          allowPdfPreview: true,
          pdfPreviewHeight: 320,
          pdfComponentExtraParams: 'toolbar=0&view=fit&page=1'     
    });

    const input5 = document.querySelector('.filepond_video');
    const pond5 = FilePond.create(input5,{
          acceptedFileTypes:['video/quicktime', 'video/mp4'],     
    });
});
</script><?php /**PATH /opt/lampp/htdocs/ssgc-bulk/resources/views/layouts/admin/elements/filepond_js.blade.php ENDPATH**/ ?>