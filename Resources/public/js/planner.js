var MzzApp = {

    News: {
        'remove': function(link) {
            var id = '#news_' + link.match(/\d+(?=\/delete)/);

            if (confirm('Do you really want to delete the news?')) {
                MzzApp._setInProgress(id);
                $.post(link, function() {}, 'script')
                .error(function() {
                    alert("Oops, error. The news was not deleted. Check your internet connection and try again.");
                });
            }

            return false;
        },


        createFormHandler: function(form) {
            this.create_form_submitted = false;

            $(form).bind("invalid-form.validate", function(e, validator) { /* empty: do not do anything */ }).validate(
            {
                submitHandler: function(_form) {
                    return jipWindow.sendForm(_form);
                },
                showErrors: function(errorMap, errorList) {
                    if (MzzApp.News.create_form_submitted) {
                        var summary = "";
                        jQuery.each(errorList, function() {
                            var label = $("label[for=" + this.element.id +  "]");
                            label.attr('class', 'error-label');
                            summary += label.html() ? label.html() : this.element.name;
                            summary +=' - ' + this.message + "\n";
                        });
                        alert(summary);
                        MzzApp.News.create_form_submitted = false;
                    }
                    this.defaultShowErrors();
                },

                errorPlacement: function(error, element){ /* empty: do not do anything */ },
                invalidHandler: function(form, validator) {
                    MzzApp.News.create_form_submitted = true;
                }
            });

        }
    },


    _setInProgress: function(elm)
    {
        $(elm + ' .jip').removeClass('jip-button');
        $(elm + ' .jip').attr('src', '/images/animation_progress3.gif').attr('width', '18').attr('height', '4');
    }
};
