const $ = jQuery.noConflict();


(() => {

    const $window = $(window);  
    let sendFormXhr;

    class Form {
        constructor() {
            
        }

        init = () => {
            const self = this;
            sendFormXhr = null;

            $('body').on('click', '.run-form', function (e) {                        
                e.preventDefault();     
                let form = $(this).parents('form');

                $.getJSON( "/wp-content/themes/pallet/inc/form/cfg/" + form.attr('name') + ".json", function(data) {  
                    self.validateForm(form, data); 
                })
                .fail(function() {
                    return false;
                });                          
            });

        }  

        validateForm = (form, formConfig) => {      

            const self = this;

            let name = form.attr('name');

            form.validate({
                rules: formConfig.rules,
                messages: formConfig.messages,
                focusInvalid: false,
                invalidHandler: function() {
                    form.find('.form__result').html('<p>Заполните необходимые поля и повторите попытку</p>');
                }
            });

            if (form.valid()) {
                self.sendForm(form, formConfig);
                return false;
            }

        }


        sendForm = (form, formConfig) => {

            if ( sendFormXhr !== null ) {
                sendFormXhr.abort();
            }


            let name = form.attr('name');
            let action = form.data('action') || 'run_form';

            form.find('.form-result').html(''); 

            sendFormXhr = $.post({
                url:'/wp-admin/admin-ajax.php', 
                data: 'action=' + action + '&subject='+ formConfig.subject + '&successMsg=' + formConfig.success + '&formName=' + name + '&' + form.serialize(), 
                cache: false,
                beforeSend: function() {                    
                    form.find('.form__result').html('');  
                    form.block({ 
                        message: null,
                        overlayCSS: {
                            backgroundColor: '#fff',
                            opacity: 0.5
                        }
                    }); 
                }
            }).done(function(respond) {
                if (respond.success === true) {
                    $('body').append(respond.data.modal);

                    form[0].reset();

                    $('.modal').modal('hide');

                    setTimeout(function() {
                        $('#thanks-modal-box').modal('show');
                    }, 300);

                    $('#thanks-modal-box').on('hidden.bs.modal', function(e) {
                        $(this).remove();   

                        if (respond.data.location) {                            
                            window.location.replace(respond.data.location);
                            window.location.reload();
                        }                        
                    });                    
                    
                } else {
                    form.find('.form__result').html('<p>' + respond.data + '</p>');  
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                alert("Ошибка");
            }).always(function(data) {
                form.unblock();
                sendFormXhr = null;
            });
        }            

    }

    const projectForm = new Form();

    $window
        .on('DOMContentLoaded', projectForm.init())
})();