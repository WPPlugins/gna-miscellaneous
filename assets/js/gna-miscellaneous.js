jQuery(document).ready(function($){
    $(".gna_more_info_body").hide();
    $(".gna_more_info_anchor").click(function(){
        $(this).next(".gna_more_info_body").animate({ "height": "toggle"});
        var toogle_char_ref = $(this).find(".gna_more_info_toggle_char");
        var toggle_char_value = toogle_char_ref.text();
        if(toggle_char_value === "+"){
            toogle_char_ref.text("-");
        }
        else{
             toogle_char_ref.text("+");
        }
    });
});