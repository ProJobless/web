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
			url: "http://localhost/spindlous/ajax/username_check",
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
			url: "http://localhost/spindlous/ajax/email_check",
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

var invalid_usernames = new Array( "admin", "administrator", "ajax", "api", "home", "login", "logout", "profile", "saved_links", "signup", "spool", "test", "iphone", "android" );

var invalid_passwords = new Array( "password", "test", "testing", "stupid", "spindlous", "123456", "secret" );

$(document).ready(function() {

	$('.edit').click(function() {
	
		
	
	});
	
	$('#body').focus(function() {
	
		$(this).attr("rows", "8");
	
	});
	
	$('.post-container').mouseover(function() {
	
		$(this).find(".comments").show();	
	});
	
	$('.post-container').mouseout(function() {
		
		$(this).find(".comments").hide();
		
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
			url: "http://localhost/spindlous/ajax/add_post",
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
			url: "http://localhost/spindlous/ajax/website_scrape",
			data: postData,
			success: function(json) {
				var images = JSON.parse(json);
				
				images = 
				
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
	
});