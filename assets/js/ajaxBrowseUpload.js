;(function($) {
    'use strict';

    $.fn.AjaxBrowseUpload = function(options = {}) {

        if ($.isFunction(options)) {
            options = {};
        }

        if (options.allowTypes) {
            options.allowTypes = Array.isArray(options.allowTypes) ? options.allowTypes : options.allowTypes.split(',')
        }

        // OPTIONS
        var setting = $.extend({
            name: 'file',
            url: '/core/ajaxUploadFile/',
            section: '',
            allowTypes: ['png', 'jpg', 'gif', 'pdf'],
            maxSize: 2048,
            timeout: 60000,
            addOn: {}
        }, options );

        setting.allowTypes.map(a => {
            return a.toLowerCase().trim();
        });

        var $box = this;
        $box.id = md5();
        $box
            .addClass('browse-upload-box')
            .attr('data-id', $box.id);


        $box.doUpload = function() {
            return _doUpload();
        };

        $box.getId = function() {
            return $box.id;
        };

        return $box.each(function() {
            if ($(this).data('AjaxBrowseUpload')) return;
            init();
        });


        function init() {
            // ...
        };


        function md5() {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < 24; i++) {
                result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
            }
            return result;
        }


        function _doUpload() {
            $box.append('<div class="browse-upload-progress"><div></div><span class="status">0%</span></div>');
            let formData = new FormData();
            let inputFile = $box.find('input[type="file"]');
            let file = inputFile[0].files[0];
            formData.append(setting.name, file, file.name);
            formData.append("section", setting.section);
            formData.append("allowed_types", setting.allowTypes.join('|'));
            formData.append("max_size", setting.maxSize);
            if (setting.addOn.length) {
                for (let k in setting.addOn) {
                    formData.append(k, setting.addOn[k]);
                }
            }

            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    url: setting.url + setting.name,
                    xhr: function (event) {
                        var myXhr = $.ajaxSettings.xhr();
                        if (myXhr.upload) {
                            myXhr.upload.addEventListener('progress', function (event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(position / total * 100);
                                }

                                $box.find('.browse-upload-progress > div').css("width", +percent + "%").end()
                                    .find('.browse-upload-progress > .status').text(percent + "%");
                            }, false);
                        }
                        return myXhr;
                    },
                    success: function (data) {
                        if (data.status == 200) {

                        }
                        $box.find('.browse-upload-progress').remove();
                        resolve(data)
                    },
                    error: function (error) {
                        $box.find('.browse-upload-progress').remove();
                        inputFile.val('');
                        reject(error)
                    },
                    async: true,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    timeout: setting.timeout
                });
            });
        };

    };

})(jQuery);
