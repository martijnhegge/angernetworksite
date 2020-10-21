$( document ).ready(function() {
    console.log( "ready!" );
    $('.sub').hide();
    $('.sidenav a.dash').addClass('active');
$( ".payments" ).click(function() {
	$( ".sub" ).slideToggle("slow");
});
$('.sidenav a.link').click(function(e) {
    e.preventDefault();
    $('.sidenav a').removeClass('active');
    $(this).addClass('active');
});
});