function myxboxprofileShow() {
  $('.xboxgamercard_gamedetails').hide();
  $('.show_gamedetails img').hover(
    function() {
		var flipid=$(this).attr('id');
        $('#xboxgamercard_gameholder #' + flipid).slideToggle('normal');
      },
	function() {
		var flipid=$(this).attr('id');
         $('#xboxgamercard_gameholder #' + flipid).slideToggle('normal');
      }
    );
  }
$(document).ready(function() {myxboxprofileShow();});
