(function (angular, $, _) {
    // "hmAddButton" is a basic skeletal directive.
    // Example usage: <add-button name="add-something" label="Add Something" url="civicrm/something/something"></add-button>
    angular.module('crmHprentals').directive('hmAddButton', function () {
        return {
            restrict: 'E',
            templateUrl: '~/crmHprentals/hmAddButton.html',
            scope: {
                name: '@',
                label: '@',
                url: '@'
            },

            link: function (scope, element, attrs) {
                element.on('click', function (event) {
                    event.preventDefault();
                    const href = CRM.url(scope.url);
                    const $el = CRM.loadForm(href, {
                        dialog: {width: '50%', height: '50%'}
                    })
                        // .success()
                        .close;
                });
            }
        };
    });
})(angular, CRM.$, CRM._);
