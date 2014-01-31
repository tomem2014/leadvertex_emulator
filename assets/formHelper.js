if (!window.leadvertex) window.leadvertex = {};
window.leadvertex.form = {};

window.leadvertex.form.label = function(field,name,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('label[for=form'+form+'_'+field +']').text(name);
    });
};
window.leadvertex.form.subLabel = function(field,text,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('#lv_form'+form+' div.field_'+field +'>.form_field').after('<div class="form_sub_label"><label for="form'+form+'_'+field +'">'+text+'</label></div>');
    });
};

window.leadvertex.form.placeholder = function(field,placeholder,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('#form'+form+'_'+field).attr('placeholder',placeholder);
    });
};
window.leadvertex.form.placeholderOnly = function(form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('#lv_form'+form+' .field.field_input').each(function(i,e){
            $(e).find('.form_label').hide();
            var $input = $(e).find('.form_field > *');
            $input.attr('placeholder',$input.attr('data-label'));
        });
    });
};

$(document).ready(function(){
    $('.lv_move').each(function(i,e){
        var form = $(e).attr('data-form');
        if (!form) form = '';
        var position = $(e).attr('data-position').toString().toLowerCase();
        var field = $(e).attr('data-field').toString().toLowerCase();
        if (field == 'submit') field = 'div.form_submit';
        else field = 'div.field_'+field;
        var $element = $('#lv_form'+form+' '+field);
        if (position == 'before') $element.before($(e));
        else $element.after($(e));
    });
});