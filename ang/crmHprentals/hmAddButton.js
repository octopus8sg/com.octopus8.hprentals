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
            // can be array of parents directive too
            link: function (scope, element, attrs,) {
                var href = CRM.url(scope.url);
                let options = scope.$parent.options;
                if (options) {
                    if (options.contact_id) {
                        let contact = options.contact_id;
                        if (contact) {
                            var href = CRM.url(scope.url + "#?cid=["+contact+"]");
                        }
                    }
                }

                element.on('click', function(event) {
                    event.stopPropagation();
                });
                scope.onClick = function() {

                    var $el =
                        CRM.loadForm(href, {
                            dialog: {width: '50%', height: '50%'}
                        }).on('crmFormSuccess crmPopupFormSuccess crmFormSubmit', function (e, data) {
                            $('.crm-sortable-col:first').click();
                        }).close;
                };
            }
        };
    });
})(angular, CRM.$, CRM._);

