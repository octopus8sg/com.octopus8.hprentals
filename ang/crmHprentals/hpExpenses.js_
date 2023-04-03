(function (angular, $, _) {
    // "hpExpenses" is a basic skeletal directive.
    // Example usage: <div hp-expenses="{foo: 1, bar: 2}"></div>
    angular.module('crmHprentals').directive('hpExpenses', function () {
        return {
            restrict: 'E',
            templateUrl: '~/crmHprentals/hpExpenses.html',
            // scope: {
            //     startDate: '@', // Use '@' for string parameter
            //     womanOnly: '@' // Use '@' for string parameter
            // },
            link: function (scope, element, attrs) {
                //     JSON.stringify({
                //     entity: 'Contact',
                //     select: {
                //         allowClear: true
                //     },
                //     api: {
                //         params: {
                //             'id': {
                //                 'IN': [348, 349]
                //             }
                //         }
                //     }
                // }); // for directive
                // Convert IDs string to array
                // let options = {where: [["id", "BETWEEN", [100, 500]], ["gender_id", "=", 1]]};
                function nl2br(str) {
                    return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
                }

                function getCodeById(id, myArray) {
                    for (let x = 0; x < myArray.length; x++) {
                        if (myArray[x].id == id) {
                            // console.log(myArray[x]);
                            return myArray[x].code;
                        }
                    }
                    return null; // or whatever you want to return if there's no match
                }

                var rentaloptions = {};
                var innerrentals = [];
                CRM.api4('RentalsRental', 'get', rentaloptions).then(function (result) {
                    innerrentals = result;
                    scope.myRentals = result;
                    scope.$apply();
                });
                scope.$watch('tenant', function (newVal, oldVal) {
                    // console.log('newVal', newVal)
                    rentaloptions = {
                        where: [["tenant_id", "=", newVal]],
                        limit: 0
                    };
                    CRM.api4('RentalsRental', 'get', rentaloptions).then(function (result) {
                        innerrentals = result;
                        scope.myRentals = result;
                        scope.$apply();
                    });
                });
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
                const options = {};
                // Call API to get contacts
                CRM.api4('RentalsExpense', 'get', options).then(function (result) {
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
