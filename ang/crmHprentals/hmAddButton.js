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
            // require: '^ngController', // Add this line to require the controller
            link: function (scope, element, attrs) {
                console.log(controller);
                element.on('click', function (event) {
                    event.preventDefault();
                    var href = CRM.url(scope.url);
                    var $el = CRM.loadForm(href, {
                        dialog: {width: '50%', height: '50%'}
                    }).on('crmPopupFormSuccess', function() {
                        // controller.refreshAfterTask();
                    }).close;
                });
            }
        };
    });
})(angular, CRM.$, CRM._);
