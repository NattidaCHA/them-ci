//$('.select2').select2({
//    theme: "bootstrap-5"
//});
let creatorInfo = $('#craetorInfo').parsley();
let socialInfo = $('#socialInfo').parsley();
let modalAddSocial = $('#modalAddsocial #createSocial').parsley();

$('#create_sow').on('click', function (e) {
    let sow = $('#select_sow').val()

    if (sow) {
        $('.hr').addClass('d-flex').removeClass('d-none')
        $('.text-head').addClass('d-flex').removeClass('d-none')
        $('#addSow').append('<div class="row createSow"><div class="col-md-2 mt-3"><label id="label_name">' + sow + '</label></div>' +
            '<div class="col-md-4 mt-2"><input id="sow" name="sow[]" value="' + sow + '"  type="hidden" autocomplete="off"><input class="form-control _autoNumAllowZero" id="cost" type="text" name="cost[]" placeholder="Cost" autocomplete="off" required></div>' +
            '<div class="col-md-4 mt-2"><input class="form-control  _autoNumAllowZero" id="price" name="price[]" type="text" placeholder="Price" autocomplete="off" required></div>' +
            '<div class="col-md-2 mt-2" id="btn"><button class="btn btn-danger btn-sm mt-1" id="del_sow" type="button"><i class="bi bi-trash3-fill"></i></button></div></div>')
    } else {
        alert('กรุณาเลือก SOW')
    }
})

$('#addSow').on('click', '#del_sow', function (e) {
    let btn = $(this).parents('.createSow')
    btn.remove();

    if ($('#addSow .createSow').length === 0) {
        $('.hr').addClass('d-none').removeClass('d-flex')
        $('.text-head').addClass('d-none').removeClass('d-flex')
    }
})

$('._autoNumAllowZero').mask('#,##0', {
    reverse: true,
    onKeyPress: function (cep, e, field, options) {
        if (e.originalEvent.data && e.originalEvent.data.search(/\./i) >= 0) {
            cep2 = e.originalEvent.data.split('.')[0] || cep.replace(/,/g, '');
            field.val(addComma(cep2, ''));
        } else {
            if (cep.length >= 2 && cep[0] === '0') {
                let ori = cep.replace(/,/g, '');
                field.val(addComma(ori.substring(1, 8), ''));
            } else if (cep.length > 10) {
                let ori = cep.replace(/,/g, '');
                field.val(addComma(ori.substring(0, 8), ''));
            }
        }
    }
})



function clearModalForm() {
    $('#modalAddsocial input').val('').trigger('change');
    $('#modalAddsocial select').val('').trigger('change');
    modalAddSocial.reset();
}