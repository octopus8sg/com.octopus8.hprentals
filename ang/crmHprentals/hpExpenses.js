(function (angular, $, _) {
    // "hpExpenses" is a basic skeletal directive.
    // Example usage: <div hp-expenses="{foo: 1, bar: 2}"></div>
    angular.module('crmHprentals').directive('hpExpenses', function () {
        return {
            restrict: 'E',
            templateUrl: '~/crmHprentals/hpExpenses.html',

            link: function (scope, element, attrs) {

                function getCodeById(id, myArray) {
                    for (let x = 0; x < myArray.length; x++) {
                        if (myArray[x].id == id) {
                            // console.log(myArray[x]);
                            return myArray[x].display_name + " " + myArray[x].admission;
                        }
                    }
                    return null; // or whatever you want to return if there's no match
                }

                var rentaloptions = {select: ["id", "admission","code", "tenant.display_name"],
                    join: [["Contact AS tenant", "LEFT", ["tenant_id", "=", "tenant.id"]]],
                    limit: 0};
                var innerrentals = [];
                var options = scope.routeParams;
                // console.log(options);
                let lookfortenant = true;
                if (options) {
                    if (options.cid) {
                        let contact = options.cid;
                        if (contact) {
                            rentaloptions = {
                                where: [["tenant_id", "=", contact]],
                                select: ["id", "admission", "code", "tenant.display_name"],
                                join: [["Contact AS tenant", "LEFT", ["tenant_id", "=", "tenant.id"]]],
                                limit: 0
                            };
                            lookfortenant = false;
                        }
                    }
                }

                CRM.api4('RentalsRental', 'get', rentaloptions).then(function (result) {
                    innerrentals = result.map(rental => {
                        const keys = Object.keys(rental);
                        const modifiedRental = {};
                        keys.forEach(key => {
                            if (key.includes('.')) {
                                const nestedKeys = key.split('.');
                                modifiedRental[nestedKeys[1]] = rental[key];
                            } else {
                                modifiedRental[key] = rental[key];
                            }
                        });
                        return modifiedRental;
                    });
                    scope.myRentals = innerrentals;
                    scope.$apply();
                });
                // console.log('tenant lookfortenant', lookfortenant)

                scope.$watch('myrental', function (newValue, oldValue) {
                    // console.log('rentalnewVal', newValue)
                    let myRentalCode = "";
                    if (newValue) {
                        var rentaltextarea = $('af-field[name="rental_id"]').find('textarea');
                        rentaltextarea.val(newValue);
                        // console.log(innerrentals);
                        myRentalCode = getCodeById(newValue, innerrentals);
                        scope.hpRentalCode = myRentalCode; // output: 'c00353a211002d220106'
                        rentaltextarea.trigger('input');
                    }
                });

                scope.frequency = {
                    "once_off": "Once Off",
                    "every_month": "Every Month",
                    "less_than_6_m": "Less than 6 months"
                };
                scope.calculateTotal = function () {
                    var total = 0.00, arrtotal = [], desctotal = "", i = 1;


                    angular.forEach(scope.myExpenses, function (myExpense) {
                        if (myExpense.checked) {
                            total += parseFloat(myExpense.amount);
                            desctotal += i + ". " + myExpense.name + " $" + myExpense.amount + "\n";
                            arrtotal.push(i + ". " + myExpense.name + " $" + myExpense.amount + "\n");
                            i = i + 1;
                        }
                    });
                    // console.log(arrtotal, arrtotal);
                    scope.totalSum = total;
                    scope.arrTotal = arrtotal;
                    scope.hpRentalDescription = desctotal;
                    scope.hpRentalAmount = "$" + total;
                    var descriptiontextarea = $('af-field[name="description"]').find('textarea');
                    descriptiontextarea.val(desctotal);
                    descriptiontextarea.trigger('input');
                    var amounttext = $('af-field[name="amount"]').find('input[type="text"]');
                    amounttext.val(total);
                    amounttext.trigger('input');
                    return total;

                };
                const roptions = {};
                // Call API to get contacts
                CRM.api4('RentalsExpense', 'get', roptions).then(function (result) {
                    scope.myExpenses = result;
                    scope.$apply();
                });
            }
        }
            ;
    })
    ;
})
(angular, CRM.$, CRM._);
