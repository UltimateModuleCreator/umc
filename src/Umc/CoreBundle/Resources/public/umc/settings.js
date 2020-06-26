if (typeof UMC === "undefined") {
    UMC = {}
}
UMC.Settings = function (data, fields) {
    this.data = {};
    for (let group in fields) {
        if (fields.hasOwnProperty(group)) {
            this.data[group] = {};
            for (let i = 0; i < fields[group].length; i++ ) {
                let field = fields[group][i];
                let value = '';
                if (data[group] !== undefined) {
                    value = data[group][field] || '';
                }
                this.data[group][field] = ko.observable(value);
            }
        }
    }
    this.submitForm = function () {
        let form = $('#settings');
        $('#body-loader').show();
        $.post({
            url: form.attr('action'),
            data: {settings: this.toParams()},
            dataType: 'json',
            complete: function (response) {
                $('#body-loader').hide();
                let data = response.responseJSON;
                let className = (data.success) ? 'alert-success' : 'alert-danger';
                let content = '<div class="alert ' + className + '">' + data.message + '</div>';
                let modal = $('#response-modal');
                modal.find('.modal-body').html(content);
                modal.modal('show');
            }
        });
    };

    this.toParams = function () {
        let data = {};
        for (let group in this.data) {
            if (this.data.hasOwnProperty(group)) {
                data[group] = {};
                for (let field in this.data[group]) {
                    if (this.data[group].hasOwnProperty(field)) {
                         let value = (typeof this.data[group][field] === "function")
                            ? this.data[group][field]()
                            : this.data[group][field];
                        data[group][field] = typeof value === "boolean" ? (value ? 1 : 0) : value;;
                    }
                }
            }
        }
        return data;
    };
    this.uuid = function (name) {
        return name;
    }
};
