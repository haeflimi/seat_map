$(function() {
    $('.seat-map-reservation-item').each(function(){
        var infoElement = $(this);
        var svgElement = $('#'+infoElement.data('svgelement'));
        if(infoElement.data('myseat')){
            svgElement.addClass('my');
        }
        svgElement.addClass('taken');
        svgElement.popover({
            container: 'body',
            html: true,
            placement: 'right',
            trigger:'click',
            content: infoElement.html()
        });
    });
});
