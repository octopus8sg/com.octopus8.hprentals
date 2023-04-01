(function (angular, $, _) {
    // "hpTextarea" is a basic skeletal directive.
    // Example usage: <div hp-textarea="{foo: 1, bar: 2}"></div>
    angular.module('crmHprentals').directive('hpTextarea', function () {
        return {
            restrict: 'E',
            replace: true,
            scope: {
                value: "="
            },
            template: '<textarea rows=15 ng-model="value"></textarea>',
            link: function (scope, element, attrs) {
                scope.$watch('value', function (newValue, oldValue) {

                    if (newValue) {
                        var textarea = $('af-field[name="description"]').find('textarea');
                        textarea.val(newValue);
                        textarea.trigger('input');
                    }
                });
            }
        }
    });
})(angular, CRM.$, CRM._);
