(function($) {
	wpMySoGridFront=function(o){
		var self;
		this.networks_posts={};
		self=this;
		self.debug=false;
		self.centered=true;
		self.pages=1;
		self.page=1;
		self.my_working=false;
		self.cache_time='';
		self.transforms=false;
		self.my_transforms_prefix='';
		this.init=function(o){
			self.options=o;
			self.my_debug("Options",o);
			self.ww=$(window).width();
			self.init_widths();
			if(self.centered){
				//self.center_isotope();
				
			}
			self.my_dialog_12_open_12=false;
			//$(document).on("load",".my_sogrid_thumb img",self.my_scale_image);
			//$(".my_sogrid_thumb img").load(self.my_scale_image);
			//$('.my_sogrid_plusone iframe').load(self.my_load_iframe);
			//self.correct_border(0);
			//$(window).load(self.my_load_image);
			self.init_isotope();
			self.my_load_image();
			$(window).resize(self.my_resize);
			
			var ww_d=$(window).width();
			var wh_d=$(window).height();
			var dw_d=640;
			var dh_d=360;
			if(dw_d>ww_d)dw_d=ww_d;
			
			if(dh_d>wh_d)dh_d=wh_d;
			if(!self.options.my_preview){
			$(".my_dialog_"+self.options.id).dialog({
				dialogClass:'my_dialog_no_title',
				width:dw_d,
				height:dh_d,
				modal:true,
				draggable:false,
				resizable:false,
				autoOpen:false,
				/*fluid:true,*/
				maxWidth:'80%',
				maxHeight:'80%',
				open:function(){
					self.my_dialog_12_open_12=true;
					//$(".my_dialog_"+self.options.id).parents(".ui-dialog-titlebar").remove();
					$(".my_close_dialog_12_12").unbind("click");
					$(".my_close_dialog_12_12").click(function(e){
						$(".my_dialog_"+self.options.id).dialog("close");
					});
					self.getViewport();
					var w=self.viewport.w;
					var h=$(document).height();
					$('body').append('<div id="my_overlay_12_12" class="my_dialog_overlay_12_12" style="width:'+w+'px;height:'+h+'px;opacity:0"></div>');
					$("#my_overlay_12_12").animate({opacity:0.7},200);
					
					/*
					self.viewport={
							w:w,
							h:h
					};*/
					
				},
				close:function(){
					self.my_dialog_12_open_12=false;
					$(".my_dialog_"+self.options.id).find('iframe').remove();
					$(".my_dialog_"+self.options.id+" h4").show();
					$("#my_overlay_12_12").remove();
				}
			});
			
			$(window).resize(function(e){
				var dw=640;
				var dh=360;
				var ww=$(window).width();
				var wh=$(window).height();
				if(dw>ww)dw=ww-100;//-100;
				if(dh>wh)dh=wh-100;//-100;
				/*if(window.console){
					console.log("Width",{dw:dw,dh:dh,wh:wh,ww:ww,open:self.my_dialog_12_open_12});
					}*/
				if(self.my_dialog_12_open_12){
					/*var left=(ww-dw)/2;
					var top=(wh-dh)/2;
					top+=$(window).scrollTop();
					if(window.console){
						console.log("Width",{left:left,top:top,dw:dw,dh:dh});
					}
					$(".my_dialog_"+self.options.id).parents('.ui-dialog').width(dw);
					$(".my_dialog_"+self.options.id).parents('.ui-dialog').height(dh);
					$(".my_dialog_"+self.options.id).parents('.ui-dialog').css('top',top+'px');
					$(".my_dialog_"+self.options.id).parents('.ui-dialog').css('left',left+'px');
					*/
					$(".my_dialog_"+self.options.id).dialog("close");
				}
					//}else {
						$(".my_dialog_"+self.options.id).dialog("option","width",dw);
						$(".my_dialog_"+self.options.id).dialog("option","height",dh);
						$(".my_dialog_"+self.options.id).parents('.ui-dialog').width(dw);
						$(".my_dialog_"+self.options.id).parents('.ui-dialog').height(dh);
						
					//	}
				
				});
			}	
			//self.init_plusone();
			$(document).on('click',".my_social_share_link",self.my_show_window);
			$(".my_sogrid_container[data-my-id='"+self.options.id+"'] .my_social_item_inner_text ").mCustomScrollbar();
			$(document).on("click",".my_social_youtube .my_sogrid_thumb",self.my_open_youtube_video);
			//Like dislike an video 
			$(".my_social_youtube .fa-thumbs-up , .my_social_youtube .fa-thumbs-down").click(self.my_like_dislike_youtube);
			
			if((self.options.dynamic_loading_animation==1)){
				self.has_transform();
				self.my_force=true;
				self.my_animate_elements();
				self.my_force=false;
				$(window).scroll(self.my_animate_elements);
			}
			if(self.options.dynamic_loading==1){
				self.my_debug("Dynamic Loading");
				$(window).scroll(self.my_scroll);
				self.cache_time=$("#my_sogrid_id_"+self.options.id).data('cache-time');
				self.my_debug("Cache time", self.cache_time);
				self.pages=parseInt($("#my_sogrid_id_"+self.options.id).data('pages'));
				self.my_debug("Pages",self.pages);
				/*if(self.options.my_preview==1){
					$(".my_dialog_preview iframe").contentWindow.scroll(self.my_scroll_1);
			
				}*/
				
			}
			/*if(self.options.has_facebook_sdk){
				console.log('Has facebook sdk',1);
				self.my_facebook_login=false;
				FB.getLoginStatus(function(response){
					self.facebook_login(response);
				});
				FB.Event.subscribe('edge.create', self.my_facebook_create);
				FB.Event.subscribe('edge.remove', self.my_facebook_remove);
				$(document).on("click",".my_facebook_new_like_a",self.my_like_new);
			}else {
				$(document).on("click",".my_facebook_new_like_a",function(e){
					e.preventDefault();
				});
			}*/
			/*setTimeout(function(){
			self.my_set_google_plus_iframe();
			},500);
			*/
			
		};
		this.my_open_youtube_video=function(e){
			e.preventDefault();
			var video_id=$(this).parents('li').data('my-id');
			//console.log('Video id',video_id);
			self.my_video_id_12=video_id;
			var ww_d=$(window).width();
			var wh_d=$(window).height();
			var dw_d=640;
			var dh_d=360;
			var i_w=640;
			var i_h=360;
			if(ww_d<dw_d){
				i_w=ww_d-20;
				i_h=i_w*0.75;
			}else if(wh_d<dh_d){
				i_h=wh_d-20;
				i_w=i_h*1.33;
			}
			
			//var html='<iframe width="100%" height="100%" src="https://www.youtube.com/embed/'+video_id+'?rel=0"></iframe>';
			//$.colorbox({iframe:true,innerWidth:640,innerHeight:390,maxWidth:'80%',maxHeight:'80%',open:true,href:function(){
			//	return 'https://www.youtube.com/embed/'+self.my_video_id_12+'?rel=0';
			//}});
			
			$(".my_dialog_"+self.options.id+" #my_video_id_12_12").html('<iframe width="'+i_w+'" height="'+i_h+'" src="https://www.youtube.com/embed/'+video_id+'?rel=0"></iframe>');
			$(".my_dialog_"+self.options.id+" #my_video_id_12_12 iframe").load(function(e){
				//console.log('Iframe loadded ',video_id);
				
				$(".my_dialog_"+self.options.id+" h4").hide();
				$(".my_dialog_"+self.options.id+" iframe").show();
				$(".my_dialog_"+self.options.id+" iframe").animate({opacity:1});
			});		
			$(".my_dialog_"+self.options.id).parents(".ui-dialog-titlebar").hide();
			$(".my_dialog_"+self.options.id).dialog("open");
			
		};
		this.my_like_dislike_youtube=function(e){
			var type='like';
			if($(this).hasClass("fa-thumbs-down"))type='dislike';
			var v_ww=window.outerWidth;
			var v_hh=window.outerHeight;
			var ww=800;
			var hh=400;
			
			if(v_ww<ww){
				ww=v_ww;
			}
			if(v_hh<hh){
				hh=v_hh;
			}
			var left=Math.floor((v_ww-ww)/2);
			var top=Math.floor((v_hh-hh)/2);
			self.my_debug("Window",{ww:ww,hh:hh,v_ww:v_ww,v_hh:v_hh,top:top,left:left});
			var video_id=$(this).parents(".my_sogrid_youtube_metadata").data('my-id');
			
			var href=self.options.like_dislike_youtube_url+'?my_video_id='+ encodeURIComponent(video_id)+'&my_action='+type+'&my_sogrid='+self.options.id;
			console.log('href',href);
			console.log('video',video_id);
			//if(type=='')
			var win=window.open(href, "my_social_share", "scrollbars=1,menubar=0,location=0,toolbar=0,status=0,width="+ww+", height="+hh+", top="+top+", left="+left);
			
		};
		this.my_get_orig_width_height=function(src){
			
			    var t = new Image();
			    t.src=src;
			    var obj={w:t.width,h:t.height};
			    return obj;
		};
		this.my_load_image=function(e){
			self.my_has_more_images_12345=false;
			$("#my_sogrid_id_"+self.options.id).find('.my_sogrid_thumb img').each(function(i,v){
				var adj=$(v).data('my_adjusted');
				if(typeof adj=='undefined' || (adj==0)){
				
				var src=$(v).attr('src');
				
			//var v=$(this);
				
				//if(typeof adj=='undefined'){
					var w=parseFloat($(v).width());
					var h=parseFloat($(v).height());
				//}
				
				var c_w=parseFloat($(v).parent("div").width());
				var c_h=parseFloat($(v).parent("div").height());
				
				//console.log("Finished loading",{src:src,w:w,h:h});
				if((w==0)&&(h==0)){
					self.my_has_more_images_12345=true;
					
				}
				if(w!=0&&h!=0){
					var obj=self.my_get_orig_width_height(src);
					h=obj.h;
					w=obj.w;
					
					//console.log("Original width height",{src:src,w:w,h:h});
					if((w==0)&&(h==0)){
						self.my_has_more_images_12345=true;
						
					}else {
					if(adj==0){
						//$(v).removeAttr('style');	
						//console.log('Adjustemnet');
						w=parseFloat($(v).data('my-w'));
						h=parseFloat($(v).data('my-h'));
					}else {
						$(v).data('my-w',w);
						$(v).data('my-h',h);
					}
				$(v).data('my_adjusted',1);
				/*if((w>=c_w)&&(h>=c_h)||(w>=c_w)||(h>=c_h)){
					//console.log('Width Height bigger',{w:w,h:h,c_w:c_w,c_h:c_h});
					var m1=w/c_w;
					var m2=h/c_h;
					//console.log('M1,m2',{m1:m1,m2:m2});
					if(m1>m2){
						$(v).css('width','100%');
						var v2=Math.floor(((h)/(m1*c_h))*100);
						//console.log('V2 width 100%',v2);
						$(v).css('height',v2+'%');
						var v3=(100-v2)/200*c_h;
						$(v).css('margin-top',v3+'px');
					}else {
						$(v).css('height','100%');
						var v2=Math.floor(((w)/(m2*c_w))*100);
						$(v).css('width',v2+'%');
					}
				}
				else*/ 
				if((w<c_w)&&(h<c_h)){
					//console.log('Width Height smaller',{w:w,h:h,c_w:c_w,c_h:c_h});
				//$(v).width(c_w);
				//$(v).height(c_h);
					//$(v).css('width','100%');
					//$(v).css('height','100%');
					var m1=w/c_w;
					var m2=h/c_h;
					//console.log('M1,m2',{m1:m1,m2:m2});
					if(m1>m2){
						$(v).css('width','100%');
						var v2=Math.floor((h/(m1*c_h))*100);
						//console.log('V2 width 100%',v2);
						$(v).css('height',v2+'%');
						var v3=(100-v2)/200*c_h;
						$(v).css('margin-top',v3+'px');
						
					}else {
						
						
						$(v).css('height','100%');
						var v2=Math.floor((w/(c_w*m2))*100);
						$(v).css('width',v2+'%');
						//var v3=(100-v2)/2;
						//$(v).css('margin-left',v3+'%');
						//console.log('V2 height 100%',v2);
						
					}
				}else if((w<c_w)){
					//console.log('Width smaller',{w:w,h:h,c_w:c_w,c_h:c_h});
				//$(v).width(c_w);
					$(v).css('width','100%');
				//$(v).css('height','100%');
				}else if((h<c_h)){
					//console.log('Height smaller',{w:w,h:h,c_w:c_w,c_h:c_h});
				//$(v).height(c_h);
				//$(v).css('width','100%');
					$(v).css('height','100%');
					
				}
				//$(v).fadeIn();
				}
				}
				}
			});
			if(self.my_has_more_images_12345){
				//console.log('Has more settimeout');
				setTimeout(self.my_load_image,600);
			}else {
				if(self.options.enable_scroll_images==1){
					$("#my_sogrid_id_"+self.options.id).find('.my_sogrid_thumb').each(function(i,v){
						var it_has=$(v).data('my_has_scroll');
						if(typeof it_has=='undefined'){
							$(v).mCustomScrollbar({axis:'y'});
							$(v).data('my_has_scroll',1);
						}else {
							$(v).mCustomScrollbar("update");
							
						}
					});
				}
			}
		};
		this.my_scale_image=function(){
			$("#my_sogrid_id_"+self.options.id).find('.my_sogrid_thumb img').each(function(i,v){
				//var href=$(this).attr('href');
				//console.log("Finished loading",{href:href});
				//var v=$(this);
				var w=$(v).width();
				var h=$(v).height();
				var c_w=$(v).parent("div").width();
				var c_h=$(v).parent("div").height();
				if((w<c_w)&&(h<c_h)){
					console.log('Width Height smaller',{w:w,h:h,c_w:c_w,c_h:c_h});
					$(v).width(c_w);
					$(v).height(c_h);
				}else if((w<c_w)){
					console.log('Width smaller',{w:w,h:h,c_w:c_w,c_h:c_h});
					$(v).width(c_w);
				}else if((h<c_h)){
					console.log('Height smaller',{w:w,h:h,c_w:c_w,c_h:c_h});
					$(v).height(c_h);
				}
			});
		};
		this.facebook_login=function(response){
			//console.log('response',response);
			if(response.status=='connected'){
				self.my_login_facebook=true;
			}
			
		};
		this.my_like_new=function(e){
			e.preventDefault();
			if(!self.my_login_facebook){
				FB.login();
				return;
			}
			//FB.getLoginStatus();
			var post_id=$(this).parents('li.my_social_facebook').data('my-id');
			var url=$(this).data('url');
			//console.log('Like post',{post_id:post_id,url:url});
			var c=parseInt($(this).data('c'));
			self.my_href_facebook=url;
			self.my_post_id=post_id;
			
			FB.api("/"+post_id+"/likes", 'post',function(response) {
				//console.log('Response',response);
				if(typeof response.success!='undefined'){
			    if(response.success === true) {
			        //alert("done!");
			    	var post_id=self.my_post_id;
			    	var href=self.my_href_facebook;
			    	var c=parseInt($(".my_facebook_new_like_a[data-url='"+href+"']").data('c'));
			    	//console.log('Href',{href:href,c:c,post_id:post_id});
			    
			    	if(c<1000){
			    		var c_1=c;
			    		c++;
			    		
			    		$(".my_facebook_new_like_a[data-url='"+href+"']").data('c',c);
			    		$(".my_facebook_new_like_a[data-url='"+href+"'] span").text(c);
			    		self.update_posts('facebook', post_id,c_1,'create');
			    		
			    	}
			    }
				}
			});
		};
		this.my_facebook_create=function(url,h){
			//console.log('Url',url);
			var href=url;
			var c=parseInt($(".my_facebook_new_like_a[data-url='"+href+"']").data('c'));
			var post_id=$(".my_facebook_new_like_a[data-url='"+href+"']").parents('li.my_social_facebook');
			//console.log("data",{c:c,post_id:post_id});
			if(c<1000){
				c--;
				$(".my_facebook_new_like_a[data-url='"+href+"']").data('c',c);
				$(".my_facebook_new_like_a[data-url='"+href+"'] span").text(c);
				self.update_posts('facebook', post_id,c,'create');
				
			}
		};
		this.my_facebook_remove=function(url,h){
			//console.log('Url',url);
			var href=url;
			var c=parseInt($(".my_facebook_new_like_a[data-url='"+href+"']").data('c'));
			var post_id=$(".my_facebook_new_like_a[data-url='"+href+"']").parents('li.my_social_facebook');
			//console.log("data",{c:c,post_id:post_id});
			if(c<1000){
				c--;
				$(".my_facebook_new_like_a[data-url='"+href+"']").data('c',c);
				$(".my_facebook_new_like_a[data-url='"+href+"'] span").text(c);
				self.update_posts('facebook', post_id,c,'remove');
				
			}
			
		};
		this.gplus_share=function(param){
			//console.log('Param',param);
			var href=param.id;
			var type=param.type;
			if(type=='hover'){
				var c=parseInt($(".my_sogrid_plusone_tweek_1[data-url='"+href+"']").data('c'));
				var h=parseInt($(".my_sogrid_plusone_tweek_1[data-url='"+href+"']").data('h'));
				h++;
				$(".my_sogrid_plusone_tweek_1[data-url='"+href+"']").data('h',h);
				//console.log('H',h);
				//if((h%2)==0){
				//console.log('C',c);
				if(c<1000){
					/*if(state=='on'){
						c++;
					}else c--;
					*/
					/*c++;
					//console.log('New c',c);
					
					$(".my_sogrid_plusone_tweek_1[data-url='"+href+"']").data('c',c);
					var f='+'+c;
					if(c>1000)f='+1K';
					console.log('F',f);
					*/
					//$(".my_sogrid_plusone_tweek_1[data-url='"+href+"'] span").html(f);
					
					var post_id=$(".my_sogrid_plusone_tweek[data-url='"+href+"']").parents('.my_social_google').data('my-id');
					//console.log('post_id',post_id);
					self.update_posts('google', post_id,c,'share');
				};
				//}
			}
		};
		this.gplus_click=function(param){
			//console.log('Param',param);
			var href=param.href;
			var state=param.state;
			
			var c=parseInt($(".my_sogrid_plusone_tweek[data-url='"+href+"']").data('c'));
			//console.log('C',c);
			if(c<1000){
				var c_1=c;
				if(state=='on'){
					c++;
				}//else c--;
				//console.log('New c',c);
				
				$(".my_sogrid_plusone_tweek[data-url='"+href+"']").data('c',c);
				var f='+'+c;
				if(c>1000)f='+1K';
				//console.log('F',f);
				if(state=='on'){
					$(".my_sogrid_plusone_tweek[data-url='"+href+"'] span").html(f);
					var post_id=$(".my_sogrid_plusone_tweek[data-url='"+href+"']").parents('.my_social_google').data('my-id');
					//console.log('post_id',post_id);
					self.update_posts('google', post_id,c_1,'plusone');
				}
			};
		};
		this.update_posts=function(network,post_id,c,type){
			var data={
					action:self.options.ajax_action_update,
					nonce:self.options.nonce,
					id:self.options.id,
					network:network,
					post_id:post_id,
					c:c,
					type:type
				};
			//console.log('Data',data);
				$.ajax({
					url:self.options.ajax_url,
					dataType:'json',
					async:true,
					data:data,
					cache:false,
					
					timeout:self.options.ajax_timeout,
					type:'POST',
					success:function(data){
						//console.log('Data',data);
						if(data.error==0){
							if(data.network=='google'){
								if(data.type=='share'){
									var c=data.new_c;
									var f=c;
									if(c>1000)f='1K';
									var post_id=data.post_id;
									$(".my_social_google[data-my-id='"+post_id+"'] .my_sogrid_plusone_tweek_1 span").html(f);
									
								}
							}else if(data.network=='twitter'){
								var type=$(self.my_a_obj).data('t');
								var c=data.data[type];
								var c_f=c;
								if(c>=1000)c_f='1K';
								$(self.my_a_obj).siblings('span').text(c_f);
							}
						}
					},
					error:function(){
						
					}
				});
			
			
		};
		this.correct_border=function(u){
			if(self.options.networks.length>0){
				$.each(self.options.borders,function(i,v){
					var css_class='my_social_'+i+'_inner';
					window.console.log('Enable boredr',{i:i,v:v,css_class:css_class});
				    //var w=$("."+css_class).width();
				    //var h=$("."+css_class).
					if(v==0){
						$("."+css_class).each(function(i1,v1){
							var adj=$(v1).data('my_adjusted_border');
							if(typeof adj=='undefined'){
								if(u){
									var top=parseFloat($(v1).parent('li').css('top'));
									var left=parseFloat($(v1).parent('li').css('left'));
									top+=2;
									//left+=1;
									$(v1).parent('li').css('top',top+'px');
									//$(v1).parent('li').css('left',left+'px');
								}
								$(v1).data('my_adjusted_border',1);
								var w=$(v1).parent('li').width();
								var h=$(v1).parent('li').height();
								w+=2;
								h+=2;
								$(v1).parent('li').width(w);
								$(v1).parent('li').height(h);
							}
								
						});
					}
				});
			}
		};
		this.my_set_google_plus_iframe=function(){
			var my_has_all=true;
			$(".my_sogrid_plusone").each(function(i,v){
				//var w=$(v).outerWidth();
				//var h=$(v).outerHeight();
				
				if($(v).find('iframe').length!=0){
					var src=$(v).find('iframe').attr('src');
					console.log('Iframe src',src);
					added=$(v).find('iframe').data('my_added');
					if(typeof added=='undefined'){
						$(v).parent(".my_sogrid_plusone").find('iframe').data('my_added',1);
						$(v).parent(".my_sogrid_plusone").find('iframe').load(self.my_load_iframe);
					}
				
				}else my_has_all=false;
					
				//}else {
				/*$(v).siblings("div").width(w);
				$(v).siblings("div").height(h);
				$(v).siblings("div iframe").width(w);
				$(v).siblings("div iframe").height(h);*/
				//}
				
			});
			if(!my_has_all){
				setTimeout(function(){
					self.my_set_google_plus_iframe();
					},500);
			}
		};
		this.my_load_iframe=function(e){
			var href=$(this).attr('src');
			
			var $div=$(this).parent('.my_sogrid_plusone');
			var w=$($div).find('.my_sogrid_plusone_tweek').outerWidth();
			var h=$($div).find('.my_sogrid_plusone_tweek').outerHeight();
			console.log('Loadded',{href:href,w:w,h:h});
			
			$($div).find('.my_sogrid_plusone_tweek').siblings('div').width(w);
			$($div).find('.my_sogrid_plusone_tweek').siblings('div').height(h);
			$($div).find('.my_sogrid_plusone_tweek').siblings('div iframe').width(w);
			$($div).find('.my_sogrid_plusone_tweek').siblings('div iframe').height(h);
			
			
		};
		this.has_transform=function(){
			var obj = document.createElement('div');
            var props = ['transform', 'WebkitTransform', 'MozTransform', 'OTransform', 'msTransform'];
            var found=false;
            for (var i in props) {
		            if ( obj.style[ props[i] ] !== undefined ) {
		            if(props[i]=='transform'){
		            	self.my_transforms_prefix="";
			            found=true;
		            }else {
		            var my_pref=props[i].replace('Transform','').toLowerCase();
		            if(my_pref==""){
		            	self.my_transforms_prefix="";
		            }else {
		            	self.my_transforms_prefix="-"+my_pref+"-";
		            }
		            }
		            found=true;
		            self.transforms=true;
		            }
		            }
            if(!found)self.transforms=false;
            //console.log('Has transform',{t:self.transforms,prefix:self.my_transforms_prefix});
		};
		this.my_animate_elements=function(e){
			//if(self.my_start_resize)return;
			var scrollTop=$(window).scrollTop();
			var w_h=$(window).height();
			var bottom=scrollTop+w_h;
			self.my_bottom=bottom;
			var p=scrollTop+w_h/2;
			var stop_p=bottom+w_h;
			var my_last_top=$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul").offset().top;
			$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul li").each(function(i,v){
				var s=$(v).data('my_animated');
				var top=$(v).offset().top;
				var id=$(v).attr('id');
				if(top>stop_p)return false;
				var top_1=top+self.options.gap;
				if(self.my_force)top_1=top;
				if((typeof s=='undefined')||s==0){
					//$(v).css('opacity',0);
					//console.log('Is Animate',{id:id,s:s,top:top,bottom:bottom});
					if(top_1<bottom){
						var diff=bottom-top;
						//console.log('Animate',{id:id,s:s,top_1:top_1,top:top,bottom:bottom,my_last_top:my_last_top,diff:diff});
						$(v).data('my_animated',1);
						self.my_animate_elements_function_1(v,p,my_last_top,diff);
						
						/*$(v).css('WebkitPerspectiveOrigin',"50% "+p+"px");
						$(v).css("MozPerspectiveOrigin","50% "+p+"px");
						$(v).css("perspectiveOrigin","50% "+p+"px");
						$(v).addClass('my_animate_box');
						$(v).data('my_animated',1);*/
						
					
					}else {
						/*console.log('Remove Animate',{id:id})
						$(v).css('WebkitPerspectiveOrigin','');
						$(v).css("MozPerspectiveOrigin","");
						$(v).css("perspectiveOrigin","");
						
						$(v).removeClass('my_animate_box');
						//$(v).css('opacity',0);
						*/
						$(v).data('my_animated',0);
						var old_style=self.old_style(v);
						old_style+='opacity:0 !important;';
						$(v).attr('style',old_style);
						
						
					}
				}else {
					if(top>bottom){
						/*console.log('Remove animate',{id:id});
						$(v).css('WebkitPerspectiveOrigin','');
						$(v).css("MozPerspectiveOrigin","");
						$(v).css("perspectiveOrigin","");
						*/
						$(v).data('my_animated',0);
						//$(v).removeClass('my_animate_box');
						//$(v).css('opacity',0);
						var old_style=self.old_style(v);
						old_style+='opacity:0 !important;';
						$(v).attr('style',old_style);
						
					}else {
						my_last_top=top+$(v).outerHeight();
					}
					//$(v).removeClass('my_animate_box');
					//$(v).css('opacity',0);
					
					//$(v).data('my_animated',0);
					//if(top>bottom)$(v).data('my_animated',0);
					
				}
			});
			
		};
		this.old_style=function(v){
			var top_p=parseFloat($(v).css('top'));
			var left_p=parseFloat($(v).css('left'));
			var pos=$(v).css('position');
			/*var r=$(v).css('transfrom');
			var left_1=0;
			var top_1=0;
			if(typeof r!='undefined'){
			var res=r.match(/(.*[0-9]+px.*,.*[0-9]+px.*,.*[0-9]+px.*)/gi);
			
			console.log('Transform',res);
			left_1=parseFloat(res[0]);
			top_1=parseFloat(res[1]);
			left_p+=left_1;
			top_p+=top_1;
				
			}*/
			
			var top=top_p+'px';
			var left=left_p+'px';
			old_style='position:'+pos+';top:'+top+';left:'+left+';';
			return old_style;

		};
		this.my_animate_elements_function_1=function(v,p,last,diff){
			var old_style=self.old_style(v);
			$(v).data('my-old_style',old_style);
			//console.log('Old style',old_style);
			var scrollTop=$(window).scrollTop();
			var w_h=$(window).height();
			var bottom=scrollTop+w_h;
			//var diff=Math.abs(bottom-$(v).offset().top);
			var my_id=$(v).attr('id');
			$(v).data('my_diff',diff);
			//console.log('Diff',diff);
			
			if(self.transforms){
				/*var old_transform=$(v).css(self.my_transforms_prefix+'transform');
				if(typeof old_transform=='undefined'){
					old_transform=$(v).css('transform');
				}
				old_transform=old_transform.replace(/translateY([^)]+)/gi,'');
				console.log('Old transform',old_transform);
				*/
				var origin='0px 0px';
				$(v).data('my-origin',origin);
				var style=self.my_transforms_prefix+'transform-origin:'+origin+';opacity:'+self.options.start_op+' !important;';
				style+=self.my_transforms_prefix+'transform:translateY('+diff+'px);';
				style+=old_style;
				$(v).attr('style',style);
				//$(v).my_scale=0;
				//$.each([v],function(i1,v1){this.my_scale=0;});
				$("#"+my_id).each(function(i,v){this.my_scale=0;});
			//	console.log('Has transforms');
				$(v).animate({
					my_scale:1},{
					step:function(now,fx){
						//console.log('My scale',{now:now,fx:fx});
						if(fx.prop=='my_scale'){
							
							var origin=$(v).data('my-origin');
							var old_style=$(v).data('my-old_style');
							var diff=parseFloat($(v).data('my_diff'));
							var new_gap=diff-now*diff;
							var op=self.options.start_op+now;
							if(op>1)op=1;
							//console.log('My scale',{now:now,origin:origin,old_style:old_style,new_gap:new_gap});
							var style=self.my_transforms_prefix+'transform-origin:'+origin+';opacity:'+op+'!important;';
							style+=self.my_transforms_prefix+'transform:translateY('+new_gap+'px);';
							style+=old_style;
							$(this).attr('style',style);	
						}
					},duration:self.options.my_duration
				});
			}else {
				$("#"+my_id).each(function(i,v){this.my_scale=0;});
				var style='opacity:0 !important;';
				style+=old_style;
				//v.my_scale=0;
				$.each([v],function(i1,v1){this.my_scale=0;})
				$(v).attr('style',style);
				$(v).animate({
					my_scale:1},{
					step:function(now,fx){
						//console.log('My scale',{now:now,fx:fx});
						if(fx.prop=='my_scale'){
							var old_style=$(v).data('my-old_style');
							//var op=self.options.start_op+now;
							//if(op>1)op=1;
							
							var style="opacity:"+now+" !important";
							style+=old_style;
							
							$(this).attr('style',style);
						}
			},duration:self.options.my_duration});
			}
		};	
		this.my_animate_elements_function=function(v){
			var scrollTop=$(window).scrollTop();
			var w_h=$(window).height();
			var bottom=scrollTop+w_h+self.options.gap;
			var p=scrollTop+w_h/2;
			$(v).css('WebkitPerspectiveOrigin',"50% "+p+"px");
			$(v).css("MozPerspectiveOrigin","50% "+p+"px");
			$(v).css("perspectiveOrigin","50% "+p+"px");
			$(v).addClass('my_animate_box');
			$(v).data('my_animated',1);
		};
		this.my_scroll_1=function(e){
			var top=$("#my_sogrid_id_"+self.options.id).offset().top;
			var height=$("#my_sogrid_id_"+self.options.id).height();
			var bottom=top+height-100;
			var scrollTop=$('.my_dialog_preview iframe').contentWindow.scrollTop();
			var w_h=$('.my_dialog_preview iframe').height();
			var bottom_window=scrollTop+w_h;
			self.my_debug("Scroll",{top:top,height:height,bottom:bottom,scrollTop:scrollTop,w_h:w_h,bottom:bottom_window});
			//if(bottom_window>bottom)
			if(bottom_window>=bottom){
				self.my_debug('Call ajax load more');
				self.my_load_more();
			}
		};
		this.my_scroll=function(e){
			var top=$("#my_sogrid_id_"+self.options.id).offset().top;
			var height=$("#my_sogrid_id_"+self.options.id).height();
			var bottom=top+height;
			var scrollTop=$(window).scrollTop();
			var w_h=$(window).height();
			var bottom_window=scrollTop+w_h;
			if(self.options.my_preview==1){
				bottom-=50;
			}
			self.my_debug("Scroll",{top:top,height:height,bottom:bottom,scrollTop:scrollTop,w_h:w_h,bottom:bottom_window});
			//if(bottom_window>bottom)
			if(bottom_window>bottom){
				self.my_debug('Call ajax load more');
				self.my_load_more();
			}
			
		};
		this.my_load_more=function(){
			/*
			 * Dont load elemebnts on resize
			 */
			if(self.my_start_resize)return;
			if(self.working)return;
			self.working=true;
			if(self.page==self.pages){
				self.my_working=false;
				return;
			}
			var pages=self.pages;
			var page=self.page;
			if(page>pages){
				self.my_working=false;
				return;
			}
			page+=1;
			var data={
					action:self.options.ajax_action,
					nonce:self.options.nonce,
					id:self.options.id,
					page:page,
					cache_time:self.cache_time,
					columns:self.my_columns
				};
			var my_id='my_sogrid_id_'+self.options.id;
			$("#"+my_id+" .my_sogrid_loading").css('visibility','visible');
			self.my_call_columns=self.my_columns;
			self.my_debug("data",data);
				$.ajax({
					url:self.options.ajax_url,
					dataType:'json',
					async:true,
					data:data,
					cache:false,
					
					timeout:self.options.ajax_timeout,
					type:'POST',
					success:function(data,status,jq){
						var my_id='my_sogrid_id_'+self.options.id;
						$("#"+my_id+" .my_sogrid_loading").css('visibility','hidden');
						
						self.working=false;
						self.my_debug("Data",data);
						if(data.error==1){
							alert(data.msg);
							
						}else if(data.no_cache){
							//setTimeout(self.my_load_more(),2000);
							setTimeout(function(){
								window.location.reload();
							},5000);
						}else if(data.error==0){
							self.page++;
							/*if(self.my_columns!=3){
								var lis
							}*/
							$items=$(data.items);
							if(self.options.dynamic_loading==1){
								$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms").isotope('insert',$items);
							}else {
								$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms").isotope('appended',$items);
							}
							if(self.my_call_columns!=self.my_columns){
								self.my_resize();
							}
							/*
							if(self.options.dynamic_loading_animation==1){
								setTimeout(function(){
									self.my_force=true;
									self.my_animate_elements();
									self.my_force=false;
								},700);
							}*/
							self.my_debug("Ids",data.ids);
							$.each(data.ids,function(i,v){
								/*if(self.options.dynamic_loading_animation==1){
									
								}*/
								
								$(".my_sogrid_container[data-my-id='"+self.options.id+"']  #"+v+" .my_social_item_inner_text").mCustomScrollbar();
							});
							if(typeof FB!='undefined'){
								FB.XFBML.parse();
							}else {
								self.my_debug("FB undfined");
							}
							if(typeof gapi!='undefined'){
								gapi.plusone.go();
								gapi.plus.go();
								gapi.ytsubscribe.go();
							}else {
								self.my_debug("gapi is undefined");
							}
							if(typeof window.parsePins !='undefined'){
								window.parsePins("my_sogrid_id_"+self.options.id);
							}
							//self.my_load_image();
							/*setTimeout(function(){
								self.my_set_google_plus_iframe();
								},500);*/
							
						}
						/*self.my_debug("Save SoGrid",data);
						if(typeof data.debug_data!='undefined'){
							self.my_debug("Debug data",data.debug_date);
							
						}
						$("#save-loader").hide();
						if(data.error==0){
							alert(data.msg);
							var my_id=data.id;
							$("#sogrid_id").val(my_id);
						}else {
							alert(data.msg);
						}
						self.working=false;
						*/
				},
				error:function(jq,status,errorhttp){
					var my_id='my_sogrid_id_'+self.options.id;
					$("#"+my_id+" .my_sogrid_loading").css('visibility','hidden');
					
					self.working=false;
					$("#save-loader").hide();
					
					alert("Error "+status);
				}

			});
			
		};
		this.my_show_window=function(e){
			e.preventDefault();
			var href=$(this).attr('href');
			var service=$(this).data('service');
			self.my_debug("Openb share window",{href:href,service:service});
			//self.getViewport();
			//var v_ww=self.viewport['w'];
			//var v_hh=self.viewport['h'];
			var v_ww=window.outerWidth;
			var v_hh=window.outerHeight;
			var ww=800;
			var hh=400;
			if(service=='google'){
				
			}
			var my_is_twitter=0;
			if($(this).hasClass('my_social_share_link_twitter')){
				self.my_a_obj=$(this);
				self.my_c_t_12=0;
				my_is_twitter=1;
				//console.log('Twiiter Class');
			}
			if(v_ww<ww){
				ww=v_ww;
			}
			if(v_hh<hh){
				hh=v_hh;
			}
			var left=Math.floor((v_ww-ww)/2);
			var top=Math.floor((v_hh-hh)/2);
			self.my_debug("Window",{ww:ww,hh:hh,v_ww:v_ww,v_hh:v_hh,top:top,left:left});
			var win=window.open(this.href, "my_social_share", "scrollbars=1,menubar=0,location=0,toolbar=0,status=0,width="+ww+", height="+hh+", top="+top+", left="+left);
			if(my_is_twitter){
				$(win).unload(self.my_check_twitter);
				//setTimeout(self.my_is_closed_win,500);
			}
		};
		this.my_is_closed_win=function(){
			if(typeof self.my_win=='undefined'){
				//console.log('Undefined');
				self.my_check_twitter();
			}else {
				setTimeout(self.my_is_closed_win,500);
			}
			
		};
		this.my_check_twitter=function(){
			var c=parseInt($(self.my_a_obj).data('c'));
			var post_id=$(self.my_a_obj).data('id');
			var t=$(self.my_a_obj).data('t');
			//console.log('Close window',{c:c,post_id:post_id,t:t});
			/*if(self.my_c_t_12==0){
				self.my_c_t_12++;
				return;
			}*/
			self.my_t_post_id=post_id;
			self.my_t_c=c;
			self.my_t=t;
			if(c<1000){
				setTimeout(function(){
					var post_id=self.my_t_post_id;
					var c=self.my_t_c;
					var t=self.my_t;
					self.update_posts('twitter', post_id,c,t);
				},7000);
				setTimeout(function(){
					var post_id=self.my_t_post_id;
					var c=self.my_t_c;
					var t=self.my_t;
					self.update_posts('twitter', post_id,c,t);
				},25000);
			}
		};
		this.init_plusone=function(){
			//gapi.plusone.render(".my_social_item_share_google" );
			$(window).load(function(e){
			$(".my_sogrid_plusone").each(function(i,v){
				var ww=$(v).find(".my_sogrid_plusone_tweek").outerWidth();
				var hh=$(v).find(".my_sogrid_plusone_tweek").height();
				self.my_debug("Plusone iframe width",{ww:ww,hh:hh});
				$(v).find('div:eq(1)').width(ww);
				$(v).find('div:eq(1)').height(hh);
				$(v).find('iframe').width(ww);
				$(v).find('iframe').height(hh);
				
				
				
				
			});
			});
			
		};
		this.init_widths=function(){
			var ww=$(".my_sogrid_container[data-my-id='"+self.options.id+"']").width();//$(window).width();
			self.my_debug("Change widths",ww);
			var l=$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").length;
			self.my_debug("Change widths",l);
			$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass("my_no_padding_right");
			var my_cont_width=$(".my_sogrid_container[data-my-id='"+self.options.id+"']").width();
			/*if((self.my_columns==1)||(self.my_columns==2)){
				
			}*/
			if(typeof self.my_columns!='undefined'){
				self.my_old_columns=self.my_columns;
			}
			/*if(ww>1200){
				my_item_width=Math.floor(my_cont_width/4);
				self.my_columns=4;
				self.my_debug("Add class 25");
				//$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li:nth-child(4n)").addClass('my_no_padding_right');
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_50');
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_100');
				//$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_25');
				
				
				
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").addClass('my_sogrid_25');
			}else 
			*/
			//console.log('Width',ww);
			if(900<ww){
				self.my_debug("Normal view");
				//$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li:nth-child(3n)").addClass('my_no_padding_right');
				self.my_columns=3;
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_no_padding_right');
				
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_50');
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_100');
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_25');
				my_item_width=Math.floor(my_cont_width/3);
				
			}
			else if((600<ww)&&(ww<=900)){
				self.my_columns=2;
				self.my_debug("Add class 50");
				//$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li:nth-child(2n)").addClass('my_no_padding_right');
				my_item_width=Math.floor(my_cont_width/2);
				
				//$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_50');
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_100');
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_25');
				
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").addClass('my_sogrid_50');
			}else {
				self.my_columns=1;
				my_item_width=my_cont_width;
				self.my_debug("Add class 100");
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").addClass('my_no_padding_right');
				
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_50');
				//$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_100');
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_sogrid_25');
				
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").addClass('my_sogrid_100');
				
			}
			self.my_cont_width=my_cont_width;
			self.my_item_width=my_item_width;
		};
		this.my_set_padding=function($elems,instance){
			var w=self.my_item_width;
			self.my_debug("ON layout",w);
			$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_no_padding_right');
			
			if(self.my_columns==1){
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").addClass('my_no_padding_right');
			}else {
				var t=(self.my_columns-1)*w;
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").each(function(i,v){
					var left=parseFloat($(v).css('left'));
					self.my_debug("Left",left);
					if(left>t){
						self.my_debug("Add class no padding",{id:$(v).attr('id'),left:left,t:t})
						$(v).addClass("my_no_padding_right");
					}
				});
			}
		};
		this.getViewport=function(){
			var h;
			var w;
			if(document.compatMode==='BackCompat'){
				h=document.body.clientHeight;
				w=document.body.clientWidth;
			}else {
				h=document.documentElement.clientHeight;
				w=document.documentElement.clientWidth;
			}
			self.viewport={
					w:w,
					h:h
			};
			self.my_debug('Viewport',self.viewport);
		};

		this.my_resize=function(e){
			self.my_start_resize=true;
			var ww=$(window).width();
			/*if(ww>1200){
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").css('width','25% !important');
			}
			else if(600<ww<800){
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").css('width','50% !important');
			}else {
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").css('width','100% !important');
			}*/
			self.init_widths();
			setTimeout(function(){
				/*var scrollTop=$(window).scrollTop();
				var w_h=$(window).height();
				var bottom=scrollTop+w_h;
				if(self.options.dynamic_loading_animation==1){
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul li").each(function(i,v){
					var old_style=self.old_style(v);
					var top=$(v).offset().top;
					if(top<bottom)old_style+='opacity:1 !important;';
					$(v).attr('style',old_style);
				});
				}*/
				if(typeof $(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms").isotope!='undefined'){
					$("#my_sogrid_id_"+self.options.id).find('.my_sogrid_thumb img').data('my_adjusted',0);
					//console.log('Resize call layout',ww);
					$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms").isotope('layout');
					//setTimeout(self.is_finish_resize,700);
				}
				
				//self.my_load_image();
				//self.my_scale_image();
				//self.my_start_resize=false;
				//$(".my_sogrid_container[data-my-id='"+self.options.id+"'] .my_social_item_inner_text ").mCustomScrollbar('update')
			},300);
			
		};
		this.is_finish_layout=function(){
			var scrollTop=$(window).scrollTop();
			var w_h=$(window).height();
			var bottom=scrollTop+w_h;
			
			
			if(self.options.dynamic_loading_animation==1){
				$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul li").each(function(i,v){
					var top=$(v).offset().top;
					if(top>bottom)$(v).data('my_animated',0);
					else {
						$(v).data('my_animated',1);
						old_style=self.old_style(v);
						old_style+='opacity:1 !important;';
						$(v).attr('style',old_style);
					}
				});
			}
			
		}
		this.center_isotope_1=function(){
			var width=$(".my_sogrid_container[data-my-id='"+self.options.id+"']").width();
			var columns=Math.floor(width/self.options.width);
			self.my_debug("Columns",columns);
			var m=Math.floor((width-columns*self.options.width)/2);
			self.my_debug("Margin",m);
			
			$(".my_sogrid_itms[data-my-id='"+self.options.id+"']").parent(".my_sogrid_inner").css('margin-left',m);
		};
		this.center_isotope=function(){
			 jQuery.Isotope.prototype._getCenteredMasonryColumns = function() {
				    this.width = this.element.width();
				    
				    var parentWidth = this.element.parent().width();
				    
				                  // i.e. options.masonry && options.masonry.columnWidth
				    var colW = this.options.masonry && this.options.masonry.columnWidth ||
				                  // or use the size of the first item
				                  this.$filteredAtoms.outerWidth(true) ||
				                  // if there's no items, use size of container
				                  parentWidth;
				    
				    var cols = Math.floor( parentWidth / colW );
				    cols = Math.max( cols, 1 );

				    // i.e. this.masonry.cols = ....
				    this.masonry.cols = cols;
				    // i.e. this.masonry.columnWidth = ...
				    this.masonry.columnWidth = colW;
				  };
				  
				  jQuery.Isotope.prototype._masonryReset = function() {
				    // layout-specific props
				    this.masonry = {};
				    // FIXME shouldn't have to call this again
				    this._getCenteredMasonryColumns();
				    var i = this.masonry.cols;
				    this.masonry.colYs = [];
				    while (i--) {
				      this.masonry.colYs.push( 0 );
				    }
				  };

				  $.Isotope.prototype._masonryResizeChanged = function() {
				    var prevColCount = this.masonry.cols;
				    // get updated colCount
				    this._getCenteredMasonryColumns();
				    return ( this.masonry.cols !== prevColCount );
				  };
				  
				  $.Isotope.prototype._masonryGetContainerSize = function() {
				    var unusedCols = 0,
				        i = this.masonry.cols;
				    // count unused columns
				    while ( --i ) {
				      if ( this.masonry.colYs[i] !== 0 ) {
				        break;
				      }
				      unusedCols++;
				    }
				    
				    return {
				          height : Math.max.apply( Math, this.masonry.colYs ),
				          // fit container to columns that have been used;
				          width : (this.masonry.cols - unusedCols) * this.masonry.columnWidth
				        };
				  };
		};
		this.my_sort_items=function(){
			var active_networks=self.options.networks;
			self.my_debug("Active newtworks",active_networks);
			self.has_thumb_c=0;
			self.no_thumb_c=0;
			$.each(active_networks,function(i,v){
				self.networks_posts[v]={};
				self.networks_posts[v].has_thumb=[];
				self.networks_posts[v].no_thumb=[];
				$(".my_sogrid_itms[data-my-id='"+self.options.id+"'] li[data-my-type='"+v+"']").each(function(i1,v1){
					var obj={};
					obj.has_thumb=$(v1).data('my-has-thumb');
					obj.id=$(v1).attr('id');
					if(obj.has_thumb){
						self.has_thumb_c++;
						self.networks_posts[v].has_thumb[self.networks_posts[v].has_thumb.length]=obj;
					}else {
						self.no_thumb_c++;
						self.networks_posts[v].no_thumb[self.networks_posts[v].no_thumb.length]=obj;
					}
					
				});
				
			});
			var total=self.has_thumb_c+self.no_thumb_c;
			var added=0;
			var c_network=0;
			var network='';
			var c_option=0;
			var c_total_networks=active_networks.length;
			var c_total_option=self.options.content.length;
			var option_str='';
			var el;
			var c=0;
			var no_thumb_else=false;
			var start_added="";
			self.my_debug("Total items",total);
			while(added<total){
				network=active_networks[c_network];
				option_str=self.options.content[c_option];
				self.my_debug("Network",{n:network,option_str:option_str});
				if(!no_thumb_else){
				if(option_str=='t'){
					//start_added="";
					el=self.my_find_thumb(network);
					if(el===false){
							no_thumb_else=true;
						}
						else $("#"+el).data('my-show',added);
					}else{
						/*if(start_added==""){
							start_added=added;
						}*/
						el=self.my_find_no_thumb(network);
						$("#"+el).data('my-show',added);
					}
				}
				if(no_thumb_else){
					el=self.my_find_no_thumb(network);
					$("#"+el).data('my-show',added);
				}
				self.my_debug("Added element "+added,{el:el,added:added})
				c++;
				added++;
				c_network++;
				if(c_network==c_total_networks)c_network=0;
				
				c_option++;
				if(c_option==c_total_option)c_option=0;
				//if(c>total)break;
			}
			
			self.my_debug("Networks posts",self.networks_posts);
			
			
			
		};
		this.my_find_no_thumb=function(n){
			var active_networks=self.options.networks;
			var found=false;
			var el="";
			if(self.networks_posts[n].no_thumb.length==0){
				$.each(active_networks,function(i,v){
					if(v!=n){
						if(self.networks_posts[v].no_thumb.length>0){
							found=true;
							var obj=self.networks_posts[v].no_thumb[0];
							self.networks_posts[v].no_thumb.splice(0,1);
							el=obj.id;
							return false;
						}
					}
				});
			}else {
				found=true;
				var obj=self.networks_posts[n].no_thumb[0];
				el=obj.id;
				self.networks_posts[n].no_thumb.splice(0,1);
			}
			//self.my_debug("Find no thumb",{n:n,el:el,found:found});
			if(!found){
				el=self.my_find_thumb(n);
				//self.my_debug("Find thumb",{n:n,el:el,found:found});
				if(el!==false){
					self.my_debug("****Find thumb****",{el:el,n:n});
					$("#"+el).find(".my_sogrid_thumb").hide();
					$("#"+el).removeClass("my_social_item_has_thumb");
					$("#"+el).data('my-has-thumb',0);
					return el;
				}else return false;	
				
			}else {
				return el;
			}
		};
		this.my_find_thumb=function(n){
			var active_networks=self.options.networks;
			var found=false;
			var el="";
			if(self.networks_posts[n].has_thumb.length==0){
				$.each(active_networks,function(i,v){
					if(v!=n){
						if(self.networks_posts[v].has_thumb.length>0){
							found=true;
							var obj=self.networks_posts[v].has_thumb[0];
							self.networks_posts[v].has_thumb.splice(0,1);
							el=obj.id;
							self.my_debug("**Found other has_thumb****"+v,{el:el,n:n});
							return false;
						}
					}
				});
			}else {
				found=true;
				var obj=self.networks_posts[n].has_thumb[0];
				el=obj.id;
				self.networks_posts[n].has_thumb.splice(0,1);
			}
			if(!found){
				return false;
			}else return el;
		};
		/*
		 * changes define layout mode
		 */
		//this.init_dynamic_grid=function(window){
		this.dynamicGridDefinition=function( LayoutMode ) {

		var dynamicGrid = LayoutMode.create('dynamicGrid');

		dynamicGrid.prototype._resetLayout = function() {
		  this.x = 0;
		  this.y = 0;
		  this.maxY = 0;
		  delete this.mymaxY;
		  this.mymaxY=0;
		  this._getMeasurement( 'gutter', 'outerWidth' );
		  this.my_c=0;
		  this.my_counter=1;
		  this.my_has_thumbs=true;
		  //var ww=this.containerWidth;
		};
		/*dynamicGrid.prototype._dynamicGridLayout=function($elems){
			$.each($elems,function(i,v){
				var pos=this._getItemLayoutPosition(v);
				_pushPosition( $this, pos.x, pos.y );

			});
			
		};*/	
		dynamicGrid.prototype._getItemLayoutPosition = function( item ) {
		  item.getSize();
		  var my_id=jQuery(item.element).attr('id');
		  var my_show=jQuery(item.element).data('my-show');
		  var has_thumb=jQuery(item.element).data('my-has-thumb');
		  var itemWidth = item.size.outerWidth + this.gutter;
		  var itemHeight = Math.round(item.size.outerHeight) + this.gutter;
		  var network=jQuery(item.element).data('my-type');
		  var my_options_c=this.isotope.options.my_content;
		  var my_ctstr=my_options_c[this.my_c];
		  this.my_c++;
		 
		  if(this.my_c==my_options_c.length)this.my_c=0;
		  
		 /* if(window.console){
			  console.log('my_options_c '+this.my_c,my_ctstr);
		  }*/
		  /*if(window.console){
			  console.log('Item id='+my_id+" show "+my_show+" has thumb "+has_thumb,{network:network,itemHeight:itemHeight,item:item,itemWidth:itemWidth});
		  }*/
		  /*if(typeof this.mymaxY=='undefined'){
			  this.mymaxY=0;
		  }*/
		  // if this element cannot fit in the current row
		  var containerWidth = this.isotope.size.innerWidth + this.gutter;
		  var my_columns=Math.round(containerWidth/itemWidth);
		  /*if(window.console){
			  console.log('My columns',{columns:my_columns,counter:this.my_counter,id:my_id});
		  }*/
		 // jQuery(item.element).removeClass('my_no_padding_right');
		  var position = {
				    x: this.x,
				    y: this.y
				  };
		  if(has_thumb){
			  this.my_added_no_thumb=false;
			  /*if(typeof this.my_has_thumb!='undefined' && !this.my_has_thumb){
				  if ( (this.x !== 0) && (itemWidth + this.x > containerWidth) ) {
					    this.x = 0;
					    this.y = this.maxY;
					    this.maxY+=itemHeight;
					    this.y=this.maxY;
				  }else {
					  this.y=this.maxY-itemHeight;
				  }
			  }else {
			  */
			  this.my_has_thumb=true;
			  if ( (this.x !== 0) && (itemWidth + this.x > containerWidth) ) {
				  	this.maxY+=itemHeight;
				  	this.mymaxY+=itemHeight;
				  	this.x = 0;
				    this.y = this.mymaxY;
				    
			  }
			 
			  if(this.my_counter==my_columns){
					 /* if(window.console){
						  var id=jQuery(item.element).attr('id');
						  console.log("Add no padding ",{id:id});
					  }*/
					  //jQuery(item.element).addClass('my_no_padding_right');
					  /*if(window.console){
						  console.log('****Add no_padding***"',{columns:my_columns,counter:this.my_counter,id:my_id});
					  }*/
					  this.my_counter=1;
				  }else {
					  this.my_counter++;	  
				  }
			  this.y=this.mymaxY;//this.maxY;
			  //}
			  position = {
					    x: this.x,
					    y: this.y
					  };
			  this.x+=itemWidth;
			  this.maxY = Math.max( this.maxY, this.y + itemHeight );
			  
			  //this.maxY=MATH.maxitemHeight;
		  }else {
			 /* if(my_ctstr=='t'){
				  if(window.console){
					  console.log('No more thumbs');
				  }
				  this.my_has_thumbs=false;
			  }*/
			  if(this.my_has_thumbs){
			  if(this.my_added_no_thumb){
				  if ( (this.x !== 0) && (itemWidth + this.x > containerWidth) ) {
					    this.x = 0;
					    this.y = this.maxY;
					  }
				  this.y=this.mymaxY+itemHeight;
				  this.my_added_no_thumb=false;
				  position = {
						    x: this.x,
						    y: this.y
						  };
				  this.x+=itemWidth;
				  this.maxY = Math.max( this.maxY, this.y + itemHeight );
				  if(this.my_counter==my_columns){
						 /* if(window.console){
							  var id=jQuery(item.element).attr('id');
							  console.log("Add no padding ",{id:id});
						  }*/
						  /*jQuery(item.element).addClass('my_no_padding_right');
						  if(window.console){
							  console.log('****Add no_padding***"',{columns:my_columns,counter:this.my_counter,id:my_id});
						  }*/
						  this.my_counter=1;
					  }else {
						  this.my_counter++;
					  }
			  }else {
				  this.my_added_no_thumb=true;
				  this.my_has_thumb=false;
			  if ( (this.x !== 0) && (itemWidth + this.x > containerWidth) ) {
				  	this.maxY+=2*itemHeight;
				  	this.mymaxY+=2*itemHeight;
				    this.x = 0;
				    this.y = this.mymaxY;//this.maxY;
				  }else {
					  this.y=this.mymaxY;//this.maxY;
				  }
			  position = {
					    x: this.x,
					    y: this.y
					  };
			  if(this.my_counter==my_columns){
					 /* if(window.console){
						  var id=jQuery(item.element).attr('id');
						  console.log("Add no padding ",{id:id});
					  }*/
					 /* jQuery(item.element).addClass('my_no_padding_right');
					  if(window.console){
						  console.log('****Add no_padding***"',{columns:my_columns,counter:this.my_counter,id:my_id});
					  }*/
					 // this.my_counter=1;
				  }else {
					  
				  }
			  
			 // this.maxY+=itemHeight;
			  }
			  }else {
				  if ( (this.x !== 0) && (itemWidth + this.x > containerWidth) ) {
					  	this.maxY+=itemHeight;
					  	this.mymaxY+=itemHeight;
					    this.x = 0;
					    this.y = this.mymaxY;//this.maxY;
					  }else {
						  this.y=this.mymaxY;//this.maxY;
					  }
				  this.maxY = Math.max( this.maxY, this.y + itemHeight );
				  position = {
						    x: this.x,
						    y: this.y
						  };
				  
				 
			  }
		  }
		 
		  /*if(window.console){
			  console.log("Position",position);
		  }*/
		  
		  /*if(has_thumb){
			  this.x += itemWidth;
		  }*/
		  
		  //this.maxY = Math.max( this.maxY, this.y + item.size.outerHeight );
			 
		  /*if ( this.x !== 0 && itemWidth + this.x > containerWidth ) {
		    this.x = 0;
		    this.y = this.maxY;
		  }

		  var position = {
		    x: this.x,
		    y: this.y
		  };

		  this.maxY = Math.max( this.maxY, this.y + item.size.outerHeight );
		  this.x += itemWidth;
			*/
		 
		  return position;
		};

		dynamicGrid.prototype._getContainerSize = function() {
		  return { height: this.maxY };
		};

		return dynamicGrid;

		}

		/*if ( typeof define === 'function' && define.amd ) {
		  // AMD
		  define( [
		      '../layout-mode'
		    ],
		    dynamicGridDefinition );
		} else if ( typeof exports === 'object' ) {
		  // CommonJS
		  module.exports = dynamicGridDefinition(
		    require('../layout-mode')
		  );
		} else {
		  // browser global
		  dynamicGridDefinition(
		    window.Isotope.LayoutMode
		  );
		}*/

		

		//}
		this.init_isotope=function(){
			var o={
					 
					// isLayoutInstant:true,
					itemSelector : 'li.my_social_item',
					transitionDuration : self.options.transition,
					itemPositionDataEnabled:true,
					onlayoutComplete:function(instance,elems){
						//alert('Layoput complete');
						//self.my_debug("Layout complete");
						//console.log("Layout complete option");
						return;
						if(self.options.dynamic_loading_animation==1){
							$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul li").each(function(i,v){
								var scrollTop=$(window).scrollTop();
								var w_h=$(window).height();
								var bottom=scrollTop+w_h;
								var s=$(v).data("my_animated");
								var s_stop=bottom+w_h;
								if(s==0 || (typeof s=='undefined')){
									var top=$(v).offset().top;
									if(top>s_top)return false;
									if(top<bottom){
										self.my_animate_elements_function(v);
									}
								}
							});
						}},
					onlayout:function($elem,instance){
						//console.log("OnLayout complete option");
						
						return;
						var w=self.my_item_width;
						self.my_debug("ON layout",w);
						$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").removeClass('my_no_padding_right');
						
						if(self.my_columns==1){
							$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").addClass('my_no_padding_right');
						}else {
							var t=(self.my_columns-1)*w;
							$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms li").each(function(i,v){
								var left=parseFloat($(v).css('left'));
								self.my_debug("Left",left);
								if(left>t){
									self.my_debug("Add class no padding",{id:$(v).attr('id'),left:left,t:t})
									$(v).addClass("my_no_padding_right");
								}
							});
						}
					}
					
					/*isFitWidth: true*/
					
			};
			if(self.options.dynamic_loading_animation==1){
				o.isLayoutInstant=true;
			}
			if(self.options.dynamic_grid){
				self.my_debug("Dynamic Grid");
				
				/*
				 * changes 1.27.2015
				 */
				self.dynamicGridDefinition(window.Isotope.LayoutMode);
				/*
				 * addd layout mode
				 */ 
				/*dynamicGridDefinition(
						    window.Isotope.LayoutMode
						  );*/
				/*if(self.options.dynamic_loading==0){
					self.my_sort_items();
				}*/	
					o.my_content=self.options.content,
					o.layoutMode="dynamicGrid";
					if(self.options.dynamic_loading==0){
						
					o.getSortData={
						myShow : function(itemElem) {
							var el_id=$(itemElem).attr('id');
							var val=parseInt($(itemElem).data('my-show'), 10);
							self.my_debug("*****Element ****** "+el_id+" val="+val);
							return val;
						}
					};
					o.sortBy='myShow';
					}
					o.sortAscending=true;
				//}	
				
			}else {
				if(self.options.dynamic_loading==0){
					/*if(self.options.sort_date){
			
						o.getSortData={
								postDate : function(itemElem) {
						return parseInt($(itemElem).data('published'), 10);
								}
						};
						o.sortBy='postDate';
						o.sortAscending=false;
						*/
			}else {
						//o.sortBy='random';
			}
				}
			//}
			self.my_debug("Isotope Options",o);
			var $c=$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms").isotope(o);
			$c.isotope('on','layoutComplete',function(instance,elems){
				//return;
				//self.correct_border(1);
				var old_c=self.my_old_columns;
				var new_c=self.my_columns;
				//console.log('Layout complete');
				self.my_load_image();
				//$('.my_sogrid_plusone iframe').load(self.my_load_iframe);
				/*if(self.my_start_resize&&(old_c==1&&((new_c==2)||(new_c==3)))||(old_c==2&&new_c==3)){
					var h=$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms").height();
					var w_h=$(window).height();
					var t=h-w_h;
					console.log('Scroll top',{t:t,h:h,w_h:w_h});
					$(window).scrollTop(t);
				}*/
				var ww=$(window).width();
				//console.log("Layout complete check on resize",{ww:ww,my_start_resize:self.my_start_resize});
				if(self.options.dynamic_loading_animation==0){
					self.my_start_resize=false;
				}
				if(self.options.dynamic_loading_animation==1){
					if(self.my_start_resize&&(old_c==1&&((new_c==2)||(new_c==3)))||(old_c==2&&new_c==3)){
						setTimeout(function(){
						var h=$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul.my_sogrid_itms").height();
						var w_h=$(window).height();
						var t=h-w_h;
						//console.log('Scroll top',{t:t,h:h,w_h:w_h});
						$(window).scrollTop(t);
						},500);
					}
				if(!self.my_start_resize){
					self.my_force=true;
					self.my_animate_elements();
					self.my_force=false;
				}
				if(self.my_start_resize){
				//console.log('Finish layout elements');
				self.is_finish_layout();
				self.my_start_resize=false;
				return;
				//return;
				//self.my_debug("Layout complete");
				if(self.my_start_resize){
				var scrollTop=$(window).scrollTop();
				var w_h=$(window).height();
				var bottom=scrollTop+w_h;
				
				
				if(self.options.dynamic_loading_animation==1){
					$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul li").each(function(i,v){
						var top=$(v).offset().top;
						if(top>bottom)$(v).data('my_animated',0);
						else {
							$(v).data('my_animated',1);
							old_style=self.old_style(v);
							old_style+='opacity:1 !important;';
							$(v).attr('style',old_style);
						}
					});
				}
				
				}
			}}
				return;
				//alert('Layout complete '+self.options.dynamic_loading_animation);
				if(self.options.dynamic_loading_animation==1){
					$(".my_sogrid_container[data-my-id='"+self.options.id+"'] ul li").each(function(i,v){
						var scrollTop=$(window).scrollTop();
						var w_h=$(window).height();
						var bottom=scrollTop+w_h;
						var s=$(v).data("my_animated");
						var s_stop=bottom+w_h;
						var p=scrollTop+w_h/2;
						//console.log('Animate element',{s:s,bottom:bottom});
						if(s==0 || (typeof s=='undefined')){
							var top=$(v).offset().top;
							if(top>s_stop)return false;
							if(top<bottom){
								$(v).data('my_animated',1);
								self.my_animate_elements_function_1(v,p);
							}
						}
					});
				}
			});
			
			
		};
		this.my_debug=function(t,o){
			if(self.debug){
				if(window.console){
					console.log("***Front SoGrid *** \n"+t,o);
				}
			}
		};
		this.init(o);
	};
})(jQuery);