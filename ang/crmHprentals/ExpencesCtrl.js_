(function (angular, $, _) {

    angular.module('crmHprentals').config(function ($routeProvider) {
            $routeProvider.when('/rentals/expenses', {
                controller: 'CrmHprentalsExpencesCtrl',
                controllerAs: '$ctrl',
                templateUrl: '~/crmHprentals/ExpencesCtrl.html',

                // If you need to look up data when opening the page, list it out
                // under "resolve".
                resolve: {
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
    angular.module('crmHprentals').controller('CrmHprentalsExpencesCtrl', function ($scope, crmApi, crmStatus, crmUiHelp, myExpenses) {
        // The ts() and hs() functions help load strings for this module.
        var ts = $scope.ts = CRM.ts('com.octopus8.hprentals');
        var hs = $scope.hs = crmUiHelp({file: 'CRM/crmHprentals/ExpencesCtrl'}); // See: templates/CRM/crmHprentals/ExpencesCtrl.hlp
        // Local variable for this controller (needed when inside a callback fn where `this` is not available).
        var ctrl = this;

        // We have myContact available in JS. We also want to reference it in HTML.
        this.myContact = myContact;
        var frequency = {
            "once_off": "Once Off",
            "every_month": "Every Month",
            "less_than_6_m": "Less than 6 months"
        };
        this.frequency = frequency;
        Expenses = myExpenses.values;
        this.myExpenses = Expenses;
        this.calculateTotal = function () {
            var total = 0.00;
            angular.forEach(this.myExpenses, function (myExpense) {
                if (myExpense.checked) {
                    total += parseFloat(myExpense.amount);
                }
            });
            return total;
        };

        this.calculateTotalText = function () {
            var total = 0.00;
            angular.forEach(this.myExpenses, function (myExpense) {
                if (myExpense.checked) {
                    total += parseFloat(myExpense.amount);
                }
            });
            return total;
        };

    });

})(angular, CRM.$, CRM._);
