
/* $('.to-top').click(function () {
  $('html, body').animate({scrollTop:'0px'},800)
}) */

$(window).on("scroll", function() {
  if ($(this).scrollTop() > 0) {
    $('.to-top').fadeIn('slow');
  } else {
    $('.to-top').fadeOut('slow');
  }
});
$(".to-top").on("click", function() {
  $("html, body").animate({
    scrollTop: '0px'
  }, 800);
  return false;
});