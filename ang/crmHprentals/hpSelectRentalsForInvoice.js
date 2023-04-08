(function (angular, $, _) {
    // "hpSelectRentalsForInvoice" is a basic skeletal directive.
    // Example usage: <hp-select-rentals-for-invoice></hp>
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
                    return months.map((monthNum) => {
                        return {name: monthNames[monthNum], value: monthNum};
                    });
                };

                function extractDates(rentals, selectedYear, selectedMonth) {
                    console.log("extractDates rentals", rentals);
                    let matchingRentals = rentals.filter((rental) => {
                        const admissionDate = new Date(rental.admission);
                        const admissionYear = admissionDate.getFullYear();
                        const admissionMonth = admissionDate.getMonth();
                        console.log("selectedYear", selectedYear, "admissionYear", admissionYear);
                        console.log("selectedMonth", selectedMonth, "admissionMonth", admissionMonth);
                        return admissionDate.getFullYear() === selectedYear && admissionDate.getMonth() === selectedMonth;
                    });
                    console.log("matchingRentals", matchingRentals);
                    const dateOptions = _.uniq(matchingRentals.map((rental) => {
                        const date = new Date(rental.admission).getDate();
                        return {
                            id: rental.id,
                            name: date
                        };
                    })).sort((a, b) => a.name - b.name);
                    console.log("dateOptions", dateOptions);
                    return dateOptions;
                }

                let rentaloptions = [];
                if (options) {
                    if (options.cid) {
                        let contact = options.cid;
                        if (contact) {
                            rentaloptions = {
                                select: ["id", "admission", "invoices.rental_id"],
                                join: [
                                    ["RentalsInvoice AS invoices", "LEFT",
                                        // [
                                        ["id", "=", "invoices.rental_id"],
                                        ["admission", "=", "invoices.start_date"]
                                        // ]
                                    ]
                                ],
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
                        $scope.monthOptions = extractMonths($scope.myRentals, newValue);
                        // $scope.$apply();
                    }
                });

                $scope.$watchGroup(['selectedYear', 'selectedMonth'], function (newValues, oldValues) {
                    if (newValues != undefined) {
                        if (newValues[0] != undefined) {
                            if (newValues[1] != undefined) {
                                if ($scope.myRentals != undefined) {
                                    const rentals = $scope.myRentals;
                                    console.log('$watchGroup', rentals);
                                    const selectedYear = newValues[0];
                                    const selectedMonth = newValues[1];
                                    const dateOptions = extractDates(rentals, selectedYear, selectedMonth);
                                    console.log('dateOptions', dateOptions);
                                    $scope.dateOptions = dateOptions;
                                }
                            }
                        }
                    }
                });
            }
        };
    };
    angular.module('crmHprentals').directive('hpSelectRentalsForInvoice', hpSRdde);

})(angular, CRM.$, CRM._);
