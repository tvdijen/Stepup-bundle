(function ($) {
    'use strict';

    // Disable forms on submission.
    $(document).on('submit', function () {
        var $form = $(this);

        // Disabling must be deferred until after the submission or the form values won't be included in the request.
        setTimeout(function () {
            $form.find('button, input, textarea, select').prop('disabled', true);
        }, 0);
    });
}(jQuery));
