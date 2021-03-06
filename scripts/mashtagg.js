function positionPopup(e) {
	
	
	var xoffset = ( $(window).width() / 2 ) - ( e.width() / 2);
	
	xoffset = xoffset + 'px';
	
	e.css({'right': xoffset});
	
}



function refresh_page() {}

function username_check() {
	
	var username = $("#username").val().trim();
	var regexp = /^[a-zA-Z0-9-_]{4,32}$/;		
	
	if (username.search(regexp) == -1) {
	
		inputError($(".username"), "The username entered is invalid.  Usernames must be between 4 and 32 characters, with no spaces");
		return false;
	
	} else {
		
		for(i = 0; i < invalid_usernames.length(); i++) {
			if (username == invalid_usernames[i]) {
				inputError($(".username"), "The username entered is already in use");
				return false;
			}
		}

		var postData = { "username" : username };
		
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/username_check",
			data: postData,
			success: function(data) {
				if (data == "exists") {
					inputError($(".username"), "That username is already in use");
					return false;
				}
			}
		});
			
	}
	
	return true;
	
}



function email_check() {
	
	var email = $("#email").val().trim();
	var regexp = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
	
	if(email.search(regexp) == -1) {
		
		inputError($(".email"), "The email entered is invalid.");
		return false;
		
	} else {
		
		var postData = { "email" : email };
		
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/email_check",
			data: postData,
			success: function(data) {
				if (data == "exists") {
					inputError($(".email"), "That email is already in use");
					return false;
				}
			}
		});
	}
	
	return true;
}

function password_check() {
	
	var password = $("#password").val();
		
	for(i = 0; i < invalid_passwords.length(); i++) {
		if (password == invalid_usernames[i]) {
			inputError($(".password"), "Too easy.");
			return false;
		}
	}
	return true;
}

function inputError(element, error_msg) {
	
	element.html(error_msg);
	element.show();
	
}

function hideError(element) {

	element.hide();
	element.html(" ");

}

function tab_change(event, _this) {

	$("#tabs div").removeClass("menu-item");
	$("#tabs div").removeClass("active-menu-item");
	$("#tabs div").addClass("menu-item");
	$("#comments-tab").html("<a href='/' >Comments</a>");
	$("#shares-tab").html("<a href='/' >Shares</a>");
	$("#tags-tab").html("<a href='/' >Tags</a>");
	
	if ($(_this).attr("id") === "comments-tab") {
	
		$("#comments-tab").html("Comments");
		$("#comment-container").show();
		$("#shares-container").hide();
		$("#tags-container").hide();
	
	} else if ($(_this).attr("id") === "shares-tab") {
	
		$("#shares-tab").html("Shares");
		$("#comment-container").hide();
		$("#shares-container").show();
		$("#tags-container").hide();
	
	} else if ($(_this).attr("id") === "tags-tab") {
		
		$("#tags-tab").html("Tags");
		$("#comment-container").hide();
		$("#shares-container").hide();
		$("#tags-container").show();
	
	}
	
	$(_this).removeClass("menu-item");
	$(_this).addClass("active-menu-item");
	
	event.preventDefault();

}

var invalid_usernames = [ "admin", "administrator", "ajax", "api", "home", "login", "logout", "profile", "saved_links", "signup", "spool", "test", "iphone", "android" ];

var invalid_passwords = [ "password", "test", "testing", "stupid", "mashtagg", "123456", "secret" ];

$(document).ready(function() {

	if($("#new_post_textarea").length > 0) CKEDITOR.replace('new_post_textarea', {"height": 552});
	
	if($("#new_post_caption").length > 0) CKEDITOR.replace('new_post_caption');

	$(".active-menu-item").click(function(event) {
	
		tab_change(event, this);
		
	});

	$(".menu-item").click(function(event) {
		
		tab_change(event, this);
	
	});
	
	$(".upvote").click(function() {

		if ($(this).parent().hasClass('root-post') || $(this).parent().hasClass('feed-post')) {
			var influence_gain = parseInt($(this).parent().children(".influence_gain").html());
			if ($(this).parent().children(".clicked_downvote").length) {
				influence_gain = influence_gain + 2;
			} else {
				influence_gain++;
			}
			$(this).parent().children(".influence_gain").html(influence_gain);
			var sid = $(this).closest(".outer-post-container").attr("id");
		} else {
			var influence_gain = parseInt($(this).closest(".outer-comment-container").find(".influence_gain").html());
			if ($(this).parent().children(".clicked_downvote").length) {
				influence_gain = influence_gain + 2;
			} else {
				influence_gain++;
			}
			$(this).closest(".outer-comment-container").find(".influence_gain").html(influence_gain)
			var sid = $(this).closest(".outer-comment-container").attr("id");
		}

		$(this).addClass("clicked_upvote");
		$(this).children("img").attr("src", Mashtagg.base_url + "assets/clicked_arrow_up.png");
		$(this).removeClass("upvote");
		$(this).parent().children(".clicked_downvote").children("img").attr("src", Mashtagg.base_url + "assets/arrow_down.png");
		$(this).parent().children(".clicked_downvote").addClass("downvote");
		$(this).parent().children(".clicked_downvote").removeClass("clicked_downvote");

		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/upvote",
			data: { "sid" : sid },
			success: function(data) {
				
			}
		});
	});
	
	$(".downvote").click(function() {

		if ($(this).parent().hasClass('root-post')) {
			var influence_gain = parseInt($(this).parent().children(".influence_gain").html());
			if ($(this).parent().children(".clicked_upvote").length) {
				influence_gain = influence_gain - 2;
			} else {
				influence_gain--;
			}
			$(this).parent().children(".influence_gain").html(influence_gain);
			var sid = $(this).closest(".outer-post-container").attr("id");
		} else {
			var influence_gain = parseInt($(this).closest(".outer-comment-container").find(".influence_gain").html());
			if ($(this).parent().children(".clicked_upvote").length) {
				influence_gain = influence_gain - 2;
			} else {
				influence_gain--;
			}
			$(this).closest(".outer-comment-container").find(".influence_gain").html(influence_gain)
			var sid = $(this).closest(".outer-comment-container").attr("id");
		}

		$(this).addClass("clicked_downvote");
		$(this).children("img").attr("src", Mashtagg.base_url + "assets/clicked_arrow_down.png");
		$(this).removeClass("downvote");
		$(this).parent().children(".clicked_upvote").children("img").attr("src", Mashtagg.base_url + "assets/arrow_up.png");
		$(this).parent().children(".clicked_upvote").addClass("upvote");
		$(this).parent().children(".clicked_upvote").removeClass("clicked_upvote");
		
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/downvote",
			data: { "sid" : sid },
			success: function(data) {
				
			}
		});
	});

	/******************* Posts page **********************/

	post_fix_heights();

	$('.new_comment').live('click', function() {
	
		var button = $(this);

		if ( button.val() === "Save" ) {

			if (button.hasClass("first_new_comment")) {

				var body = button.parent().siblings(".input_container").children(".new_comment_input").attr("name");

			} else {

				var body = button.closest('.input_container').children(".new_comment_input").attr("name");

			}
		
			
			var input_value = CKEDITOR.instances[body].getData();
			
			if (input_value != "") {

				button.hide();
				button.siblings(".loading-gif").show();
				
				if(button.attr("id") == "first_new_comment") {
					button.val("New Comment");
					button.parent(".add_comment").prev('.input_container').hide();
				} else {
					button.parent().siblings(".new_comment_input").hide();
					button.closest(".input_container").siblings(".reply").hide();
				}

				var postData = {
					
					"body"      : input_value,
					"published" : "true",
					"type"      : "comment",
					"parent"    : $("#parent_comment").val(),
					"root"      : $("#root_comment").val()
				
				};

				if ($(this).parent().parent().parent().hasClass("new-comment-container")) {
					postData['odd'] = true;	
				} else {
					if ($(this).closest(".outer-comment-container").hasClass("odd-comment")) {
						postData['odd'] = false;
					} else {
						postData['odd'] = true;
					}
				}
			
				$.ajax({
					type: "POST",
					url: Mashtagg.base_url + "ajax/add_comment",
					data: postData,
					success: function(data) {

						button.siblings(".loading-gif").hide();
						button.show();
						$(".new_comment_input").val("");
						$("#no-comments-yet").remove();
						if(button.attr("id") == "first_new_comment") {
							button.closest('.new-comment-container').siblings('.new-comment-holder').prepend(data);
							var new_textarea_name = button.closest('.new-comment-container')
								.siblings('.new-comment-holder')
								.children('.outer-comment-container:first')
								.children('.add_comment_container')
								.children('.input_container')
								.children('textarea')
								.attr('name');
						} else {
							button.parent().siblings(".new_comment_input").show();
							button.closest('.input_container').hide();
							button.closest(".input_container").siblings(".reply").show();
							button.closest('.add_comment_container').siblings('.new-comment-holder').prepend(data);
							var new_textarea_name = button.closest('.add_comment_container')
								.siblings('.new-comment-holder')
								.children('.outer-comment-container:first')
								.children('.add_comment_container')
								.children('.input_container')
								.children('textarea')
								.attr('name');
						}
						console.log(new_textarea_name);
						if (typeof(new_textarea_name) != "undefined") {
							CKEDITOR.replace(new_textarea_name);
						}
							
					}
				});

			}
		
		} else {
			$(this).val("Save");
			$("#parent_comment").val($("#root_comment").val());
			$('.input_container').hide();
			button.parent(".add_comment").prev('.input_container').show();
		}
	
	});
	
	$('.reply').live('click', function(event) {
		$("#first_new_comment").val("New Comment");
		var comment_container = $(this).closest(".outer-comment-container");

		var is_hidden = comment_container.children(".add_comment_container").children(".input_container").is(":hidden")
		$('.input_container').hide();

		if (is_hidden) {
			comment_container.children(".add_comment_container").children(".input_container").show();
			$("#parent_comment").val($(this).closest(".outer-comment-container").attr("id"));
		}
		event.preventDefault();
	});
	
	$('#body').focus(function() {
	
		$(this).attr("rows", "8");
	
	});
	
	$('.outer-post-container').mouseover(function() {
	
		$(this).find(".expand-link").show();	
	});
	
	$('.outer-post-container').mouseout(function() {
		
		$(this).find(".expand-link").hide();
		
	});
	
	$('.little-post-container').mouseover(function() {
	
		$(this).find(".little-comments").show();	
	});
	
	$('.little-post-container').mouseout(function() {
		
		$(this).find(".little-comments").hide();
		
	});

	$('.input_container textarea').each(function(i){

		var input_name = $(this).attr("name");
		CKEDITOR.replace(input_name);

	});

	$('.new').click(function(e) {
		positionPopup($('#post-form-container'));
		$('#post-form-container').show();
		e.preventDefault();
	});
	
	$('#close').click(function(e) {
		$('#post-form-container').hide();
		e.preventDefault();
	});
	
	$('#post-submit').click(function() {
		var published;
		
		if ($("#publish").attr('checked')) {
			published = "true";
		} else {
			published = "false";
		}
		
		var postData = {
			            "url"       : $("#url").val(),
		                "title"     : $("#title").val(),
		                "body"      : $("#body").val(),
		                "tags"      : $("#tags").val(),
		                "published" : published,
		                "type"      : $("#type").val(),
		                "parent"    : $("#parent").val(),
		                "root"      : $("#root").val()
		               };
		
		
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/add_post",
			data: postData,
			success: function(data) {
				$('#post-form-container').hide();
				refresh_page();
			}
		});
	});

	$('#username').change(function() {
		
		
		
	});
	
	$('#email').change(function() {
	
		
		
	});
	
	$('.image-preview').hide();

	$(".save-post").live('click', function(){
		if ( $(this).hasClass("comment-save") ) {
			var post_data = {
				"post_id" : $(this).closest(".outer-comment-container").attr("id")
			};
		} else {
			var post_data = {
				"post_id" : $(this).closest(".outer-post-container").attr("id")
			};
		}
		
		if ($(this).hasClass("clicked")) {
			$(this).removeClass("clicked");
			$(this).addClass("unclicked");
			$(this).attr("src", Mashtagg.base_url + 'assets/star_fav_empty.png');

			$.ajax({
				type: "POST",
				url: Mashtagg.base_url + "ajax/unsave_post",
				data: post_data,
				success: function(data) {
					console.log("success!");
				}
			});

		} else {
			$(this).addClass("clicked");
			$(this).removeClass("unclicked");
			$(this).attr("src", Mashtagg.base_url + 'assets/star_fav_full.png');

			$.ajax({
				type: "POST",
				url: Mashtagg.base_url + "ajax/save_post",
				data: post_data,
				success: function(data) {
					console.log("success!");
				}
			});

		}		
		
	});

	/******************* Users page ********************/

	$(".user-search-input").bind('keypress', function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		var query = $(".user-search-input").val();
		if(code == 13 && query != "") {
			var rgx = /[^\w\.@-]/;
			query = query.replace(rgx, "");
			var url = document.URL;
			rgx = /page=[0-9]+&*/;
			url = url.replace(rgx, "");
			rgx = /&*search=[a-zA-Z0-9]+/;
			url = url.replace(rgx, "");
			rgx = /users\?/;
			if (url.match(rgx)) {
				rgx = /users\?./
				if (url.match(rgx)) {
					url = url + "&search=" + query;	
				} else {
					url = url + "search=" + query;
				}
			} else {
				url = url + "?search=" + query;
			}
			window.location = url;
		}
	});

	/******************* Profile Page ********************/



	$("#follow-button").click(function(){
		if($(this).val() == "Follow") {
			var postData = {
				"username" : $("#username").val()
			};
			$.ajax({
				type: "POST",
				url: Mashtagg.base_url + "ajax/follow",
				data: postData
			});
			$(this).val("Unfollow");
		} else {
			var postData = {
				"username" : $("#username").val()
			};
			$.ajax({
				type: "POST",
				url: Mashtagg.base_url + "ajax/unfollow",
				data: postData
			});	
			$(this).val("Follow");		
		}
	});

	var crop_coords = {};

	$("#crop_button").click(function(){
		$("#done_cropping").show();
		$("#profile_avatar").Jcrop({
			onSelect: function(c) {
				crop_coords.x  = c.x;
				crop_coords.y  = c.y;
				crop_coords.x2 = c.x2;
				crop_coords.y2 = c.y2;
				crop_coords.w  = c.w;
				crop_coords.h  = c.h;
			},
            bgColor:     'transparent',
            bgOpacity:   .4,
            setSelect:   [ 0, 0, 100, 100 ],
            aspectRatio: 1,
        }, function(){
        	Jcrop_image = this;
        });
	});

	$("#profile_avatar_img").change(function(){
		$("#hidden-avatar-flag").val("true");
	});

	$("#done_cropping").click(function(){
		$("#profile_avatar_thumbnail").attr("src", $("#profile_avatar").attr("src"));
		

		var img = new Image();
		img.src = $("#profile_avatar").attr("src");

		$("#crop-image-wrapper").css({
			"overflow" : "hidden",
			"background-image" : "none"
		});

		if (img.width > 500) {
			var new_height = ( img.height / img.width ) * 500;
			var new_width = 500;
		} else {
			var new_height = img.height;
			var new_width = img.width;
		}

		var crop_ratio_x = crop_coords.w / new_width;
		var crop_ratio_y = crop_coords.h / new_height;

		var new_cropped_width = 48 / crop_ratio_x;
		var new_cropped_height = 48 / crop_ratio_y;

		var new_cropped_x = (crop_coords.x / new_width) * new_cropped_width;
		var new_cropped_y = (crop_coords.y / new_height) * new_cropped_height;

		var crop_start_x = (crop_coords.x / new_width) * img.width;
		var crop_start_y = (crop_coords.y / new_height) * img.height;

		var crop_end_x = (crop_coords.x2 / new_width) * img.width;
		var crop_end_y = (crop_coords.y2 / new_height) * img.height;

		var crop_width  = crop_end_x - crop_start_x;
		var crop_height = crop_end_y - crop_start_y;


		$("#hidden-thumbnail-coords").val(crop_start_x + "," + crop_start_y + "," + crop_width + "," + crop_height);

		$("#crop-image-wrapper img").css({
			"opacity": "1",
			 "display": "block",
			 "visibility": "visible",
			 "max-width": "none",
			 "max-height": "none",
			 "width": new_cropped_width + "px",
			 "height": new_cropped_height + "px",
			 "image-rendering": "auto",
			 "margin-left": "-" + new_cropped_x + "px",
			 "margin-top" : "-" + new_cropped_y + "px"
		});

		$("#hidden-thumbnail-flag").val("true");
		Jcrop_image.destroy();
		$(this).hide();
	});

	/******************* Login / signup page **********************/

	$(".signup-button").click(function(){
		$(".login-form").fadeOut(250, function() {
			$(".error").remove();
			$(".signup-form").fadeIn(250);
		});
		
	});

	$(".cancel-signup").click(function(){
		$(".signup-form").fadeOut(250, function(){
			$(".error").remove();
			$(".login-form").fadeIn(250);
		});
	});

	$(".forgot_password").click(function(event){
		event.preventDefault();
		$(".login-form").fadeOut(250, function() {
			$(".error").remove();
			$(".forgot-password-form").fadeIn(250);
		});
	});

	$(".cancel-forgot-password").click(function(){
		$(".forgot-password-form").fadeOut(250, function(){
			$("#forgot_password_email").val("");
			$(".login-form").fadeIn(250);
		});
	});

	$("#submit-forgot-password").click(function() {
		var post_data = {
			"email" : $("#forgot_password_email").val(),
		};
		$(".forgot-password-form").hide();
		$(".forgot-password-loading").show();
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "forgot_password/submit",
			data: post_data,
			success: function(data) {
				$("#forgot_password_email").val("");
				$(".forgot-password-loading").hide();
				$(".forgot-password-success").show();
			}
		});
	});

	$(".return-to-login").click(function() {
		$(".forgot-password-success").fadeOut(250, function(){
			$(".login-form").fadeIn(250);
		});
	});

	/******************* Referral page **********************/

	$(".referral-button").click(function(){
		$(".inner-container .header").hide();
		$(".email-container").show();
	});

	$("#cancel_referral").click(function(){
		$(".email-container").hide();
		$(".inner-container .header").show();
		$("#referral_email").val("");
		$(".validation-errors").hide();
		$("#referral_email").css({"border" : "1px solid #AAA"});
	});

	$("#submit_referral").click(function(){
		var email = $("#referral_email").val();
		var referrer = $("#referrer").val();
		if (email != "") {
			if (isRFC822ValidEmail(email)) {
				$(".validation-errors").hide();
				$("#referral_email").css({"border" : "1px solid #AAA"});
				var referral_amount = $("#referral_amount").val();
				var admin_referral_amount = 0;
				if (referral_amount < 5) {
					if (referral_amount >= 0) {
						referral_amount++;
						$("#referral_amount").val(referral_amount);
					} else {
						admin_referral_amount = $("#admin_referral_amount").val();
						admin_referral_amount++;
						$("#admin_referral_amount").val(admin_referral_amount);
					}

					$(".email-container").hide();
					$(".inner-container .header").show();
					$("#referral_email").val("");

					var postData = {
						"referrer" : referrer,
						"email" : email
					};

					$.ajax({
						type: "POST",
						url: Mashtagg.base_url + "refer/create",
						data: postData,
						success: function() {

							$(".referrals").append(
								"<div class='referral-container'>" +
									"<p class='referral'>" + email + "</p>" + 
								"</div>"
							);
						}
					});
					
					if (referral_amount == 5) {
						$(".referral-header").html("<p>You have used up all 5 of your invites.<p>");
					} else if (referral_amount >= 0) {
						$(".referral-header").html("<p>You have sent " + referral_amount + " referrals out, and have " + (5 - referral_amount) + " left to send.</p>");
					} else {
						$(".referral-header").html("<p>You have sent " + admin_referral_amount + " referrals out.</p>");
					}

				}
			} else {
				$(".validation-errors").show();
				$("#referral_email").css({"border" : "1px solid red"});
			}
			
		}
		
	});

	/******************* Blog page **********************/

	$(".expand-link").live('click', function(){
		if ($(this).closest(".outer-post-container").hasClass("expanded")) {
			$(this).children("img").attr("src", Mashtagg.base_url + "assets/arrow_down_3_50p.png");
		 	$(this).closest(".outer-post-container").removeClass("expanded");
			$(".outer-post-container").removeClass("expanded");
			$(".meta-container").hide();
		} else {
			$(".expand-link img").attr("src", Mashtagg.base_url + "assets/arrow_down_3_50p.png");
			$(this).children("img").attr("src", Mashtagg.base_url + "assets/arrow_up_3_50p.png");
		 	$(".outer-post-container").removeClass("expanded");
			$(this).closest(".outer-post-container").addClass("expanded");
			$(".meta-container").hide();
			$(this).siblings(".meta-container").show();
		}
		post_fix_heights();
	});

	/******************* Compose page *********************/

	$("#upload_image").change(function(){
		if (this.files && this.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$(".image-placeholder-container").css({"display" : "inline-block"});
				$(".image-upload-container").hide();
				$(".image-placeholder-container img").attr('src', e.target.result);
				// $(".image-placeholder-container").css({
				// 	"width" : $(".upload-image-preview").width() + "px"
				// });

				console.log($(".upload-image-preview").width());		
			}

			reader.readAsDataURL(this.files[0]);
		}

	});

	$(".cancel-image").click(function(){
		$(".image-placeholder-container").hide();
		$(".image-upload-container").show();
	});

	$('#link').change(function() {

		var link = $('#link').val()
		
		var postData = { "url": link };
		
		var i = 0;

		if (link != "") {

			$('.link-loading-container').show();

			$.ajax({
				type: "POST",
				url: Mashtagg.base_url + "ajax/website_scrape",
				data: postData,
				success: function(json) {

					//Clear out any values that may have been carried over.
					$("#link_url").val("");
					$("#link_title").val("");
					$("#link_description").val("");
					$("#link_media_url").val("");
					$("#link_type").val("");
					$("#link_image").val("");
					$("#link_base_url").val("");
					$("#link_media_height").val("");
					$("#link_media_width").val("");

					$('.link-loading-container').hide();
					$(".link-placeholder-container").css({"display":"inline-block"});
					
					data = JSON.parse(json);
					console.log(data);

					$("#link_url").val(link);
					$("#link_title").val(data.title);
					$("#link_description").val(data.description);
					$("#link_base_url").val(data.base_url);

					if (data.hasOwnProperty("type")) {

						//There are currently 3 types of links that we parse out, 'player', 'image', and 'summary'
						//Player is a video or sound clip
						//Image is an image, ... :O
						//And summary is the summary of a website. So a link to a blog article, or news article or some shit.
						if (data.type == "player") {

							//Do some height/width calculations, we don't want them to be native that youtube or whatever gives us
							//because we want this player to fit nicely in the frame we have.

							var ratio = data.player_height / data.player_width;
							var height = data.player_height;
							var width = data.player_width;

							if (width > 620) {
								width = 620;
								height = ratio * width;
							}

							//Now set the values in the DOM:
							$(".link-media-container").show();
							$(".link-summary-container").hide();
							$("#object_frame").hide();
							$("#player_frame").attr("src", data.player);
							$("#player_frame").attr("height", height);
							$("#player_frame").attr("width", width);
							$("#player_frame").show();
							$(".link-player-container").show();
							$(".image-container").hide();

							$("#link_type").val("player");
							$("#link_media_url").val(data.player);
							$("#link_image").val(data.image);
							$("#link_media_height").val(data.player_height);
							$("#link_media_width").val(data.player_width);

						} else if (data.type == "image") {

							$(".link-media-container").show();
							$(".link-summary-container").hide();
							$(".link-player-container").hide();
							$(".image-container").show();
							$(".image-container img").attr("src", data.image);

							$("#link_type").val("image");
							$("#link_media_url").val(data.player);
							$("#link_image").val(data.image);

						} else {

							$(".link-title .link-title-link").html(data.title);
							$(".link-title .link-title-link").attr("href", link);
							$(".link-media-container").hide();

							$(".link-summary-container").show();
							if (data.hasOwnProperty("image") && data.image != "") {
								$(".image-container").show();
								$(".image-container img").attr("src", data.image);	
							} else {
								$(".image-container").hide();
							}
							$(".text-container .link-description").html(data.description);

							$("#link_type").val("summary");
							$("#link_image").val(data.image);

						}

					} else {

						if (data.hasOwnProperty("error")) {
							$(".image-container img").attr("src", "");
							$(".image-container").hide();
							$(".link-title .link-title-link").html("");
							$(".link-media-container").hide();
							$(".link-summary-container").show();
							$(".text-container .link-description").html("There was an error retrieving the link given.");
						}

					}


				}
			});

		} else {

			$("#link_url").val("");
			$("#link_title").val("");
			$("#link_description").val("");
			$("#link_media_url").val("");
			$("#link_type").val("");
			$("#link_image").val("");
			$("#link_base_url").val("");
			$("#link_media_height").val("");
			$("#link_media_width").val("");
			$(".link-placeholder-container").hide();

		}
		
		
		
	});

	$(".cancel-link").click(function(){
		$(".link-placeholder-container").hide();
		$("#link").val("");

	});

	$(".navigation-outline").height("800px");

	//Image upload app events:

	$(".upload-button").click(function() {
		window.location.hash = "#upload";
		$(".upload-queue").show();
		$(".upload-config").show();
		$(".modal-screen").show();
	});

	$(window).on('hashchange', function() {
		if (window.location.hash === "") {
			$(".upload-queue").hide();
			$(".upload-config").hide();
			$(".modal-screen").hide();	
		}
	});

	$("#picture_name").click(function(){
		$(this).hide();
		$("#picture_name_input").show();
		$("#picture_name_input").val($(this).html());
		$("#picture_name_input").select();
	});
	$("#picture_description").click(function(){
		$(this).hide();
		$("#picture_description_input").show();
		$("#picture_description_input").val($(this).html());
		$("#picture_description_input").select();
	});

	$("#picture_name_input").blur(function(){
		$(this).hide();
		$("#picture_name").show();
		image_value_relay($(this), $("#picture_name"), ".name");
	});

	$("#picture_description_input").blur(function(){
		$(this).hide();
		$("#picture_description").show();
		image_value_relay($(this), $("#picture_description"), ".description");
	});

	$("#picture_name_input").keydown(function(event){
		if (event.which == 13) {
			event.preventDefault();
			$(this).hide();
			$("#picture_name").show();
			image_value_relay($(this), $("#picture_name"), ".name");
		}
	});

	$("#picture_description_input").keydown(function(event){
		if (event.which == 13) {
			event.preventDefault();
			$(this).hide();
			$("#picture_description").show();
			image_value_relay($(this), $("#picture_description"), ".description");
		}
	});

	$("#picture_tags_input").keydown(function(event){
		if (event.which == 13) {
			event.preventDefault();
			$(this).hide();
			$("#picture_tags").show();
			image_value_relay($(this), $("#picture_tags"), ".tags");
		}
	})

	$("#picture_name_input").keyup(function(){
		var value = $(this).val();
		var selected_id = '#' + $("#selected_image_id").val();
		$(selected_id).children(".name").val(value);
		if (value == "") {
			$(selected_id).children(".file-name").html('Add a title');
		} else {
			$(selected_id).children(".file-name").html(value);
		}
		
	});

	$("#picture_tags").click(function(){
		$(this).hide();
		$("#picture_tags_input").show();
		$("#picture_tags_input").select();

	});

	$("#picture_tags_input").blur(function(){
		$(this).hide();
		$("#picture_tags").show();
		image_value_relay($(this), $("#picture_tags"), ".tags");
	});

	$(".modal-screen").click(function(){
		$("#selected_image_id").val("");
		$("#picture_attributes_placeholder").show();
		$("#picture_name").hide();
		$("#picture_name").html("");
		$("#picture_description").hide();
		$("#picture_description").html("");
		$(".tags-container").hide();
		$(".upload-queue .image-container").removeClass("clicked");
	});

	$(".upload-url-button").click(function(){
		url = $("#image_url").val();
		if (url != "") {
			if (validateUrl(url)) {
				var image_id = "CURLUpload_" + ($("#upload-queue").children(".internet-upload").length + 1);
				$("#upload-queue").append("<div id='" + image_id + "' class='image-container internet-upload'><input class='fileID' type='hidden' name='ids[]' value='" + image_id + "' /><input class='name' type='hidden' name='names[]' value='' /><input class='description' type='hidden' name='descriptions[]' value='' /><input class='tags' type='hidden' name='tags[]' value='' /><input class='file_type' type='hidden' name='file_types[]' value='' /><input class='filename' type='hidden' name='file_names[]' value='' /><input class='thumbnail_name' type='hidden' name='thumbnail_names[]' value='' /><div class='cancel-internet-upload'></div><div class='thumbnail-container'><img class='placeholder' src='" + Mashtagg.base_url + "assets/new_picture.png' /></div><p class='file-name'>Add a title</p> <div class='progress-bar'><div class='progress'></div></div></div>")
				postData = {"url" : url};
				$("#image_url").val("");
				if (!$(".post-upload-container").is(":visible")) {
	        		$(".post-upload-container").show();
	        	}
				$.ajax({
					type: "POST",
					url: Mashtagg.base_url + "ajax/curl_image",
					data: postData,
					success: function(data) {
						image_data = JSON.parse(data);
						image_container = $("#" + image_id);
						image_container.children(".filename").val(image_data.image_file_name);
						image_container.children(".thumbnail_name").val(image_data.thumbnail_file_name);
						image_container.children(".file_type").val(image_data.file_type);
						image_container.children(".thumbnail-container").children(".placeholder").attr("src", image_data.image_source);
						image_container.children(".thumbnail-container").children(".placeholder").removeClass("placeholder");
						image_container.children(".progress-bar").children(".progress").css({"width" : "170px"})

					}
				});
			} else {
				alert("Not a valid url!");
				$("#image_url").val("");
			}
		}
		
		
	});

	$(".image-modal .image-wrapper img").click(function(){
		var images = $(".images-container .image-container");
		var source = $(this).attr("src");
		var max_height = parseInt($(window).height()) - 50;
		console.log(max_height);
		$(".image-modal .image-wrapper img").css({"max-height" : max_height});
		for(var i = 0; i < images.length; i++) {
			if ( source == $(images[i]).children(".image-url").val()) {
				if (i != images.length - 1) {
					var next_image = $(images[i + 1]);
					
				} else {
					var next_image = $(images[0]);
				}
				var temp_image = new Image();
				temp_image.onload = function() {
					center_modal($(".image-modal"), next_image.children(".image-height").val(), next_image.children(".image-width").val());
					var arrow_position = ($(".image-modal").height() / 2) - 24;
					$(".prev-image").css({"top" : arrow_position});
					$(".next-image").css({"top" : arrow_position});
				}
				temp_image.src = next_image.children(".image-url").val();
				$(this).attr("src", temp_image.src);
				
			}
		}
	});

	$(".next-image").click(function(){
		var images = $(".images-container .image-container");
		var source = $(this).siblings(".image-wrapper").children("img").attr("src");
		for(var i = 0; i < images.length; i++) {
			if ( source == $(images[i]).children(".image-url").val()) {
				if (i != images.length - 1) {
					var next_image = $(images[i + 1]);
					
				} else {
					var next_image = $(images[0]);
				}
				var temp_image = new Image();
				temp_image.onload = function() {
					center_modal($(".image-modal"), next_image.children(".image-height").val(), next_image.children(".image-width").val());
					var arrow_position = ($(".image-modal").height() / 2) - 24;
					$(".prev-image").css({"top" : arrow_position});
					$(".next-image").css({"top" : arrow_position});
				}
				temp_image.src = next_image.children(".image-url").val();
				$(this).siblings(".image-wrapper").children("img").attr("src", temp_image.src);
				
			}
		}
	});

	$(".prev-image").click(function(){
		var images = $(".images-container .image-container");
		var source = $(this).siblings(".image-wrapper").children("img").attr("src");
		for(var i = 0; i < images.length; i++) {
			if ( source == $(images[i]).children(".image-url").val()) {
				if (i != 0) {
					var next_image = $(images[i - 1]);
					
				} else {
					var next_image = $(images[images.length - 1]);
				}
				var temp_image = new Image();
				temp_image.onload = function() {
					center_modal($(".image-modal"), next_image.children(".image-height").val(), next_image.children(".image-width").val());
					var arrow_position = ($(".image-modal").height() / 2) - 24;
					$(".prev-image").css({"top" : arrow_position});
					$(".next-image").css({"top" : arrow_position});
				}
				temp_image.src = next_image.children(".image-url").val();
				$(this).siblings(".image-wrapper").children("img").attr("src", temp_image.src);
				
			}
		}
	});

	$(".image-modal .close-modal").click(function(){
		$(".image-modal").hide();
		$(".image-modal-screen").hide();
	});

	$(".image-modal-screen").click(function(){
		$(".image-modal").hide();
		$(".image-modal-screen").hide();
	});


});

function image_value_relay(element, text_element, hidden_element) {
	var value = element.val();
	var selected_id = '#' + $("#selected_image_id").val();
	if (value == "") {
		if (hidden_element == ".name") {
			text_element.html("Add a title");	
		} else if (hidden_element == ".description") {
			text_element.html("Add a description");
		} else if (hidden_element == ".tags") {
			text_element.html("Tags");
		}
	} else {
		text_element.html(value);
	}
	if (value != "Add a description" && value != "Add a title" && value != "Tags") {
		$(selected_id).children(hidden_element).val(value);	
	}
	
}

$(document).on('mouseenter','.image-modal', function(){
    $(this).children(".inspect-image").show();
    $(this).children(".prev-image").show();
    $(this).children(".next-image").show();
}).on('mouseleave','.image-modal', function(){
	$(this).children(".inspect-image").hide();
	$(this).children(".prev-image").hide();
    $(this).children(".next-image").hide();
}).on('click','.image-row .image-container', function(){
	var url = $(this).children(".image-url").val();
    $(".image-modal").children(".image-wrapper").children("img").attr("src", url);
    $(".image-modal").show();
    $(".image-modal-screen").show();
    var height = parseInt($(this).children(".image-height").val()) + 10;
    var width = parseInt($(this).children(".image-width").val()) + 10;
    var temp_image = new Image();
	temp_image.onload = function() {
    	center_modal($(".image-modal"), height, width);
    	var arrow_position = ($(".image-modal").height() / 2) - 24;
		$(".prev-image").css({"top" : arrow_position});
		$(".next-image").css({"top" : arrow_position});
	}
	temp_image.src = url;
}).on('mouseenter','.image-row .image-container', function(){
    $(this).children(".inspect-image").show();
}).on('mouseleave','.image-row .image-container', function(){
	$(this).children(".inspect-image").hide();
}).on('mouseenter','.upload-queue .image-container', function(){
    $(this).children(".cancel-upload").show();
    $(this).children(".cancel-internet-upload").show();
}).on('mouseleave','.upload-queue .image-container', function(){
	$(this).children(".cancel-upload").hide();
	$(this).children(".cancel-internet-upload").hide();
}).on('click', '.upload-queue .image-container', function(){
	//We first check and see if its actually a destroy event, we don't want to select it if its a destroy mouse click.
	if (!$(this).hasClass("dirty")) {
		$(".upload-queue .image-container").removeClass("clicked");
		$("#selected_image_id").val($(this).attr("id"));
		$(this).addClass("clicked");
		$("#picture_attributes_placeholder").hide();
		var image_name = $(this).children(".name").val();
		$("#picture_name").show();
		if(image_name != "") {
			$("#picture_name").html(image_name);
		} else {
			$("#picture_name").html("Add a title");
		}
		
		var image_description = $(this).children(".description").val();
		$("#picture_description").show();
		if (image_description != "") {
			$("#picture_description").html(image_description);
		} else {
			$("#picture_description").html("Add a description");
		}
		var image_tags = $(this).children(".tags").val();
		$(".tags-container").show();
		if (image_tags != "") {
			$("#picture_tags").html(image_tags);
		} else {
			$("#picture_tags").html("Tags");
		}
	}
}).on('click', '.cancel-upload', function(){
	var file_id = $(this).parent().attr("id");
	$(this).parent().addClass("dirty");
	if ($(this).parent().hasClass("clicked")) {
		$("#selected_image_id").val("");
		$("#picture_attributes_placeholder").show();
		$("#picture_name").hide();
		$("#picture_name").html("");
		$("#picture_description").hide();
		$("#picture_description").html("");
		$(".tags-container").hide();
	}
	$('#upload_btn').uploadify('cancel', file_id);
	$(this).parent().remove();
	var upload_count = $("#upload-queue").children(".image-container").length;
	if(upload_count === 0) {
		$(".post-upload-container").hide();
	} else if(upload_count === 1) {
		$(".confirm-upload").val("Upload 1 File");
	} else if (upload_count > 1) {
		$(".confirm-upload").val("Upload " + upload_count + " Files");
	}
}).on('click', '.cancel-internet-upload', function(){
	var file_id = $(this).parent().attr("id");
	$(this).parent().addClass("dirty");
	if ($(this).parent().hasClass("clicked")) {
		$("#selected_image_id").val("");
		$("#picture_attributes_placeholder").show();
		$("#picture_name").hide();
		$("#picture_name").html("");
		$("#picture_description").hide();
		$("#picture_description").html("");
		$(".tags-container").hide();
	}
	$(this).parent().remove();
	var upload_count = $("#upload-queue").children(".image-container").length;
	if(upload_count === 0) {
		$(".post-upload-container").hide();
	} else if(upload_count === 1) {
		$(".confirm-upload").val("Upload 1 File");
	} else if (upload_count > 1) {
		$(".confirm-upload").val("Upload " + upload_count + " Files");
	}
});;

$(window).load(function(){
	if ($("#post_container .outer-post-container").length) {
		$("#post_container .outer-post-container").equalHeights();
	}

	if($(".compose-outer-container").length > 0) {
		$(".compose-outer-container").equalHeights();	
	} 
});

function post_fix_heights() {
	$(".posts-container .post-container").each(function() {
		if ($(this).closest(".outer-post-container").hasClass("expanded")) {
			$(this).children(".vote-picture-container").css({
				"min-height" : $(this).children(".inner-post-container").height()
			});
		} else {
			$(this).children(".vote-picture-container").css({
				"min-height" : $(this).children(".inner-post-container").height() + 1
			});
		}
		
	});
}

window.onload=function(){post_fix_heights();}

//From http://rosskendall.com/blog/web/javascript-function-to-check-an-email-address-conforms-to-rfc822
function isRFC822ValidEmail(sEmail) {

  var sQtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
  var sDtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
  var sAtom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
  var sQuotedPair = '\\x5c[\\x00-\\x7f]';
  var sDomainLiteral = '\\x5b(' + sDtext + '|' + sQuotedPair + ')*\\x5d';
  var sQuotedString = '\\x22(' + sQtext + '|' + sQuotedPair + ')*\\x22';
  var sDomain_ref = sAtom;
  var sSubDomain = '(' + sDomain_ref + '|' + sDomainLiteral + ')';
  var sWord = '(' + sAtom + '|' + sQuotedString + ')';
  var sDomain = sSubDomain + '(\\x2e' + sSubDomain + ')*';
  var sLocalPart = sWord + '(\\x2e' + sWord + ')*';
  var sAddrSpec = sLocalPart + '\\x40' + sDomain; // complete RFC822 email address spec
  var sValidEmail = '^' + sAddrSpec + '$'; // as whole string
  
  var reValidEmail = new RegExp(sValidEmail);
  
  if (reValidEmail.test(sEmail)) {
    return true;
  }
  
  return false;
}

function readURL(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(id).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function center_modal(element, height, width) {
	var window_width  = $(window).width();
	var window_height = $(window).height();
	if (width > 800) {
		var aspect_ratio = (width / height);
		width = 800;
		height = (aspect_ratio * height);
	}
	var center_x = (window_width / 2) - (width / 2);
	if (height > (window_height - 50)) {
		var center_y = 25;
		var aspect_ratio = (width / height);
		height = window_height - 50;
		width = (height / aspect_ratio);
	} else {
		var center_y = (window_height / 2) - (height / 2);
	}
	console.log({
		"window_width" : window_width,
		"window_height" : window_height,
		"center_x" : center_x,
		"center_y" : center_y,
	});
	element.css({"top" : center_y, "left" : center_x});
}

function validateUrl(value){
    return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
}
