jQuery.notifyBar = function(settings) {
	(function($) {
		var bar = notifyBarNS = {};
		notifyBarNS.shown = false;
		if (!settings) {
			settings = {};
		}
		notifyBarNS.html = settings.html || "Your message here";
		notifyBarNS.delay = settings.delay || 2000;
		notifyBarNS.animationSpeed = settings.animationSpeed || 200;
		notifyBarNS.jqObject = settings.jqObject;
		notifyBarNS.cls = settings.cls || "";
		notifyBarNS.close = settings.close || false;
		if (notifyBarNS.jqObject) {
			bar = notifyBarNS.jqObject;
			notifyBarNS.html = bar.html();
		} else {
			bar = jQuery("<div></div>").addClass("jquery-notify-bar").addClass(notifyBarNS.cls).attr("id", "__notifyBar");
		}
		bar.html(notifyBarNS.html).hide();
		var id = bar.attr("id");
		switch (notifyBarNS.animationSpeed) {
			case "slow":
				asTime = 600;
				break;
			case "normal":
				asTime = 400;
				break;
			case "fast":
				asTime = 200;
				break;
			default:
				asTime = notifyBarNS.animationSpeed;
		}
		if (bar != 'object'); {
			//jQuery("#notify_msg_div").html(bar);
			jQuery("body").prepend(bar);
		}
		if (notifyBarNS.close) {
			bar.append(jQuery("<a href='#' class='notify-bar-close'>Close [X]</a>"));
			jQuery(".notify-bar-close").click(function() {
				if (bar.attr("id") == "__notifyBar") {
					jQuery("#" + id).slideUp(asTime, function() {
						jQuery("#" + id).remove()
					});
				} else {
					jQuery("#" + id).slideUp(asTime);
				}
				return false;
			});
		}
		bar.slideDown(asTime);
		if (bar.attr("id") == "__notifyBar") {
			setTimeout("jQuery('#" + id + "').slideUp(" + asTime + ", function() {jQuery('#" + id + "').remove()});", notifyBarNS.delay + asTime);
		} else {
			setTimeout("jQuery('#" + id + "').slideUp(" + asTime + ", function() {jQuery('#" + id + "')});", notifyBarNS.delay + asTime);
		}
	})(jQuery)
};

function showError(msg) {
	if (msg != '') {
		$.notifyBar({
			html: msg,
			cls: "error",
			delay: 3500,
			animationSpeed: "normal",
			close:true
		});
	}
}

function showCustomMessage(msg) {
	if (msg != '') {
		$.notifyBar({
			html: msg,
			delay: 2500,
			animationSpeed: "fast",
			close:true
		});
	}
}

function showSuccess(msg) {
	if (msg != '') {
		$.notifyBar({
			html: msg,
			cls: "success",
			delay: 2500,
			animationSpeed: "normal",
			close:true
		});
	}
}