(function (angular, $, _) {
    // "hpExpenses" is a basic skeletal directive.
    // Example usage: <div hp-expenses="{foo: 1, bar: 2}"></div>
    angular.module('crmHprentals').directive('hpExpenses', function () {
        return {
            restrict: 'AE',
            templateUrl: '~/crmHprentals/hpExpenses.html',
            scope: {
                startDate: '@', // Use '@' for string parameter
                womanOnly: '@' // Use '@' for string parameter
            },
            link: function (scope, element, attrs) {
                // Convert IDs string to array
                var ids = scope.ids.split(',');

                // Remove any non-numeric characters from each ID
                ids = ids.map(function (id) {
                    return id.replace(/\D/g, '');
                });

                // Remove any empty IDs
                ids = ids.filter(function (id) {
                    return id !== '';
                });

                // Build API options object
                var apiOptions = {
                    entity: 'Contact',
                    action: 'get',
                    options: {
                        sequential: 1
                    }
                };

                if (ids.length > 0) {
                    apiOptions.params = {
                        contact_id: {
                            IN: ids.join(',')
                        }
                    };
                }

                if (scope.womanOnly === '1') {
                    apiOptions.params
                }
                let options = {where: [["id", "BETWEEN", [100, 500]], ["gender_id", "=", 1]]};
                // Call API to get contacts
                CRM.api4('Contact', 'get', options).then(function (result) {
                    scope.contacts = result;
                    scope.$apply();
                });
            }
        };
    });
})(angular, CRM.$, CRM._);
