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
                } else if(infoElement.data('temporary')){
                    svgElement.addClass('temp');
                } else {
                    svgElement.addClass('taken');
                }

                svgElement.popover({
                    container: 'body',
                    html: true,
                    placement: 'right',
                    trigger:'click',
                    content: infoElement.html(),
                    title: title,
                }).on('shown.bs.popover', function(shownEvent) {
                    initClaimButton(svgElement);
                    initInviteSelect();
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
                        $('#seat-map-empty-seat-form .btn.seat-map-claim').data('seat-id',seat_id);
                        return $('#seat-map-empty-seat-form').html();
                    }
                }).on('shown.bs.popover', function(shownEvent) {
                    initClaimButton(svgElement);
                });

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

function initInviteSelect(){
    $('.invite-user-select').selectize({
        create: false,
        onChange: function( invite_user_id ) {
            $('.invite-user-select').val( invite_user_id );
            $('.invitee_user_id').val( invite_user_id );
        }
    });
}

function initClaimButton(svgElement){
    $('.seat-map-claim').click( function( event ){
        event.preventDefault();
        var seat_id = $(svgElement).attr('id');
        if($('#claimSeatForm-'+seat_id).length){
            var formData = $('#claimSeatForm-'+seat_id);
        } else {
            var formData = $('#claimSeatForm');
        }

        $.ajax({
            type: "post",
            url: '/ccm/seat_map/claim_seat',
            data: formData.serialize(),
            success: function (data) {
                svgElement.popover('hide');
                svgElement.removeClass('temp');
                $('.seat-map-wrapper svg').find('.my').removeClass('my');
                svgElement.addClass('my');
            },
            error: function () {
                console.warn("error");
            }
        });
    });
}