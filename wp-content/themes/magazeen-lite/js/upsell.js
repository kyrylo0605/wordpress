/**
 * Upsell notice for theme
 */

( function( $ ) {

  // Add Upgrade Message
  if ('undefined' !== typeof magazeenL10n) {
    upsell = $('<a class="magazeen-upsell-link"></a>')
      .attr('href', magazeenL10n.magazeenURL)
      .attr('target', '_blank')
      .text(magazeenL10n.magazeenLabel)
      .css({
        'display' : 'inline-block',
        'background-color' : '#f1572f',
        'color' : '#fff',
        'text-transform' : 'uppercase',
        'margin-top' : '6px',
        'padding' : '10px 10px',
        'font-size': '9px',
        'letter-spacing': '1px',
        'line-height': '1.5',
        'clear' : 'both',
        'width' : '85%',
        'animation': 'shake 0.82s cubic-bezier(.36,.07,.19,.97) both',
          'transform': 'translate3d(0, 0, 0)',
          '-webkit-animation-iteration-count': 'infinite',
          'animation-iteration-count': 'infinite',
      })
    ;

    setTimeout(function () {
      $('#accordion-section-themes h3').append(upsell);
    }, 200);

    // Remove accordion click event
    $('.magazeen-upsell-link').on('click', function(e) {
      e.stopPropagation();
    });
  }

} )( jQuery );