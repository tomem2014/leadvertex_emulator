if (!window.leadvertex) window.leadvertex = {};
if (!window.leadvertex.selling) window.leadvertex.selling = {};
if (!window.leadvertex.selling.delivery) window.leadvertex.selling.delivery = {};
if (!window.leadvertex.selling.discount) window.leadvertex.selling.discount = {};
if (!window.leadvertex.selling.price) window.leadvertex.selling.price = {};

window.leadvertex.form = {};

window.leadvertex.form.label = function(field,name,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('label[for=lv-form'+form+'-'+field +']').text(name);
    });
};
window.leadvertex.form.subLabel = function(field,text,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('#lv-form'+form+' div.lv-row-'+field +'>.lv-field').after('<div class="lv-sub-label"><label for="lv-form'+form+'-'+field +'">'+text+'</label></div>');
    });
};

window.leadvertex.form.buttonText = function(text,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('#lv-form'+form +' .lv-order-button').val(text);
    });
}

window.leadvertex.form.placeholder = function(field,placeholder,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('#lv-form'+form+'-'+field).attr('placeholder',placeholder);
    });
};
window.leadvertex.form.placeholderOnly = function(form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        $('#lv-form'+form+' .lv-row.lv-row-input').each(function(i,e){
            $(e).find('.lv-label').hide();
            var $input = $(e).find('.lv-field > *');
            $input.attr('placeholder',$input.attr('data-label')+($input.attr('data-required') == '1' ? ' *' : ''));
        });
    });
};

window.leadvertex.form.validation = function($form, data, hasError) {
    if (hasError) {
        var errors = '';
        if ($form.attr('data-validation-by-alert')) {
            for (var i in data) errors+= data[i][0]+"\n\n";
            alert(errors);
        }
    }
    return true;
}
window.leadvertex.form.validationByAlert = function(form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        var $form = $('#lv-form'+form);
        $form.attr('data-validation-by-alert',1);
        $form.find('.lv-error').hide();
    });
}

window.leadvertex.form.showOnly = function (fields,form){
    if (!form) form = ''; else if (form<2) form = '';
    $(document).ready(function(){
        var $form = $('#lv-form'+form);
        $form.find('.lv-row').each(function(i,e){
            var name = $(e).attr('data-name');
            var notShow = $.inArray(name,fields)==-1;
            var isRequired = $(e).attr('data-required');
            if (name == 'checkboxPersonalData' || name == 'checkboxAgreeTerms') {
                $(e).prop('checked', true);
                isRequired = 0;
            }
            if (notShow && isRequired==0) $(e).hide();
        });
    });
}

$(document).ready(function(){
    $('.lv-move').each(function(i,e){
        var form = $(e).attr('data-form');
        if (!form) form = '';
        var position = $(e).attr('data-position').toString().toLowerCase();
        var field = $(e).attr('data-field').toString().toLowerCase();
        if (field == 'submit') field = 'div.lv-form-submit';
        else field = 'div.lv-row-'+field;
        var $element = $('#lv-form'+form+' '+field);
        if (position == 'before') $element.before($(e));
        if (position == 'after') $element.after($(e));
    });

    var $quantity = $('.lv-input-quantity');
    $quantity.change(function(){
        var quantity = parseInt($(this).val());
        var deliveryObject = window.leadvertex.selling.delivery;
        var discountObject = window.leadvertex.selling.discount;

        // Доставка
        var deliveryPrice = parseInt(deliveryObject['price']);
        var delivery;
        if (deliveryObject['for_Each']) {
            delivery = deliveryPrice * quantity;
            $('.lv-delivery-price').text(delivery);
        } else delivery = deliveryPrice;

        //Итого
        var price = parseInt(window.leadvertex.selling.price['price']);
        var discountPercent = 0;
        var discountRound = true;
        if (discountObject[quantity]) {
            discountPercent = discountObject[quantity]['discount'];
            discountRound = discountObject[quantity]['round'];
        } else {
            var index = -1;
            for (var i in discountObject) if (quantity>=i) {
                index = i;
            } else break;
            if (index == -1) {
                discountPercent = 0;
                discountRound = true;
            } else {
                discountPercent = discountObject[index]['discount'];
                discountRound = discountObject[index]['round'];
            }
        }
        var newPrice = parseFloat((price * quantity / 100) * (100-discountPercent)).toFixed(2);
        var discountSum = price*quantity-newPrice;
        price = newPrice;
        if (discountRound) price = Math.round(price);
        $('.lv-quantity-discount-sum').text(parseInt(discountSum));
        $('.lv-quantity-discount-percent').text(parseInt(discountPercent));
        $('.lv-total-price').text(parseInt(delivery)+parseFloat(price));
    });
    $quantity.change();
});