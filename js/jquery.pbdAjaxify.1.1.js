/* -------------------- *
 * PB Ajaxify           *
 * version 1.1          *
 * © 2017 Andre Lewis   *
 * www.pbd.com          *
 * -------------------- *
 * License PB FREE 1.0  *
 * -------------------- *
 
 * -------------------- *
 Use it freely
____________________________________________________________
END USER LICENSE AGREEMENT / EULA

For private and commercial use, PB Ajaxify can be used for free. You are free to do whatever you want.
Andre Lewis and ProjectBLU Developments claims full copyright or ownership but you can do what so ever to the scripts used in this application.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
____________________________________________________________
 HELP / CONTACT

If you need help getting PB Slidebar to work, 
contact me by email and I will get back to you as soon as possible
 
 < amalkong@gmail.com >
 */
 if ( typeof isSet !== "function" ) {
	function isSet(varname) {return typeof varname !== 'undefined';}
}
if ( typeof base_url !== "function" ) {
	function base_url(){
		var full_url_string = document.location.toString();
		var splint = full_url_string.split('.');
	    
		var expr = /^(.*)\/[^\/]+\/?$/g;
        return (expr.test(splint[0]) ? splint[0].replace(expr, "$1") : splint[0]) + '/';
	}
}

if(!isSet(SITE_BASE_URL)) {
	var SITE_BASE_URL = base_url();
}
       
    $.fn.pbdAjaxify = function (options) {
		 
        var $this = $( this ),
    
        // merge user options with default settings
        settings = $.extend({
	        ajax_view_container : '#main-ajax-container', // string | vertical,horizontal
	        orientation : 'vertical', // string | vertical,horizontal
			orientation_btn : '.orientation_btn', // string | 
			event : 'click', // string | hover,click
			show_tab_index : 2, // integer/number
			effect : 'fadein', // string | show,fadein
			effect_time : 1000, // integer/number
			height : 'auto', // integer/number/string 
        	_loading_code : '<center><p style="text-align:center !important;">Loading... Please Wait...<\/p><p style="text-align: center !important;"><img src="{loader}" border="0" alt="Loading Image" title="Please Wait..." \/><\/p><\/center>', // string | 
            _loading_error_code : '<center><p style="text-align: center !important;">Error!</p><p style="text-align: center !important;"><font color="red">There was a problem and the page didnt load. Possible reasons could be, the URL is incorrect or you are offline(script not called from a server, <strong>ajax<\/strong> only works online)<\/font><\/p><\/center>', // string | 
		}, options );	        
		
        return this.each(function(){
			//$('body,.nav_menu').css({'background':'#000','color':'#ff0000'});
		    var loadingIMG = $('<img/>').attr('src', SITE_BASE_URL + '/images/Hour Glass.gif');
	        var loadingDIV = $('<div/>').attr('style', 'display:none;').attr('class', 'ajaxLoadDivElement');
	        loadingDIV.appendTo('body');
	        loadingIMG.appendTo('.ajaxLoadDivElement');
	
	        _loading_code = settings._loading_code.replace('{loader}', loadingIMG.attr('src'));
	        _loading_error_code = settings._loading_error_code.replace('{loader}', loadingIMG.attr('src'));
	
            $(this).bind( settings.event, function(e) {
	            e.preventDefault();
                if(isSet($(this).attr('action'))){
		        	var $target_url = $($(this).attr('action'));
		        } else if(isSet($(this).parent().attr('action'))){
		        	var $target_url = $($(this).parent().attr('action'));
		        }  else if(isSet($(this).attr('data-href-link'))){
		        	var $target_url = $($(this).attr('data-href-link'));
		        } else var target_url = $(this).attr('href'); 
                
				if(target_url.match(/^.*#/) || target_url.match(/^#/)){
					if(settings.effect == 'show') $(target_url,$this).show(settings.effect_time);
					else if(settings.effect == 'fadein') $(target_url,$this).fadeIn(settings.effect_time);
					//$(target_url,$this).show();
				} else {
		            if(isSet($(this).attr('data-target-id'))){
		        	    var $target = $($(this).attr('data-target-id'));
		            } else var $target = $(settings.ajax_view_container);
		
		            if(isSet($(this).attr('data-method'))){
				        var method = $(this).attr('data-method');
				    } else var method = 'GET';
					
					if(settings.effect == 'show') $target.show(settings.effect_time).html(_loading_code);
					else if(settings.effect == 'fadein') $target.fadeIn(settings.effect_time).html(_loading_code);
					//$target.show(800).html(_loading_code);
					
					$.ajax({
				        url: target_url,
					    type: method,
				    	cache: false,
				    	success: function (html) {
				    		//$target.show(800).html(html);
							if(settings.effect == 'show') $target.show(settings.effect_time).html(html);
							else if(settings.effect == 'fadein') $target.fadeIn(settings.effect_time).html(html);
				    	},
				    	error: function(html){
				    	    //$target.show(800).html(_loading_error_code + "<br>\n" + html.responseText);							
				    	    if(settings.effect == 'show') $target.show(settings.effect_time).html(_loading_error_code + "<br>\n" + html.responseText);
							else if(settings.effect == 'fadein') $target.fadeIn(settings.effect_time).html(_loading_error_code + "<br>\n" + html.responseText);
						}
			    	});
			     	
					$(this).addClass('ajax-active-tab');
					return true;
				}
                
                return false;
            });
	/* 
        	if($this.hasClass('horizontal')){
	            $(settings.orientation_btn).find(".ui-icon-arrowthick-2-e-w").removeClass( "ui-icon-arrowthick-2-e-w").addClass("ui-icon-arrowthick-2-n-s");
	        } else {
	            $(settings.orientation_btn).find(".ui-icon-arrowthick-2-n-s").removeClass( "ui-icon-arrowthick-2-n-s").addClass("ui-icon-arrowthick-2-e-w");
	        }
			
			$(settings.orientation_btn).click(function(){
	            if($this.hasClass('horizontal')){
	                $this.removeClass("horizontal").addClass("vertical");
	                $(".ui-icon-arrowthick-2-n-s",this).removeClass( "ui-icon-arrowthick-2-n-s").addClass("ui-icon-arrowthick-2-e-w");
		        }else {
		            $this.removeClass("vertical").addClass("horizontal");
		            $(".ui-icon-arrowthick-2-e-w",this).removeClass( "ui-icon-arrowthick-2-e-w").addClass("ui-icon-arrowthick-2-n-s");
	            }
				
		        return false;	
	        }); */
	    });
    };