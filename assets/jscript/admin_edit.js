(function($) {
	wpMySoGridAdmin=function(o){
		var self;
		self=this;
		self.debug=false;
		self.my_picker_name="";
		self.active_so={};
		self.inactive_so={};
		self.working=false;
		self.my_required={text:"input[type='text']"};
		self.validate_form=true;
		self.checked_values={};
		/*
		 * changes 1.25.2015.
		 */
		self.is_dynamic=true;
		self.preview_w=1050;
		self.preview_h=550;
		this.init=function(o){
			self.my_debug("Options",o);
			self.options=o;
			//$("#google").change(self.my_activate_so);
			$(".my_general_social_options .imapper-checkbox-on,.my_general_social_options .imapper-checkbox-off ").click(self.my_activate_so);
			$("#save-timeline").click(self.my_save);
			$(".imapper_items_options input[type='hidden']").each(function(i,v){
				var val=$(v).val();
				self.active_so[val]=1;
			});
			self.my_debug("Active Social",self.active_so);
			$(".my_general_social_options input[type='checkbox']").each(function(i,v){
				var checked=$(v).is(":checked");
				var name=$(v).attr('name');
				self.checked_values[name]=checked;
			});
			self.my_debug("Checked",self.checked_values);
			self.init_tooltips();
			/*
			 * changes 1.23.2015.
			 */
			$.each(self.checked_values,function(i,v){
				self.my_init_color_picker("#my_social_"+i+" .my_color_picker_title");
				
				
			});
			self.is_dynamic=$("input[name='my_general_option_dynamic']").is(":checked");
			self.my_debug("Is dynamic",self.is_dynamic);
			if(self.is_dynamic){
				self.update_checks(1);
			}
			$(document).on('click','.imapper-checkbox-on',self.my_check_dynamic);
			$(document).on('click','.imapper-checkbox-off',self.my_check_dynamic);
			
			$(document).on('click',".my_change_color_input_12",function(e){
				e.stopPropagation();
				
			});
			$(document).on('click','.my_color_picker_title',self.my_open_color_picker);
			/*
			 * end changes
			 */
			/*
			 * changed add preview
			 */
			var w_123=self.myGetViewport();
			var preview_w,preview_h;
			preview_w=self.preview_w;
			preview_h=self.preview_h;
			if(w_123.w<preview_w){
				preview_w=w_123.w-200;
			}
			if(w_123.h<preview_h){
				preview_h=w_123.h-200;
			}
			$(".my_dialog_preview").dialog({
				width:preview_w,
				height:preview_h,
				autoOpen:false,
				open:function(){
					self.my_debug("Open dialog");
					//$(".my_dialog_preview iframe").contents().find("#my-save-loader").show();
					$(".my_dialog_preview iframe").bind('load',function(e){
						self.my_debug("Loaded");
						$("#my-save-loader").hide();
						$(".my_dialog_preview iframe").animate({opacity:1});
						
					});
					
				},
				close:function(){
					self.my_debug("Close dialog");
					//$(".my_dialog_preview iframe").contents().find("#my-save-loader").show();
					$(".my_dialog_preview #my-save-loader").show();
					$(".my_dialog_preview iframe").css('opacity',0);
					
					
				}
			});
			$("#preview-timeline").click(self.my_open_window);
			$(window).resize(function(e){
				var w_123=self.myGetViewport();
				var preview_w,preview_h;
				preview_w=self.preview_w;
				preview_h=self.preview_h;
				if(w_123.w<preview_w){
					preview_w=w_123.w-200;
				}
				if(w_123.h<preview_h){
					preview_h=w_123.h-200;
				}
				var top=(w_123.h-preview_h)/2;
				var left=(w_123.w-preview_w)/2;
				$(".ui-dialog").css('top',top+'px');
				$(".ui-dialog").css('left',left+'px');
				
				$(".ui-dialog").css('width',preview_w+'px');
				$(".ui-dialog").css('height',preview_h+'px');
				
				//$(".my_dialog_preview").dialog("option","width",self.preview_w);
				//$(".my_dialog_preview").dialog("option","height",self.preview_h);
				
				
			});
		};
		this.myGetViewport=function(){
			var w;
			var h;
			if(document.compatMode=='BackCompat'){
				w=document.body.clientWidth;
				h=document.body.clientHeight;
			}else {
				w=document.documentElement.clientWidth;
				h=document.documentElement.clientHeight;
				
			}	
			var obj={w:w,h:h};
			//console.log("Obj",obj);
			return obj;
			
		};
		/*
		 * changes 1.20.2015.
		 * preview
		 */
		this.check_name=function(name){
			if(name.indexOf('intro')!==-1)return true;
			if(name.indexOf('title')!==-1)return true;
			if(name.indexOf('text')!==-1)return true;
			if(name.indexOf('thumb')!==-1)return true;
			if(name.indexOf('share')!==-1)return true;
			if(name.indexOf('author_box')!==-1)return true;
			if(name.indexOf('show_metadata')!==-1)return true;
			
			
			
			return false;
		};
		this.update_checks=function(on){
			if(on){
				self.is_dynamic=true;
				$(".imapper-sortableItem [type='checkbox']").each(function(i,v){
					var name=$(v).attr('name');
					//self.my_debug("Check name",name);
					if(self.check_name(name)){
						self.my_debug("Found name",name);
						$(this).siblings(".imapper-checkbox-span").css('opacity','0.4');
						$(this).siblings(".imapper-checkbox-span").addClass('my_disabled');
						
					}
					
				});
			}else {
				self.is_dynamic=false;
				$(".imapper-sortableItem [type='checkbox']").each(function(i,v){
					var name=$(v).attr('name');
					//self.my_debug("Check name",name);
					if(self.check_name(name)){
						self.my_debug("Found name",name);
						$(this).siblings(".imapper-checkbox-span").css('opacity','');
						$(this).siblings(".imapper-checkbox-span").removeClass('my_disabled');
						
					}
					
				});
			}
		};
		this.my_check_dynamic=function(e){
			var my_class=$(this).attr('class');
			var name=$(this).siblings("[type='checkbox']").attr('name');
			self.my_debug("*****Check dynamic **** ",name);
			if(name=='my_general_option_dynamic'){
				if($(this).hasClass("imapper-checkbox-on")){
				self.my_debug("****Enable dynamic****");	
					//disbale other options
				self.is_dynamic=true;	
				self.update_checks(1);
				}else {
					self.my_debug("*****Disable dynamic*****");
					self.is_dynamic=false;	
				self.update_checks(0);	
					
				}
			}
		};
		
		this.my_open_color_picker=function(e){
			e.stopPropagation();
			var new_name=$(this).attr('my_name');
			var my_current_color_picker=self.my_picker_name;
			if(self.my_picker_name==new_name){
				
				$(".my_color_picker_title[my_name='"+my_current_color_picker+"'] .my_select_picker").show();
				$(".my_color_picker_title[my_name='"+my_current_color_picker+"'] .my_close_picker").hide();
				$(".my_color_picker_title[my_name='"+my_current_color_picker+"']").iris("hide");
				self.my_picker_name="";
			}else {
				if(my_current_color_picker!=""){
				$(".my_color_picker_title[my_name='"+my_current_color_picker+"']").iris("hide");
					//my_current_color_picker="";
					self.my_picker_name="";
				}
				my_current_color_picker=$(this).attr('my_name');
				$(".my_color_picker_title[my_name='"+my_current_color_picker+"'] .my_select_picker").hide();
				$(".my_color_picker_title[my_name='"+my_current_color_picker+"'] .my_close_picker").show();
				
				
				self.my_debug("Show picker",my_current_color_picker);
				$(this).iris('show');
				self.my_picker_name=my_current_color_picker;
				
			}
		};
		this.my_open_window=function(e){
			e.preventDefault();
			var id=$("#sogrid_id").val();
			if(id==""){
				alert(self.options.msgs.preview_save);
				return;
			}
			var url=self.options.preview_url;
			url=url.replace('{id}',id);
			self.my_debug("URL",url);
			$(".my_dialog_preview iframe").attr('src',url);
			$(".my_dialog_preview").dialog('open');
		};
		/*
		 * end changes
		 */
		this.check_required=function(){
			self.validate_form=true;
			$.each(self.my_required,function(i,v){
				$("#post_form "+v).each(function(i1,v1){
					var required=$(v1).data('my-required');
					self.my_debug("Element",{i:i,req:required});
					if((typeof required!='undefined')&&(required)){
						var id=$(v1).attr('id');
						var val;
						if(i=='text')val=$("#post_form #"+id).val();
						if(val==""){
							var label_field=$('label[for="'+id+'"]').text();
							var msg=self.options.msgs.field_is_required;
							msg=msg.replace('{1}',label_field);
							self.validate_form=false;
							$("#post_form #"+id).focus();
							alert(msg);
							return false;
							
						}
						
					}
				});
			});
		};
		this.my_save=function(e){
			e.preventDefault();
			self.my_debug("Save So Grid");
			if(self.working)return;
			self.working=true;
			
			self.check_required();
			if(!self.validate_form){
				self.working=false;
				return;
			}
			$("#save-loader").show();
			var data={
				action:self.options.ajax_action,
				my_action:'save_sogrid',
				data:$("#post_form").serialize()
			};
			$.ajax({
				url:self.options.ajax_url,
				dataType:'json',
				async:false,
				data:data,
				cache:false,
				
				timeout:self.options.ajax_timeout,
				type:'POST',
				success:function(data,status,jq){
					self.my_debug("Save SoGrid",data);
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
			},
			error:function(jq,status,errorhttp){
				self.working=false;
				$("#save-loader").hide();
				
				alert("Error "+status);
			}

		});
		};
		this.init_tooltips=function(e){
			$(".my_tooltip_form").tooltip({
				items:'div',
				content:function(){
				var html=$(this).children(".my_tooltip_content").html();
				return html;
				}
			});

		};
		/*
		 * changes 1.23.2015.
		 */
		this.my_init_color_picker=function(sel){
			$(sel).each(function(){
	            //$(this).css('background', $(this).val());
			
				var name=$(this).attr('my_name');
				var color=$("input[type='hidden'][name='"+name+"']").val();
				$(this).siblings(".my_color_picker_color").css('background-color',color);
	            self.my_debug("Init colors",{name:name,color:color});
	            $(this).data('my-color',color);
	            $(this).iris({
					height: 145,
					color:color,
	               // target:$(this).parent().find(".color-picker-iris-holder[name='"+name+"']"),
					change: function(event, ui) {
	                    var color=ui.color.toString();
	                    var name=$(this).attr('my_name');
	                    $(this).siblings(".my_color_picker_color").css('background-color',color);
	                    $("input[type='hidden'][name='"+name+"']").val(color);
	                    self.my_debug("Color",color);
	                    $(this).find(".iris-picker-inner .my_change_color_input_12").val(color);
	                    //$(this).css( 'background-color', ui.color.toString());
	                }              
	            });
	            
			});
			$(".iris-picker").each(function(i,v){
				if($(this).find(".iris-picker-inner .my_change_color_input_12").length==0){
				var h=$(this).height();
				h+=50;
				$(this).height(h);
				var color="";
				color=$(this).parents(".my_color_picker_title").data('my-color');
				self.my_debug("Init color text field",color);
				//var color=$(this).iris("option",true);
				$(this).find(".iris-picker-inner").append('<div class="clear"></div><div style="margin-top:10px">'+self.options.msgs.hex_value+'<input type="text" class="my_change_color_input_12" value="'+color+'"/></div>');
				}
			});
			$(".my_change_color_input_12").unbind('keyup');
			$(".my_change_color_input_12").keyup(function(e){
				var keycode=e.which;
				self.my_debug("Key code",keycode);
				var val=$(this).val();
				/*var l=val.length-1;
				if((keycode<48)||(keycode>57&&keycode<65)||(keycode>70)){
					
					val=val.substr(0,l);
					$(this).val(val);
					return;
				}*/
				
				if(val.length==0){
					$(this).val('#');
					val='#';
				}
				if(val.length>=8){
					val=val.substr(0,7);
					$(this).val(val);
				}
				self.my_debug("Change input text",val);
				if(val.length>1){
					/*if(!val.match(/^\#[0-9]+[a-f]+[A-F]+$/)){
						alert(my_admin_woo_msgs.wrong_color_hex);
						return;
					}else 
					*/
					//$(this).parents(".my_color_picker_title").iris("color",val);
				}
				if(val.length==7){
					$(this).parents(".my_color_picker_title").iris("color",val);
				}
			});
			
		};
		/*
		 * end
		 */
		this.my_activate_so=function(e){
			if(self.working)return;
			self.working=true;
			
			//var id=$(this).attr('id');
			//var checked=$(this).is(":checked");
			var checked=0;
			if($(this).hasClass("imapper-checkbox-on")){
				checked=1;
			}
			var id=$(this).siblings("input[type='checkbox']").attr('name');
			if(self.checked_values[id]&&checked==1){
				self.my_debug("Checked click checked");
				self.working=false;
				return;				
			}
			if(!self.checked_values[id]&&checked==0){
				self.my_debug("not checked click not checked");
				self.working=false;
				return;				
			}
			self.checked_values[id]=checked;
			self.my_debug("Activate newtork",{id:id,checked:checked});
			if(checked){
				if(id in self.inactive_so){
					self.active_so[id]=1;
					delete self.inactive_so[id];
					self.my_debug("Reactivate Network",id);
					//var html=$(".my_social_pre_form_"+id).html();
					var html=$(".my_inactive_network_"+id).html();
					
					self.my_debug("Active networks",{active:self.active_so,inactive:self.inactive_so});
					$("#imapper-sortable-items").append('<li class="imapper-sortableItem"  id="my_social_network_li_'+id+'" data-my-id="'+id+'">'+html+'</li>');
					$(".my_inactive_so_networks .my_inactive_network_"+id).remove();	
					self.init_tooltips();
					var hidden_html='<input type="hidden" name="my_active_networks[]" value="'+id+'"/>'; 
					$(".imapper_items_options").append(hidden_html);
					
					/*
					 * changes 1.23.2015.
					 */
					self.my_init_color_picker("#my_social_"+id+" .my_color_picker_title");
					if(self.is_dynamic)self.update_checks(1);
					else self.update_checks(0);
					
					/*
					 * end changes
					 */
					
				}else {
					//$("#youtube_max_id").val(50);
					self.my_debug("ADD Network",id);
					self.active_so[id]=1;
					self.my_debug("Active networks",self.active_so);
					
					var html=$(".my_social_pre_form_"+id).html();
					$("#imapper-sortable-items").append('<li class="imapper-sortableItem"  id="my_social_network_li_'+id+'" data-my-id="'+id+'">'+html+'</li>');
					$(".my_social_networks_init .my_social_pre_form_"+id).remove();
					var hidden_html='<input type="hidden" name="my_active_networks[]" value="'+id+'"/>'; 
					$(".imapper_items_options").append(hidden_html);
					
					self.init_tooltips();
					/*
					 *changes 1.23.2015.
					 *init color pickers 
					 */
					if(self.is_dynamic)self.update_checks(1);
					else self.update_checks(0);
					
					self.my_init_color_picker("#my_social_"+id+" .my_color_picker_title");
					/*
					 * end changes
					 */
				}
				
			}else {
				if(id in self.active_so){
					$(".imapper_items_options input[value='"+id+"']").remove();
					self.my_debug("Remove Network",id);
					delete self.active_so[id];
					self.inactive_so[id]=1;
					self.my_debug("Active networks",{active:self.active_so,inactive:self.inactive_so});
					
					
					var html=$("#my_social_network_li_"+id).html();
					$("#my_social_network_li_"+id).remove();
					var new_html='<div class="my_inactive_network_'+id+'">'+html+'</div>';
					if($(".my_inactive_so_networks .my_inactive_network_"+id).lenght>0){
						$(".my_inactive_so_networks .my_inactive_network_"+id).html(html);
						
					}else $(".my_inactive_so_networks").append(new_html);
					
					
				
				}
			}
			self.working=false;
			
		};
		this.my_debug=function(t,o){
			if(self.debug){
				if(typeof o!='undefined'){
					console.log('So Grid Admin\n'+t,o);
				}else {
					console.log('So Grid Admin\n'+t);
					
				}
			}
		};
		
		this.init(o);

	};
})(jQuery);