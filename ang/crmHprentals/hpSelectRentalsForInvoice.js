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
                // console.log('scope.routeParams', options)
                const ts = $scope.ts = CRM.ts('com.octopus8.hprentals');
                const extractYears = (rentals) => {
// Extract list of unique years from non-invoiced rentals
                    var years = _.uniq(rentals.map(function (rental) {
                        return new Date(rental.admission).getFullYear();
                    }));
                    // console.log(years);
                    return years;
                };
                const getCodeById = (id, rentals) => {
                    for (let x = 0; x < rentals.length; x++) {
                        if (rentals[x].id === id) {
                            // console.log(rentals[x]);
                            return rentals[x].code;
                        }
                    }
                    return null; // or whatever you want to return if there's no match
                };
                const getStartById = (id, rentals) => {
                    for (let x = 0; x < rentals.length; x++) {
                        if (rentals[x].id === id) {
                            // console.log(rentals[x]);
                            return rentals[x].admission;
                        }
                    }
                    return null; // or whatever you want to return if there's no match
                };
                const getEndById = (id, rentals) => {
                    for (let x = 0; x < rentals.length; x++) {
                        if (rentals[x].id === id) {
                            console.log(rentals[x]);
                            lastDateForRental = lastDayOfTheMonthForDate(rentals[x].admission);
                            console.log('lastDateForRental', lastDateForRental);
                            console.log('rentals[x].discharge', rentals[x].discharge);
                            if (rentals[x].discharge !== null) {
                                return rentals[x].discharge;
                            } else {
                                return lastDayOfTheMonthForDate(rentals[x].admission); // or whatever you want to return if there's no match
                            }
                        }
                    }

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
                const prorateFromTheStartOfMonth = (price, date_end_str) => {
                    const date_end = new Date(date_end_str);
                    const lastDayOfMonth = new Date(date_end.getFullYear(), date_end.getMonth() + 1, 0).getDate();
                    const daysInMonth = lastDayOfMonth - date_end.getDate() + 1;
                    const proratedAmount = price * (daysInMonth / lastDayOfMonth);
                    return proratedAmount.toFixed(2);
                };

// Calculates the prorated amount from the given date until the end of the month
                const prorateTillTheEndOfMonth = (price, date_start_str) => {
                    // console.log("I have this", date_start_str);
                    const date_start = new Date(date_start_str);
                    // console.log("I have this Date", date_start);
                    const lastDayOfMonth = new Date(date_start.getFullYear(), date_start.getMonth() + 1, 0).getDate();
                    const daysInMonth = lastDayOfMonth - date_start.getDate() + 1;
                    const proratedAmount = price * (daysInMonth / lastDayOfMonth);
                    return proratedAmount.toFixed(2);
                };
                const lastDayForDate = (date_start_str) => {
                    const lastDay = new Date(date_start_str).toLocaleDateString('en-US', {
                        month: '2-digit',
                        day: '2-digit',
                        year: 'numeric'
                    });
                    ;
                    return lastDay;
                };
                const lastDayOfTheMonthForDate = (date_str) => {
                    const date = new Date(date_str);
                    const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
                    const year = date.getFullYear();
                    let month = date.getMonth() + 1;
                    if (month < 10) {
                        month = "0" + month;
                    }
                    let dateString = year + "-" + month + "-" + lastDay;
                    return dateString;
                };

                const firstDayForDate = (date_start_str) => {
                    const date_start = new Date(date_start_str);
                    let firstDay = date_start.toLocaleDateString('en-US', {
                        month: '2-digit',
                        day: '2-digit',
                        year: 'numeric'
                    });
                    ;
                    return firstDay;
                };
                const firstDayOfTheMonthForDate = (date_str) => {
                    const date = new Date(date_str);
                    let firstDay = date.getDate();
                    const year = date.getFullYear();
                    let month = date.getMonth() + 1;
                    if (month < 10) {
                        month = "0" + month;
                    }
                    if (firstDay < 10) {
                        firstDay = "0" + firstDay;
                    }
                    let dateString = year + "-" + month + "-" + firstDay;
                    return dateString;
                };

// Calculates the prorated amount between the start and end date
                const prorateWithStartAndEnd = (price, date_start_str, date_end_str) => {
                    const date_end = new Date(date_end_str);
                    const date_start = new Date(date_start_str);
                    const startDay = date_start.getDate();
                    const endDay = date_end.getDate();
                    const lastDayOfMonth = new Date(date_end.getFullYear(), date_end.getMonth() + 1, 0).getDate();
                    const daysInMonth = lastDayOfMonth - startDay + 1;
                    const proratedAmount = price * (daysInMonth / lastDayOfMonth);

                    if (startDay === 1 && endDay === lastDayOfMonth) {
                        return proratedAmount.toFixed(2);
                    }

                    const daysInRange = endDay - startDay + 1;
                    const proratedAmountInRange = price * (daysInRange / lastDayOfMonth);
                    return proratedAmountInRange.toFixed(2);
                };
                $scope.frequency = {
                    "once_off": "Once Off",
                    "every_month": "Every Month",
                    "less_than_6_m": "Less than 6 months",
                    "more_than_6_m": "More than 6 months"
                };
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
                    // console.log("extractDates rentals", rentals);
                    let matchingRentals = rentals.filter((rental) => {
                        const admissionDate = new Date(rental.admission);
                        const admissionYear = admissionDate.getFullYear();
                        const admissionMonth = admissionDate.getMonth();
                        // console.log("selectedYear", selectedYear, "admissionYear", admissionYear);
                        // console.log("selectedMonth", selectedMonth, "admissionMonth", admissionMonth);
                        return admissionDate.getFullYear() === selectedYear && admissionDate.getMonth() === selectedMonth;
                    });
                    // console.log("matchingRentals", matchingRentals);
                    const dateOptions = _.uniq(matchingRentals.map((rental) => {
                        const date = new Date(rental.admission).getDate();
                        return {
                            id: rental.id,
                            name: date
                        };
                    })).sort((a, b) => a.name - b.name);
                    // console.log("dateOptions", dateOptions);
                    return dateOptions;
                }

                const today = new Date();
                const today_str = today.toDateString();
                const lastDayOfCurrentMonth = lastDayOfTheMonthForDate(today_str);
                const firstDayDateOfCurrentMonth = firstDayOfTheMonthForDate(today_str);
                const currentMonth = today.getMonth() + 1; // Add 1 because getMonth() returns 0-based index
                const currentYear = today.getFullYear();
                let rentaloptions = {
                    select: ["id", "admission", "discharge", "invoices.rental_id"],
                    join: [
                        ["RentalsInvoice AS invoices", "LEFT",
                            // [
                            ["id", "=", "invoices.rental_id"],
                            ["admission", "=", "invoices.start_date"]
                            // ]
                        ]
                    ],
                    where: [
                        ["invoices.rental_id", "IS NULL"],
                        ["OR", [["discharge", "BETWEEN", [firstDayDateOfCurrentMonth, lastDayOfCurrentMonth]], ["discharge", "IS NULL"]]],
                    ],
                    limit: 0
                };
                if (options) {
                    if (options.cid) {
                        let contact = options.cid;
                        if (contact) {
                            rentaloptions = {
                                select: ["id", "admission", "discharge", "invoices.rental_id"],
                                join: [
                                    ["RentalsInvoice AS invoices", "LEFT",
                                        // [
                                        ["id", "=", "invoices.rental_id"],
                                        ["admission", "=", "invoices.start_date"]
                                        // ]
                                    ]
                                ],
                                where: [
                                    ["invoices.rental_id", "IS NULL"],
                                    ["tenant_id", "=", contact],
                                    ["OR", [["discharge", "BETWEEN", [firstDayDateOfCurrentMonth, lastDayOfCurrentMonth]], ["discharge", "IS NULL"]]],
                                ],
                                limit: 0
                            };
                        }
                    }
                }

                console.log(rentaloptions);
                CRM.api4('RentalsRental', 'get', rentaloptions).then((result) => {
                    $scope.years = extractYears(result);
                    $scope.myRentals = result;
                    // console.log(result);
                    $scope.$apply();
                });
                $scope.months = [];
                $scope.$watch('selectedYear', (newValue, oldValue) => {
                    if (newValue !== oldValue && newValue) {
                        $scope.monthOptions = extractMonths($scope.myRentals, newValue);
                        // $scope.$apply();
                    }
                });
                const roptions = {};
                // Call API to get contacts
                CRM.api4('RentalsExpense', 'get', {}).then((expenses) => {
                    expenses.forEach((expense) => {
                        expense.checked = false;
                        expense.total = 0;
                    });
                    $scope.myExpenses = expenses;
                    $scope.$apply();
                });

                $scope.$watchGroup(['selectedYear', 'selectedMonth'], (newValues, oldValues) => {
                    if (newValues != undefined) {
                        if (newValues[0] != undefined) {
                            if (newValues[1] != undefined) {
                                if ($scope.myRentals != undefined) {
                                    const rentals = $scope.myRentals;
                                    // console.log('$watchGroup', rentals);
                                    const selectedYear = newValues[0];
                                    const selectedMonth = newValues[1];
                                    const dateOptions = extractDates(rentals, selectedYear, selectedMonth);
                                    // console.log('dateOptions', dateOptions);
                                    $scope.dateOptions = dateOptions;
                                }
                            }
                        }
                    }
                });

                $scope.$watch('selectedDate', (newValue, oldValue) => {
                    // console.log('rentalnewVal', newValue)
                    const myRentals = $scope.myRentals;
                    const myExpenses = $scope.myExpenses;
                    const onceoffrequences = ["once_off", "every_month", "less_than_6_m"];
                    if (newValue) {
                        const rentaltextarea = $('af-field[name="rental_id"]').find('textarea');
                        rentaltextarea.val(newValue);
                        // console.log(myRentals);
                        const myRentalCode = getCodeById(newValue, myRentals);
                        const myRentalStart = getStartById(newValue, myRentals);
                        console.log(myRentalStart);
                        const myRentalEnd = getEndById(newValue, myRentals);
                        console.log(myRentalEnd);
                        const lastDayDate = lastDayForDate(myRentalEnd);
                        const firstDayDate = firstDayForDate(myRentalStart);
                        // console.log(myRentalStart);
                        // _.findWhere($scope.myExpenses, {name: 'Less than 6 month'});
                        $scope.hpRentalCode = myRentalCode; // output: 'c00353a211002d220106'
                        myExpenses.forEach((expence) => {
                            if (onceoffrequences.includes(expence.frequency)) {
                                expence.checked = true;
                                const price = expence.amount;
                                if (expence.is_prorate == 1) {
                                    expence.total = prorateWithStartAndEnd(price, myRentalStart, myRentalEnd);
                                }
                                if (expence.is_prorate == 0) {
                                    expence.total = price;
                                }
                            }
                        });
                        rentaltextarea.trigger('input');
                        const end_date = $('af-field[name="end_date"]').find('input[type="text"]');
                        const start_date = $('af-field[name="start_date"]').find('input[type="text"]');
                        start_date.val(firstDayDate);
                        start_date.trigger('input');
                        start_date.trigger('change');
                        end_date.val(lastDayDate);
                        end_date.trigger('input');
                        end_date.trigger('change');

                    }
                });

                $scope.calculateTotal = () => {
                    let total = 0.00, arrtotal = [], desctotal = "", i = 1;

                    angular.forEach($scope.myExpenses, function (myExpense) {
                        if (myExpense.checked) {
                            total += parseFloat(myExpense.total);
                            desctotal += i + ". " + myExpense.name + " $" + myExpense.total + "\n";
                            arrtotal.push(i + ". " + myExpense.name + " $" + myExpense.total + "\n");
                            i = i + 1;
                        }
                    });
                    // console.log(arrtotal, arrtotal);
                    $scope.totalSum = total;
                    $scope.arrTotal = arrtotal;
                    $scope.hpRentalDescription = desctotal;
                    $scope.hpRentalAmount = "$" + total;
                    const descriptiontextarea = $('af-field[name="description"]').find('textarea');
                    descriptiontextarea.val(desctotal);
                    descriptiontextarea.trigger('input');
                    const amounttext = $('af-field[name="amount"]').find('input[type="text"]');
                    amounttext.val(total);
                    amounttext.trigger('input');
                    return total;

                };
            }
        };
    };
    angular.module('crmHprentals').directive('hpSelectRentalsForInvoice', hpSRdde);

})
(angular, CRM.$, CRM._);
