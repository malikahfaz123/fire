function removeExtraSpaces (theField) {
    if(!theField.value) return;
    trimValue= theField.value;

    trimValue = trimValue.replace(/\s+/g," ");
    trimValue = trimValue.replace(/^\s+|\s+$/g,"");

    theField.value = trimValue;
}

$(function()
{
    $(document).on('blur','.form-control',function()
    {
        removeExtraSpaces(this)
    });

    $(document).on('keyup input','.alphanumeric-only',function()
    {
        if (this.value.match(/[^a-zA-Z0-9]/g))
        {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        }
    });

    $(document).on('keyup input','.alpha-only',function()
    {
        if (this.value.match(/[^a-zA-Z]/g))
        {
            this.value = this.value.replace(/[^a-zA-Z]/g, '');
        }
    });

    $(document).on('keyup input','.numeric-only',function()
    {
        if (this.value.match(/[^0-9]/g))
        {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $(document).on('keyup input','.alpha-with-space',function()
    {
        if (this.value.match(/[^a-zA-Z ]/g))
        {
            this.value = this.value.replace(/[^a-zA-Z ]/g, '');
        }
    });

});