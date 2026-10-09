$(function(){
    const $typeSelect = $('#hero-type');
    const $priceFields = $('[data-price]');

    function switchPrice(){
        $priceFields.each(function (){
            const isActive = $(this).data('price') === $typeSelect.val();
            $(this).prop('hidden', !isActive);
            $(this).find('select').prop('disabled', !isActive);
        });
    }

    $typeSelect.on('change', switchPrice);
    switchPrice();
});
