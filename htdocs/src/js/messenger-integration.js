var facebookLoaded = false;
var messengerButton = $('.messenger');
var messengerWindow = $('.messenger-window');
messengerButton.show();
messengerButton.click(function() {
	"use strict";

	messengerWindow.toggle();

	messengerWindow.find('textarea').focus();

	if (!facebookLoaded) {
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '1780998928787657',
				xfbml      : true,
				version    : 'v2.6'
			});
			messengerWindow.find('.messenger-loading').hide();
		};

		(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {
				return;
			}
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	}
});
