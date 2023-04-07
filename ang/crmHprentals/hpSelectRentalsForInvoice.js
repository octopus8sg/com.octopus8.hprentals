(function (angular, $, _) {
    // "hpSelectRentalsForInvoice" is a basic skeletal directive.
    // Example usage: <hp-select-rentals-for-invoice></hp>
    angular.module('crmHprentals').directive('hpSelectRentalsForInvoice', hpSRdde);
    const hpSRdde = function () {
        return {
            restrict: 'E',
            templateUrl: '~/crmHprentals/hpSelectRentalsForInvoice.html',
            scope: {
                hpSelectRentalsForInvoice: '='
            },
            link: function ($scope, $el, $attr) {
                const options = $scope.$parent.routeParams;
                console.log('scope.routeParams', options)
                const ts = $scope.ts = CRM.ts('com.octopus8.hprentals');
                const extractYears = (rentals) => {
// Extract list of unique years from non-invoiced rentals
                    var years = _.uniq(rentals.map(function (rental) {
                        return new Date(rental.admission).getFullYear();
                    }));
                    console.log(years);
                    return years;
                };

                const monthNames = ['January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'];

// Function to extract list of months from rentals for a given year
                const extractMonths = (rentals, year) => {
                    var months = [];

                    rentals.forEach(function (rental) {
                        var admissionDate = new Date(rental.admission);
                        if (admissionDate.getFullYear() === year) {
                            var monthNum = admissionDate.getMonth();
                            if (months.indexOf(monthNum) === -1) {
                                months.push(monthNum);
                            }
                        }
                    });

                    // Sort the list of month numbers
                    months.sort();

                    // Map month numbers to month names
                    return months.map( (monthNum) => monthNames[monthNum]);
                };

                function extractDates(rentals, selectedYear, selectedMonth) {
                    console.log("extractDates rentals", rentals);
                    let matchingRentals = rentals.filter( (rental) => {
                        const admissionDate = new Date(rental.admission);
                        return admissionDate.getFullYear() === selectedYear && admissionDate.getMonth() === selectedMonth;
                    });

                    let dates = _.uniq(matchingRentals.map((rental) => new Date(rental.admission).getDate() ).sort();

                    return dates;
                }

                var rentaloptions = [];
                if (options) {
                    if (options.cid) {
                        let contact = options.cid;
                        if (contact) {
                            rentaloptions = {
                                select: ["id", "admission", "invoices.rental_id"],
                                join: [["RentalsInvoice AS invoices", "LEFT", ["id", "=", "invoices.rental_id"]]],
                                where: [["invoices.rental_id", "IS NULL"], ["tenant_id", "=", contact]],
                                limit: 0
                            };
                        }
                    }
                }

                console.log(rentaloptions);
                CRM.api4('RentalsRental', 'get', rentaloptions).then(function (result) {
                    $scope.years = extractYears(result);
                    $scope.myRentals = result;
                    console.log(result);
                    $scope.$apply();
                });
                $scope.months = [];
                $scope.$watch('selectedYear', function (newValue, oldValue) {
                    if (newValue !== oldValue && newValue) {
                        $scope.months = extractMonths($scope.myRentals, newValue);
                        // $scope.$apply();
                    }
                });

                $scope.$watchGroup(['selectedYear', 'selectedMonth'], function (newValues, oldValues) {
                    if (newValues != undefined) {
                        if (newValues[0] != undefined) {
                            if ($scope.myRentals != undefined) {
                                const rentals = $scope.myRentals;
                                console.log('$watchGroup', rentals);
                                const selectedYear = newValues[0];
                                const selectedMonth = newValues[1];

                                $scope.dates = extractDates(rentals, selectedYear, selectedMonth);
                            }
                        }
                    }
                });
            }
        };
    };
})(angular, CRM.$, CRM._);
