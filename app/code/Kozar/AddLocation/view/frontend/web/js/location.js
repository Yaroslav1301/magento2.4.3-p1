define(
    [
        'jquery',
        'Magento_Ui/js/modal/modal',
        'ko'
    ],
    function ($, modal, ko) {
        "use strict";

        function initialize()
        {
            var options = {
                types: ['(cities)'],
                componentRestrictions: {
                    country: "ua"
                }
            };

            var input = document.getElementById('autocomplete_city');
            return new google.maps.places.Autocomplete(input, options);
        }

        function getLocationByMyIp()
        {
            $.ajax({
                url: "https://ipapi.co/json/",
                type: "POST",
                showLoader: true,
                cache: false,
                success: function (res) {
                    localStorage['location'] = res['city'];
                    localStorage['check'] = 'true';
                    $('#ip').text(localStorage['location']);
                }
            });
        }

        function getLocationByUser()
        {
            $('#ip_image, #ip').click(function () {
                var autocomlete = initialize();
                var options = {
                    type: 'popup',
                    responsive: true,
                    title: $.mage.__('Оберіть своє місто'),
                    buttons: [{
                        text: $.mage.__('Ok'),
                        class: '',
                        click: function () {
                            localStorage['location'] = autocomlete.getPlace().vicinity;
                            $('#ip').text(localStorage['location']);
                            this.closeModal();
                        }
                    },{
                        text: $.mage.__('Change to first location'),
                        click: function () {
                            getLocationByMyIp();
                            $('#ip').text(localStorage['location']);
                            this.closeModal();
                        }
                    }
                    ]
                };
                var currentModal = $('#modal');
                var popup = modal(options, currentModal);
                currentModal.modal('openModal');
            });
        }

        return function location()
        {
            $('#ip').text(localStorage['location']);
            if (localStorage['check'] !== 'true') {
                getLocationByMyIp();
            }
            getLocationByUser();

        }
    }
);



