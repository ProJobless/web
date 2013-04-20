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
			'queueID' : 'upload-queue',

			'overrideEvents' : ['onUploadProgress, onSelect, onUploadStart', 'onUploadComplete', 'onSelectError'],

			'fileTypeExts' : '*.png; *.jpg; *.gif',
			'fileTypeDesc' : 'Image Files',

			'formData'  : { 'sessdata' : '<?=$this->session->get_encrypted_sessdata()?>' },
			'method'  : 'post',
			'fileObjName' : 'userfile',

			'queueSizeLimit': 99,
			'simUploadLimit': 1,
			'fileSizeLimit'  : '10MB',

			'itemTemplate' : "<div id='${fileID}' class='image-container'><input class='fileID' type='hidden' name='ids[]' value='${fileID}' /><input class='name' type='hidden' name='names[]' value='${fileName}' /><input class='description' type='hidden' name='descriptions[]' value='' /><input class='tags' type='hidden' name='tags[]' value='' /><input class='file_type' type='hidden' name='file_types[]' value='' /><input class='filename' type='hidden' name='file_names[]' value='' /><input class='thumbnail_name' type='hidden' name='thumbnail_names[]' value='' /><div class='cancel-upload'></div><div class='thumbnail-container'><img class='placeholder' src=\'<?php echo base_url() . 'assets/new_picture.png'; ?>\' /></div><p class='file-name'>${fileName}</p> <div class='progress-bar'><div class='progress'></div></div></div>",

			'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {

				if (bytesUploaded != 0) {
					var percentage = bytesUploaded / bytesTotal;
					var progress_width = percentage * 170;
					var file_id = '#' + file.id;
					$(file_id).children(".progress-bar").children(".progress").css({"width" : progress_width + "px"});
				}

				
			},

	        'onUploadSuccess' : function(file, data, response) {

	        	var response = JSON.parse(data);
	        	console.log(response);

	        	var file_id = '#' + file.id;
	        	$(file_id).children(".thumbnail-container").children("img").attr("src", response.image_source);
	        	$(file_id).children(".thumbnail-container").children("img").removeClass("placeholder");
	        	$(file_id).children(".filename").val(response.image_file_name);
	        	$(file_id).children(".thumbnail_name").val(response.thumbnail_file_name);
	        	$(file_id).children(".file_type").val(response.file_type);

	        },

	        'onQueueComplete' : function(queueData) {

	        	var upload_count = $("#upload-queue").children(".image-container").length;
	        	if(upload_count === 1) {
	        		$(".confirm-upload").val("Upload 1 File");
	        	} else if (upload_count > 1) {
	        		$(".confirm-upload").val("Upload " + upload_count + " Files");
	        	}

	        },

	        'onSelectError' : function(file, errorCode, errorMsg) {

	        	console.log(file);
	        	console.log(errorCode);
	        	console.log(errorMsg);

	        },

	        'onSelect' : function(file) {
	        	if (!$(".post-upload-container").is(":visible")) {
	        		$(".post-upload-container").show();
	        	}
	        }


        	
		});

	});
</script>