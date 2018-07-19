$(function() {
    var cls = $('#seat-map-reservations').data('class');
    $('.'+cls).each(function(){
        var svgElement = $(this);
        var seat_id = $(this).attr('id');
        var infoElement = $('#'+seat_id+'_details');
        var title = 'Seat: <b>'+seat_id.toUpperCase()+'</b>';
        if(infoElement.length > 0){
            if(infoElement.data('myseat')){
                svgElement.addClass('my');
            }
            svgElement.addClass('taken');
            svgElement.popover({
                container: 'body',
                html: true,
                placement: 'right',
                trigger:'click',
                content: infoElement.html(),
                title: title,
            });
        } else {
            svgElement.popover({
                container: 'body',
                html: true,
                placement: 'right',
                trigger:'click',
                content: function(){
                    $('#seat-map-empty-seat-form #seat-map-claim-seat').val(seat_id);
                    return $('#seat-map-empty-seat-form').html()
                },
                title: title,
            });
        }
    });
});
