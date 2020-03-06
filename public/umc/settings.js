Settings = function (data, fields) {
    this.data = {};
    for (field in fields) {
        if (fields.hasOwnProperty(field)) {
            this.data[fields[field]] = ko.observable(data[fields[field]] || '');
        }
    }
    this.submitForm = function () {
        var form = $('#settings');
        $('#body-loader').show();
        $.post({
            url: form.attr('action'),
            data: {settings: this.toParams()},
            dataType: 'json',
            complete: function (response) {
                $('#body-loader').hide();
                var data = response.responseJSON;
                var className = (data.success) ? 'alert-success' : 'alert-error';
                var content = '<div class="alert ' + className + '">' + data.message + '</div>';
                var modal = $('#response-modal');
                modal.find('.modal-body').html(content);
                modal.modal('show');
            }
        });
        console.log(this.toParams());
    };

    this.toParams = function () {
        var data = {};
        for (var field in this.data) {
            if (this.data.hasOwnProperty(field)) {
                data[field] = (typeof this.data[field] === "function") ? this.data[field]() : this.data[field];
            }
        }
        return data;
    };
    this.uuid = function (name) {
        return name;
    }
};
