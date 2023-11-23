'use strict';

window._datetimepickers = [];

window._registerDatetimepicker = function _registerDatetimepicker(e) {
    window._datetimepickers.push(e);
};

window._resetDatetimepickers = function resetDatetimepickers() {
    window._datetimepickers = [];
};

window._getDatetimepicker = function getDatetimepicker(idx) {
    return window._datetimepickers[idx];
};

angular
    .module('datetimepicker', [])

    .provider('datetimepicker', function () {
        var default_options = {};

        this.setOptions = function (options) {
            default_options = options;
        };

        this.$get = function () {
            return {
                getOptions: function () {
                    return default_options;
                }
            };
        };
    })

    .directive('datetimepicker', [
        '$timeout',
        'datetimepicker',
        function ($timeout,
                  datetimepicker) {

            var default_options = datetimepicker.getOptions();

            return {
                require : '?ngModel',
                restrict: 'AE',
                scope   : {
                    datetimepickerOptions: '@'
                },
                link    : function ($scope, $element, $attrs, ngModelCtrl) {
                    var passed_in_options = $scope.$eval($attrs.datetimepickerOptions);
                    var options = jQuery.extend({}, default_options, passed_in_options);

                    window._registerDatetimepicker($($element));

                    $($element)
                        .on('dp.change', function (e) {
                            if (ngModelCtrl) {
                                $timeout(function () {
                                    ngModelCtrl.$setViewValue(e.target.value);
                                });
                            }
                        })
                        .datetimepicker(options);

                    function setPickerValue() {
                        var date = options.defaultDate || null;

                        if (ngModelCtrl && ngModelCtrl.$viewValue) {
                            date = ngModelCtrl.$viewValue;
                        }

                        $($element)
                            .data('DateTimePicker')
                            .date(date);
                    }

                    if (ngModelCtrl) {
                        ngModelCtrl.$render = function () {
                            setPickerValue();
                        };
                    }

                    setPickerValue();
                }
            };
        }
    ]);
