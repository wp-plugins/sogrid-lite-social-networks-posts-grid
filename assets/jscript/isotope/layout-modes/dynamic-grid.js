( function( window ) {

	function dynamicGridDefinition( LayoutMode ) {

		var dynamicGrid = LayoutMode.create('dynamicGrid');

		dynamicGrid.prototype._resetLayout = function() {
		  this.x = 0;
		  this.y = 0;
		  this.maxY = 0;
		  delete this.mymaxY;
		  this.mymaxY=0;
		  this._getMeasurement( 'gutter', 'outerWidth' );
		  this.my_c=0;
		  this.my_has_thumbs=true;
		};

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

		if ( typeof define === 'function' && define.amd ) {
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
		}

		})( window );
