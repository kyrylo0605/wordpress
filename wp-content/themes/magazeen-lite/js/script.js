 //Masonry

jQuery(document).ready(function($){

var $container = $('.masonry');
$container.imagesLoaded(function(){
$container.masonry({
  // options
  itemSelector: '.col-6-12',
  columnWidth: '.col-6-12',
  percentPosition: true,
  //columnWidth: 200
});});

//jQuery('ul.sf-menu').superfish();


  $('#cssmenu').prepend('<div id="menu-button">Menu</div>');
  $('#cssmenu #menu-button').on('click', function(){
    var menu = $(this).next('ul');
    if (menu.hasClass('open')) {
      menu.removeClass('open');
    } else {
      menu.addClass('open');
    }
  });
});


