<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.uploadify.min.js"></script>
<script type='text/javascript' >
	$(function() {
		$('#upload_btn').uploadify({
			'debug'   : false,

			'swf'   : '<?=site_url('assets/uploadify.swf')?>',
			'uploader'  : '<?=site_url('images/upload/submit')?>',
			'cancelImage' : '/assets/img/uploadify-cancel.png',
			'queueID'  : 'file-queue',
			'buttonText' : "Upload Files",
			'buttonImage' : '<?=site_url('assets/upload_button.png')?>',
			'height' : 41,
			'width' : 288,
			'multi'   : true,
			'auto'   : true,

			'fileTypeExts' : '*.png; *.jpg; *.gif',
			'fileTypeDesc' : 'Image Files',

			'formData'  : { 'sessdata' : '<?=$this->session->get_encrypted_sessdata()?>' },
			'method'  : 'post',
			'fileObjName' : 'userfile',

			'queueSizeLimit': 99,
			'simUploadLimit': 1,
			'fileSizeLimit'  : '10MB'
		});
	});
</script>