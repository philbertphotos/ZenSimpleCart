	/* ---------------------------------------------------------------------- */
	/*	Ajax Minicart
	/* ---------------------------------------------------------------------- */

		$('#cart .arrow').live('mouseover', function() {

			$('#cart').addClass('active');
			
			//$('#cart').load('#cart > *');

			$('#cart > .cartcontent').slideToggle('fast');
			
			$('#cart').live('mouseleave', function() {
				$(this).removeClass('active');
			});
			
		});
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
 document.getElementById('basket').style.display = "";
	/* ---------------------------------------------------------------------- */
	/*	Cart Popup
	/* ---------------------------------------------------------------------- */	
    //$(".inline").colorbox({inline:true, width:"90%"});
$(document).ready(function () {
    $("a[rel^='cartpop']").colorbox({inline:true, width:"80%", height:"80%"});
	$("a[rel^='inlinetest']").colorbox({inline:true, width:"80%", height:"80%"});
	$(".inline").colorbox({inline:true, width:"50%"});
});


	
/* ---------------------------------------------------------------------- */
	/* Select Dropbox
	/* ---------------------------------------------------------------------- */	

	$("document").ready(function(){
   
        $("#priceselect").change(function(){
    
          //$("#priceshow").append("<span>" + $(this).val() + " </span>");
		  $("#priceshow").text ("$" + ($(this).val()));
          
        });    
    });
	/* ---------------------------------------------------------------------- */
	/* TipTip
	/* ---------------------------------------------------------------------- */	
	
	/*Call TipTip Tooltip*/
$(function(){
$("a.tooltip_r").tipTip({maxWidth: "auto",defaultPosition:"right"});
$("a.tooltip_t").tipTip({maxWidth: "auto",defaultPosition:"top",edgeOffset: 40});
$("a.tooltip_l").tipTip({maxWidth: "auto",defaultPosition:"left"});
$("a.tooltip_b").tipTip({maxWidth: "auto"});
});