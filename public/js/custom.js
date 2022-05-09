function formatDateToYMD(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

function formatDateToDMY(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [month, day, year].join('/');
}

function getYears(startDate, endDate) {
    var d1 = new Date(startDate),
        d2 = new Date(endDate),
        yr = [];

    for (var i = d1.getFullYear(); i <= d2.getFullYear(); i++) {
        yr.push( i );
    }
    return yr;
}

function daysdifference(startDate, endDate){
    var startDay = new Date(startDate);
    var endDay = new Date(endDate);

    var millisBetween = startDay.getTime() - endDay.getTime();
    var days = millisBetween / (1000 * 3600 * 24);

    return Math.round(Math.abs(days));
}

$('[name=renewable]').on('change', function () {
    if (parseInt($(this).val())) {
        $('#renewal-period-container-1').removeClass('d-none');
        $('#renewal-period-container-2').removeClass('d-none');
        $('.admin_ceus').removeClass('d-none');
        $('#credit-types-container').removeClass('d-none');
        $('#certification_cycle_end').attr('disabled',true);
    } else {
        $('#renewal-period-container-1').addClass('d-none');
        $('#renewal-period-container-2').addClass('d-none');
        $('.admin_ceus').addClass('d-none');
        $('#credit-types-container').addClass('d-none');
        $('#renewal_period').val('');
        $('#certification_cycle_start').val('');
        $('#certification_cycle_end').val('');
    }
});

$('#certification_cycle_start').on('change', function () {
    $('#certification_cycle_end').attr('disabled',false);
    if($(this).val() == ''){
        $('#certification_cycle_end').attr('disabled',true);
    }
    var myDate = new Date($(this).val());
    var newDate = new Date(myDate.setFullYear(myDate.getFullYear() + 3));
    if(newDate.getMonth() > 6){
        newDate.setMonth(9);
        newDate.setDate(31);
    }else{
        newDate.setMonth(3);
        newDate.setDate(30);
    }
    $(certification_cycle_end).val(formatDateToYMD(newDate));
    $('#renewal_period').val(3);
});

$('#certification_cycle_end').on('change', function () {
    if($(this).val() > $('#certification_cycle_start').val())
    {
        var startDate = formatDateToDMY($('#certification_cycle_start').val());
        var endDate = formatDateToDMY($(this).val());
        var totalDays = getYears(startDate, endDate);
        $('#renewal_period').val(totalDays.length - 1);
    }
    else {
        alert('Certification cycle end date cannot be smaller than start date.');
        $(this).val('');
        $('#certification_cycle_start').val('');
        $('#renewal_period').val('');
    }
    $(this).attr('disabled',false);
    if($('#certification_cycle_start').val() == ''){
        $(this).attr('disabled',true);
    }
});
