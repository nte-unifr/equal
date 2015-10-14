$(document).ready(function() {
	$(".label-info-modal").click(function() {
	   	var id = $(this).attr('href');
	    rescale(id);
	});
});
function rescale(id){
    var size = {width: $(window).width() , height: $(window).height() }
    /*CALCULATE SIZE*/
    var offset = 50;
    var offsetBody = 170;
    /*$(id).css('height', size.height - offset );*/
    $('.modal-body').css('height', size.height - (offset + offsetBody));
    $(id).css('top', '10px' );
}
$(window).bind("resize", rescale);