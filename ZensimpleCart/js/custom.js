	/* ---------------------------------------------------------------------- */
	/*	Cart image Fly in
	/* ---------------------------------------------------------------------- */
		  $(document).ready( function(){
                $('.addProduct').each(function(){
                    var img = $(this).closest('div').find('img:first');
                    $(this).click(function(){
                        flyToElement($(img), $('#basket'));
                    });
 
                });
                $('.removeProduct').each(function(){
                    var img = $(this).closest('div').find('img:first');
                    $(this).click(function(){
                        flyFromElement($(img), $('#basket'));
                    });
                });
            });
	/* ---------------------------------------------------------------------- */
	/*	Cart Popup
	/* ---------------------------------------------------------------------- */	
$('#cart > .heading a').live('click', function() {
		$('#cart .content').slideDown(400);
		$('#cart').addClass('active');
		$('#cart').live('mouseleave', function() {
			$('#cart .content').slideUp(200, function(){
				$(this).removeClass('active');
			});
		});
	});

//Hide until page finish loading.
$(document).ready(function() {
	  $('#cart').toggle();
});


/* ---------------------------------------------------------------------- */
	/* Set Cart options for checkout
	/* ---------------------------------------------------------------------- */	

	$("document").ready(function(){
   
          $("#priceselect").change(function(){
		  $("#pricedesc").text ($('#priceselect option:selected').text());
		  $("#priceshow").text ("$" + ($(this).val())); 
        });    
    });
	/* ---------------------------------------------------------------------- */
	/* TipTip
	/* ---------------------------------------------------------------------- */	
	
	/*Call TipTip Tooltip*/
$(function(){
$("a.tooltip_r").tipTip({maxWidth: "auto",defaultPosition:"right"});
$(".tooltip_t").tipTip({maxWidth: "auto",defaultPosition:"top"});
$(".tooltip_l").tipTip({maxWidth: "auto",defaultPosition:"left"});
$("a.tooltip_b").tipTip({maxWidth: "auto"});
});
	/* ---------------------------------------------------------------------- */
	/* SimpleCart Actions
	/* ---------------------------------------------------------------------- */	
$(document).ready( function(){
simpleCart.bind( 'beforeCheckout' , function( data ){
        $("#lightbox_open").click();
});
});
               /* b.fadeOut(function () {
                    d.fadeIn(500, function () {
                        setTimeout(function () {
                            c.removeAttr("disabled"), $("#user-wrapper form").submit(), c.attr("disabled", "disabled")
                        }, 1200)
                    })
                })*/
$("#lightbox_close").live("click", function () {
	$("#info_box").fadeOut("fast"), $("#info_box_shadow").removeClass("display")
});

$("#lightbox_open").live("click", function () {
$("#info_box").fadeIn("fast"), $("#info_box_shadow").addClass("display"), close_and_callback = function () {
       }, $("#info_box_shadow, #info_box #lightbox_close").unbind("click").bind("click", close_and_callback)
});	
