(function (angular, $, _) {

    angular.module('crmHprentals').config(function ($routeProvider) {
            $routeProvider.when('/about/me', {
                controller: 'CrmHprentalsEditCtrl',
                controllerAs: '$ctrl',
                templateUrl: '~/crmHprentals/EditCtrl.html',

                // If you need to look up data when opening the page, list it out
                // under "resolve".
                resolve: {
                    myContact: function (crmApi) {
                        return crmApi('Contact', 'getsingle', {
                            id: 'user_contact_id',
                            return: ['first_name', 'last_name']
                        });
                    },
                    myExpenses: function (crmApi) {
                        return crmApi('RentalsExpense', 'get', {
                            return: ['id', 'name', 'frequency', 'is_refund', 'is_prorate', 'amount']
                        });
                    }
                }
            });
        }
    );

    // The controller uses *injection*. This default injects a few things:
    //   $scope -- This is the set of variables shared between JS and HTML.
    //   crmApi, crmStatus, crmUiHelp -- These are services provided by civicrm-core.
    //   myContact -- The current contact, defined above in config().
    angular.module('crmHprentals').controller('CrmHprentalsEditCtrl', function ($scope, crmApi, crmStatus, crmUiHelp, myContact, myExpenses) {
        // The ts() and hs() functions help load strings for this module.
        var ts = $scope.ts = CRM.ts('com.octopus8.hprentals');
        var hs = $scope.hs = crmUiHelp({file: 'CRM/crmHprentals/EditCtrl'}); // See: templates/CRM/crmHprentals/EditCtrl.hlp
        // Local variable for this controller (needed when inside a callback fn where `this` is not available).
        var ctrl = this;

        // We have myContact available in JS. We also want to reference it in HTML.
        this.myContact = myContact;
        var frequency = {
            "once_off": "Once Off",
            "every_month": "Every Month",
            "less_than_6_m": "Less than 6 months"
        };
        $scope.frequency = frequency;
        Expenses = myExpenses.values;
        $scope.myExpenses = Expenses;
        $scope.calculateTotal = function () {
            var total = 0.00;
            angular.forEach($scope.myExpenses, function (myExpense) {
                if (myExpense.checked) {
                    total += parseFloat(myExpense.amount);
                }
            });
            return total;
        };
        this.save = function () {
            return crmStatus(
                // Status messages. For defaults, just use "{}"
                {start: ts('Saving...'), success: ts('Saved')},
                // The save action. Note that crmApi() returns a promise.
                crmApi('Contact', 'create', {
                    id: ctrl.myContact.id,
                    first_name: ctrl.myContact.first_name,
                    last_name: ctrl.myContact.last_name
                })
            );
        };
    });

})(angular, CRM.$, CRM._);
