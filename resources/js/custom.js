$(document).ready(function () {

    $('.sidebar-dropdown-toggle').on('click', function () {
        $('.sidebar-dropdown-menu').removeClass('show');
        $(this).parent().find('.sidebar-dropdown-menu').addClass('show');
    });

    $('.numeric-only').bind('keyup input',function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    if(typeof Swal !=='undefined' ){
        window.Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    $('.cancel').on('click', function (e) {
        let response = confirm('Are you sure want to cancel ?');
        if (!response) {
            e.preventDefault();
        }
    });

    $(document).on('submit','form',function () {
        $(this).find('.invalid-feedback').text('');
        $(this).find('.is-invalid').removeClass('is-invalid');
    });

    $(document).on('keyup input','.numeric-with-fraction',function() {
        if (this.value.match(/[^0-9-/.]/g)) {
            this.value = this.value.replace(/[^0-9-/.]/g, '');
        }
    });

    $(document).on('keyup input keypress','.numeric-only',function(evt) {
        if (evt.which !== 8 && evt.which !== 0 && evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $('body').tooltip({
        selector: '*',
        delay: { "show": 1000 }
    });

    axios.interceptors.response.use(response => response, error => {
        if(error.response.status === 422) {
            setFormErrors(error.response.data.errors);
            return Promise.reject(error)
        }
        else if(error.response.status === 403) {
            alert('You does not have the right permissions.');
            return Promise.reject(error)
        }
    });

    function setFormErrors(errors) {
        let keys = Object.keys(errors), name;
        if(keys.length){
            for (let i=0; i<keys.length; i++){
                $(`#${keys[i]}`).text(errors[keys[i]]);
                if(document.getElementsByName(keys[i]).length){
                    name = keys[i]
                }else if(document.getElementsByName(keys[i]+"[]").length){
                    name = keys[i]+"[]"
                }
                $(`[name="${name}"]`).addClass('is-invalid');
            }
        }
    }

    $(document).on('change','#toggle_edit',function () {
        let checked = $(this).is(':checked');
        if(checked){
            $(document).find('.show-field').addClass('d-none');
            $(document).find('.edit-field').removeClass('d-none');
        }else{
            $(document).find('.show-field').removeClass('d-none');
            $(document).find('.edit-field').addClass('d-none');
        }
    });

    $(document).on('click','.toggle-row',function () {
        let target = $(this).data('toggle');
        $(document).find(`.toggle-row-item`).addClass('d-none');
        if(!$(this).hasClass('active')){
            $(document).find('.toggle-row').removeClass('active');
            $(this).addClass('active');
            $(document).find(target).removeClass('d-none');
        }else{
            $(this).removeClass('active');
            $(document).find(target).addClass('d-none');
        }
    })
});

window.contains = function(stack,needle){
    for (let i=0; i<stack.length; i++){
        if(stack[i] ===needle){
            return true;
        }
    }
    return false;
}
Object.size = function(obj) {
    let size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
window.ordinal_suffix_of = function(i) {
    let j = i % 10,
        k = i % 100;
    if (j === 1 && k !== 11) {
        return i + "st";
    }
    if (j === 2 && k !== 12) {
        return i + "nd";
    }
    if (j === 3 && k !== 13) {
        return i + "rd";
    }
    return i + "th";
}