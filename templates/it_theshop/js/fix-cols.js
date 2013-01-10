

  var BoxHeights = {
	maxh: 0,
	topBox: ['promo','icetabs','notice-msg_wrapper'], /*id of the top box module on the content_inside box*/
	topMargin: [15,5,5], /*margin between elements*/
	boxes: Array(),
	num: 0,
	equalise: function() {
		this.num = arguments.length;
		for (var i=0;i<this.num;i++) if (!$(arguments[i])) this.num -=1;
		this.boxes = arguments;
		this.maxheight();
		this.theight = 0;
		for(i=0;i< this.topBox.length; i++){
			if( $(this.topBox[i]) ){
				this.theight+= this.getHeight( this.topBox[i] ) + this.topMargin[i];
			}
		}
		var leftHeight = this.getHeight( 'left-column' );
		for (var i=0;i<this.num;i++) {
			if( $(arguments[i]) ){
				var maxheight = this.maxh;
				if(arguments[i] == 'left-column' ){
					maxheight = this.maxh + this.theight;
				}
				
				$(arguments[i]).style.height = maxheight+"px";
			}
		}
	},
	getHeight: function( idbox ){
		var rheight = 0;
		if (navigator.userAgent.toLowerCase().indexOf('opera') == -1) {
			rheight = $(idbox).scrollHeight;
		} else {
			rheight = $(idbox).offsetHeight;
		}
		return rheight;
	},
	maxheight: function() {
		var heights = new Array();
		var total = this.num;
		for (var i=0;i<this.num;i++) {
			if( $(this.boxes[i]) ){
				if (navigator.userAgent.toLowerCase().indexOf('opera') == -1) {
					heights.push($(this.boxes[i]).scrollHeight);
				} else {
					heights.push($(this.boxes[i]).offsetHeight);
				}
			}
			else{
				total -= 1;
			}
		}
		heights.sort(this.sortNumeric);
		this.maxh = heights[total-1];
	},
	sortNumeric: function(f,s) {
		return f-s;
	}
}
window.onload= function() {
	BoxHeights.equalise('left-column','middle-column','right-column','content_inside');
}