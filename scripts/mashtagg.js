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

var invalid_passwords = [ "password", "test", "testing", "stupid", "slasht", "123456", "secret" ];

$(document).ready(function() {

	if($("#new_post_textarea").length > 0) CKEDITOR.replace('new_post_textarea', {"height": 622});
	
	if($("#new_post_caption").length > 0) CKEDITOR.replace('new_post_caption');

	$(".active-menu-item").click(function(event) {
	
		tab_change(event, this);
		
	});

	$(".menu-item").click(function(event) {
		
		tab_change(event, this);
	
	});
	
	$(".upvote").click(function() {

		$(this).addClass("clicked_upvote");
		$(this).removeClass("upvote");
		$(this).next(".clicked_downvote").addClass("downvote");
		$(this).next(".clicked_downvote").removeClass("clicked_downvote");
		var sid = $(this).closest(".outer-comment-container").attr("id");
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/upvote",
			data: { "sid" : sid },
			success: function(data) {
				
			}
		});
	});
	
	$(".downvote").click(function() {

		$(this).addClass("clicked_downvote");
		$(this).removeClass("downvote");
		$(this).prev(".clicked_upvote").addClass("upvote");
		$(this).prev(".clicked_upvote").removeClass("clicked_upvote");
		var sid = $(this).closest(".outer-comment-container").attr("id");
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/downvote",
			data: { "sid" : sid },
			success: function(data) {
				
			}
		});
	});

	/******************* Posts page **********************/

	$(".outer-post-container").equalHeights();

	$('.new_comment').live('click', function() {
	
		var button = $(this);

		if ( button.val() === "Save" ) {
		
			var body = button.closest(".add_comment_container").children('.input_container').children(".new_comment_input").attr("name");
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
						} else {
							button.parent().siblings(".new_comment_input").show();
							button.closest('.input_container').hide();
							button.closest(".input_container").siblings(".reply").show();
							button.closest('.comment-body-container').siblings('.new-comment-holder').prepend(data);
							var new_textarea_name = button.closest('.comment-body-container')
								.siblings('.new-comment-holder')
								.children('.outer-comment-container')
								.children('.inner-comment-container')
								.children('.comment-body-container')
								.children('.add_comment_container')
								.children('.input_container')
								.children('textarea')
								.attr('name');

							if (typeof(new_textarea_name) != "undefined") {
								CKEDITOR.replace(new_textarea_name);
							}

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
		var is_hidden = $(this).prev(".input_container").is(":hidden")
		$('.input_container').hide();
		if (is_hidden) {
			$(this).prev('.input_container').show();
			$("#parent_comment").val($(this).closest(".outer-comment-container").attr("id"));
		}
		event.preventDefault();
	});
	
	$('#body').focus(function() {
	
		$(this).attr("rows", "8");
	
	});
	
	$('.outer-post-container').mouseover(function() {
	
		$(this).find(".comments").show();	
	});
	
	$('.outer-post-container').mouseout(function() {
		
		$(this).find(".comments").hide();
		
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
	
	
	$('#url').change(function() {
		
		var postData = { "url": $('#url').val() };
		
		var i = 0;
		
		$.ajax({
			type: "POST",
			url: Mashtagg.base_url + "ajax/website_scrape",
			data: postData,
			success: function(json) {
				var images = JSON.parse(json);
				
				$('.image-preview').show();
				$('#link-image').attr('src', images[i].src);
				$('#next-image').bind('click', function(event) {
					event.preventDefault();
					i++;
					
					if ( i > images.length) { 
						i = 0; 
					}
					
					$('#link-image').attr('src', images[i].src);
					
				});
				
				$('#prev-image').bind('click', function(event) {
					event.preventDefault();
					i--;
					
					if ( i < 0) { 
						i = images.length;
					}
					
					$('#link-image').attr('src', images[i].src);
					
				});
				
				
				
			}
		});
		
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
	})

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
				if (referral_amount < 5) {
					referral_amount++;
					$("#referral_amount").val(referral_amount);
					$(".email-container").hide();
					$(".inner-container .header").show();
					$("#referral_email").val("");
					if (referral_amount == 5) {
						$(".referral-header").html("<p>You have used up all 5 of your invites.<p>");
					} else {
						$(".referral-header").html("<p>You have sent " + referral_amount + " referrals out, and have " + (5 - referral_amount) + " left to send.</p>");
					}
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
				}
			} else {
				$(".validation-errors").show();
				$("#referral_email").css({"border" : "1px solid red"});
			}
			
		}
		
	});

});


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