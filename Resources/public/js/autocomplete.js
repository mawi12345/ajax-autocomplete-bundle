$(function() {
    var cache = {};
	$.widget( "mawi.ajautocomplete", $.ui.autocomplete, {
		_renderItem: function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.addClass( item.clazz)
				.append( $( "<a></a>" ).html( item.label ) )
				.appendTo( ul );
		},
		_move: function( direction, event ) {
			/* fix move cursor */
			$.ui.autocomplete.prototype._move.call(this, direction, event);
			this._trigger( "select", event, {item: this.menu.active.data('item.autocomplete')});
		}
	});
	
	$('[data-autocomplete]').each(function(){
		var $this = $(this);
		var alias = $this.attr('data-autocomplete');
		var aid = $this.attr('id');
		var hid = aid.slice(0, aid.length - 5)+'id';
	    $( '#'+aid ).ajautocomplete({
	        source: function( request, response ) {
                var term = request.term;
                if ( term in cache ) {
                  response( cache[ term ] );
                  return;
                }
	        	var reqData = $( '#'+aid ).closest( 'form' ).serialize()+'&term='+request.term+'&alias='+alias;
	            $.ajax({
	                url: Routing.generate('mawi_ajaxautocomplete'),
	                type: 'POST',
	                dataType: "json",
	                data: reqData,
	                success: function( data ) {
	                    response( $.map( data, function( item ) {
	                    	if (item.clazz) {
	                    		return {
		                            label: item.label,
		                            value: item.id,
		                            clazz: item.clazz
		                        };
	                    	};
	                        return {
	                            label: item.label,
	                            value: item.id,
	                            clazz: ''
	                        };
	                    }));
                        cache[ term ] = data;
	                }
	            });
	        },
	        minLength: 2,
			select: function( event, ui ) {
				$('#'+hid).val(ui.item.value);
				$('#'+aid).val( $(document.createElement('div')).html(ui.item.label).text() );
				$('#'+hid).change();
				return false;
			},
	    });
	});
});