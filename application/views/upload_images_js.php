<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.uploadify.min.js"></script>
<script type='text/javascript' >
	$(function() {
		$('#upload_btn').uploadify({
			'debug'   : false,

			'swf'   : '<?=site_url('assets/uploadify.swf')?>',
			'uploader'  : '<?=site_url('images/upload/submit')?>',
			'cancelImage' : '/assets/img/uploadify-cancel.png',
			'buttonText' : "Upload Files",
			'buttonImage' : '<?=site_url('assets/upload_button.png')?>',
			'height' : 41,
			'width' : 288,
			'multi'   : true,
			'auto'   : true,
			'queueID' : 'hidden-queue',

			'overrideEvents' : ['onUploadProgress, onSelect, onUploadStart', 'onUploadComplete'],

			'fileTypeExts' : '*.png; *.jpg; *.gif',
			'fileTypeDesc' : 'Image Files',

			'formData'  : { 'sessdata' : '<?=$this->session->get_encrypted_sessdata()?>' },
			'method'  : 'post',
			'fileObjName' : 'userfile',

			'queueSizeLimit': 99,
			'simUploadLimit': 1,
			'fileSizeLimit'  : '10MB',

			'onSelect' : function(file) {
				$(".upload-queue").append("<div id='" + file.id + "mt' class='image-container'><div class='thumbnail-container'><img class='placeholder' src=\'<?php echo base_url() . 'assets/new_picture.png'; ?>\' /></div><p class='file-name'>" + file.name + "</p> <div class='progress-bar'><div class='progress'></div></div> </div>");
			},

			'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {

				if (bytesUploaded != 0) {
					var percentage = bytesUploaded / bytesTotal;
					var progress_width = percentage * 170;
					var file_id = '#' + file.id + 'mt';
					$(file_id).children(".progress-bar").children(".progress").css({"width" : progress_width + "px"});
				}

				
			},

			'onUploadStart' : function(file) {
	            
	        },

	        'onUploadSuccess' : function(file, data, response) {

	        	var file_id = '#' + file.id + 'mt';

	        	$(file_id).children(".thumbnail-container").children("img").attr("src", data);
	        	$(file_id).children(".thumbnail-container").children("img").removeClass("placeholder");

	        }


        	
		});

	});
</script>