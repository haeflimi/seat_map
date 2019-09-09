$(function() {
    var cls = $('#seat-map-reservations').data('class');
    if(cls){
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
                    title: title,
                    content: function(){
                        $('#seat-map-empty-seat-form #seat-map-claim-seat').val(seat_id);
                        return $('#seat-map-empty-seat-form').html();
                    }
                }).on('shown.bs.popover', function(shownEvent) {

                    $('.seat-map-claim').click( function( event ){
                        event.preventDefault();
                        $.ajax({
                            type: "post",
                            url: '/ccm/seat_map/claim_seat',
                            data: $('#claimSeatForm').serialize(),
                            success: function (data) {
                                svgElement.popover('dispose');
                                activateNextStep();
                            },
                            error: function () {
                                console.warn("error");
                            }
                        });
                    });
                });;

            }
        });
    }



    $('#seat-map-filter').selectize({
        create: true,
        sortField: 'text',
        onChange: function( seat_id ) {
            // remove highlighting clases from all seats
            $('.'+cls).removeClass('animated').removeClass('pulse').removeClass('infinite');
            // add highlighting class for chosen seat
            $('#'+seat_id).addClass('animated').addClass('pulse').addClass('infinite');
            // filter user list to the value
            if(!seat_id.isNull){
                $('.participant-item').hide();
                var result = $('.participant-item').filter(function() {
                    return $(this).data("seat") == seat_id;
                });
                result.show();
            } else {
                $('.participant-item').show();
            }
        }
    });
});
