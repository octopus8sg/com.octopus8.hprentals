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
                    // console.log(rentals);
                    const allYears = [];

                    // Iterate through each rental
                    rentals.forEach(function (rental) {
                        const dischargeDate = rental.discharge ? new Date(rental.discharge) : new Date();
                        const admissionYear = new Date(rental.admission).getFullYear();
                        const dischargeYear = new Date(dischargeDate).getFullYear();

                        // Extract years between admission and discharge (inclusive)
                        for (let year = admissionYear; year <= dischargeYear; year++) {
                            allYears.push(year);
                        }
                    });
                    // Remove duplicates by converting the array to a Set and back to an array
                    const uniqueYears = [...new Set(allYears)];
                    // console.log(uniqueYears);
                    return uniqueYears;
                };

                const getRentalById = (id, rentals) => {
                    for (let x = 0; x < rentals.length; x++) {
                        if (rentals[x].id === id) {
                            // console.log(rentals[x]);
                            return rentals[x];
                        }
                    }
                    return null; // or whatever you want to return if there's no match
                };

                const getRentalStart = (rental, selectedYear, selectedMonth) => {
                    const admissionDate = new Date(rental.admission);
                    if (
                        admissionDate.getFullYear() === selectedYear &&
                        admissionDate.getMonth() === selectedMonth
                    ) {
                        return rental.admission;
                    } else {
                        return new Date(selectedYear, selectedMonth, 1);
                    }
                };

                const getRentalEnd = (rental, selectedYear, selectedMonth) => {
                    const dischargeDate = new Date(rental.discharge);
                    if (
                        dischargeDate.getFullYear() === selectedYear &&
                        dischargeDate.getMonth() === selectedMonth
                    ) {
                        return rental.discharge;
                    } else {
                        // Discharge date not in selectedYear and selectedMonth, return last day
                        const lastDay = new Date(selectedYear, selectedMonth + 1, 0);
                        return new Date(
                            lastDay.getFullYear(),
                            lastDay.getMonth(),
                            lastDay.getDate(),
                            23, // 23:59:59
                            59,
                            59
                        );
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
                    const lastDay = new Date(date_start_str).toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: '2-digit',
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
                    let firstDay = date_start.toLocaleDateString('en-GB', {
                        day: '2-digit',
                        month: '2-digit',
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
                        const admissionDate = new Date(rental.admission);
                        const thisDate = new Date();
                        const nextMonth = new Date(thisDate);
                        nextMonth.setMonth(thisDate.getMonth() + 1);
                        const dischargeDate = rental.discharge ? new Date(rental.discharge) : nextMonth;
                        // Iterate through months between admission and discharge (inclusive)
                        let currentDate = new Date(admissionDate);
                        while (currentDate <= dischargeDate) {
                            const monthNum = currentDate.getMonth();
                            const yearNum = currentDate.getFullYear();
                            if (yearNum === year) {
                                if (months.indexOf(monthNum) === -1) {
                                    months.push(monthNum);
                                }
                            }
                            currentDate.setMonth(currentDate.getMonth() + 1); // Move to the next month
                        }

                    });

                    const numericalSort = (a, b) => a - b;
                    months.sort(numericalSort);

                    // Map month numbers to month names
                    return months.map((monthNum) => {
                        return {name: monthNames[monthNum], value: monthNum};
                    });
                };

                function calculateRentalLengthInMonths(rental, dischargeDate) {
                    // Check if admission and discharge dates are provided
                    if (rental.admission ) {
                        // Convert admission and discharge dates to Date objects
                        const admissionDate = new Date(rental.admission);

                        // Calculate the time difference in milliseconds
                        const timeDifference = dischargeDate - admissionDate;

                        // Calculate the length of the rental period in days
                        const rentalLengthInDays = timeDifference / (1000 * 3600 * 24);

                        // Approximate the length in months based on an average of 30.44 days per month
                        const rentalLengthInMonths = rentalLengthInDays / 30.44;

                        return rentalLengthInMonths;
                    } else {
                        // If admission or discharge date is missing, return null or handle the case as needed
                        return null;
                    }
                }

                function extractRentals(rentals, selectedYear, selectedMonth) {
                    let matchingRentals = rentals.filter((rental) => {
                        const admissionDate = new Date(rental.admission);
                        const dischargeDate = rental.discharge ? new Date(rental.discharge) : new Date();
                        // Get the year and month parts of the dates
                        const admissionYear = admissionDate.getFullYear();
                        const admissionMonth = admissionDate.getMonth();
                        const dischargeYear = dischargeDate.getFullYear();
                        const dischargeMonth = dischargeDate.getMonth();

                        // Check if admission date is less than or equal to selected year and month
                        // and discharge date is greater than or equal to selected year and month
                        return (
                            admissionYear < selectedYear ||
                            (admissionYear === selectedYear && admissionMonth <= selectedMonth)
                        ) && (
                            dischargeYear > selectedYear ||
                            (dischargeYear === selectedYear && dischargeMonth >= selectedMonth)
                        );
                    });

                    const rentalOptions = _.uniq(matchingRentals.map((rental) => {
                        const admissionDate = new Date(rental.admission);
                        const admissionYear = admissionDate.getFullYear();
                        const admissionMonth = admissionDate.getMonth();
                        const date = admissionYear === selectedYear && admissionMonth === selectedMonth
                            ? admissionDate.getDate()
                            : 1; // Use the 1st day of the selected month if not within the month

                        return {
                            id: rental.id,
                            name: rental.code
                        };
                    })).sort((a, b) => a.name - b.name);

                    return rentalOptions;
                }

                const today = new Date();
                const today_str = today.toDateString();
                const lastDayOfCurrentMonth = lastDayOfTheMonthForDate(today_str);
                const firstDayDateOfCurrentMonth = firstDayOfTheMonthForDate(today_str);
                const currentMonth = today.getMonth() + 1; // Add 1 because getMonth() returns 0-based index
                const currentYear = today.getFullYear();
                let rentaloptions = {
                    select: ["id",
                        "admission",
                        "discharge",
                        "code",
                        // "invoices.rental_id"
                    ],
                    // join: [
                    //     ["RentalsInvoice AS invoices", "LEFT",
                    //         // [
                    //         ["id", "=", "invoices.rental_id"],
                    //         ["admission", "=", "invoices.start_date"]
                    //         // ]
                    //     ]
                    // ],
                    where: [
                        // ["invoices.rental_id", "IS NULL"],
                        // ["OR", [["discharge", "BETWEEN", [firstDayDateOfCurrentMonth, lastDayOfCurrentMonth]], ["discharge", "IS NULL"]]],
                    ],
                    limit: 0
                };
                if (options) {
                    if (options.cid) {
                        let contact = options.cid;
                        if (contact) {
                            rentaloptions = {
                                select: [
                                    "id",
                                    "admission",
                                    "discharge",
                                    "code",
                                    // "invoices.rental_id"
                                ],
                                // join: [
                                //     ["RentalsInvoice AS invoices", "LEFT",
                                //         // [
                                //         ["id", "=", "invoices.rental_id"],
                                //         ["admission", "=", "invoices.start_date"]
                                //         // ]
                                //     ]
                                // ],
                                where: [
                                    // ["invoices.rental_id", "IS NULL"],
                                    ["tenant_id", "=", contact],
                                    // ["OR", [["discharge", "BETWEEN", [firstDayDateOfCurrentMonth, lastDayOfCurrentMonth]], ["discharge", "IS NULL"]]],
                                ],
                                limit: 0
                            };
                        }
                    }
                }

                // console.log(rentaloptions);
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
                                    $scope.selectedYear = selectedYear;
                                    $scope.selectedMonth = selectedMonth;
                                    $scope.selectedRental = null;
                                    const rentalOptions = extractRentals(rentals, selectedYear, selectedMonth);
                                    // console.log('rentalOptions', rentalOptions);
                                    $scope.rentalOptions = rentalOptions;
                                }
                            }
                        }
                    }
                });

                $scope.$watch('selectedRental', (newValue, oldValue) => {
                    // console.log('rentalnewVal', newValue)
                    const myRentals = $scope.myRentals;
                    const myExpenses = $scope.myExpenses;
                    const selectedYear = $scope.selectedYear;
                    const selectedMonth = $scope.selectedMonth;
                    const onceoffrequences = ["once_off", "every_month", "less_than_6_m", "more_than_6_m"];
                    if (newValue) {
                        const rentaltextarea = $('af-field[name="rental_id"]').find('textarea');
                        rentaltextarea.val(newValue);
                        // console.log(myRentals);
                        const myRental = getRentalById(newValue, myRentals);
                        $scope.myRental = myRental;

                        const myRentalCode = myRental.code;
                        const admissionDate = new Date(myRental.admission);
                        const rentalStartYear = admissionDate.getFullYear();
                        const rentalStartMonth = admissionDate.getMonth();
                        const myRentalStart = getRentalStart(myRental, selectedYear, selectedMonth);
                        // console.log(myRentalStart);
                        const myRentalEnd = getRentalEnd(myRental, selectedYear, selectedMonth);
                        // console.log(myRentalEnd);
                        const lastDayDate = lastDayForDate(myRentalEnd);
                        const firstDayDate = firstDayForDate(myRentalStart);
                        const rentalLength = calculateRentalLengthInMonths(myRental, myRentalEnd);
                        // console.log(myRentalStart);
                        // _.findWhere($scope.myExpenses, {name: 'Less than 6 month'});
                        $scope.hpRentalCode = myRentalCode; // output: 'c00353a211002d220106'
                        $scope.hpRentalStart = myRentalStart; // output: 'c00353a211002d220106'
                        $scope.hpRentalEnd = myRentalEnd; // output: 'c00353a211002d220106'
                        $scope.hpRentalStartYear = rentalStartYear; // output: 'c00353a211002d220106'
                        $scope.hpRentalStartMonth = rentalStartMonth; // output: 'c00353a211002d220106'
                        $scope.hpSelectedYear = selectedYear; // output: 'c00353a211002d220106'
                        $scope.hpSelectedMonth = selectedMonth; // output: 'c00353a211002d220106'
                        $scope.hpRentalFirstDay = firstDayDate; // output: 'c00353a211002d220106'
                        $scope.hpRentalLastDay = lastDayDate; // output: 'c00353a211002d220106'
                        $scope.hpRentalLength = rentalLength; // output: 'c00353a211002d220106'
                        myExpenses.forEach((expence) => {
                            expence.checked = null;
                            expence.total = null;
                            if (onceoffrequences.includes(expence.frequency)) {
                                if(rentalStartYear  === selectedYear &&
                                    rentalStartMonth === selectedMonth &&
                                    expence.frequency === "once_off"){
                                    expence.checked = true;
                                }
                                if(expence.frequency != "once_off" &&
                                    expence.frequency != "less_than_6_m" &&
                                    expence.frequency != "more_than_6_m"
                                ){
                                    expence.checked = true;
                                }
                                if(rentalLength <= 6){
                                    if(
                                        expence.frequency === "less_than_6_m"
                                    ){
                                        expence.checked = true;
                                    }
                                }
                                if(rentalLength > 6){
                                    if(
                                        expence.frequency === "more_than_6_m"
                                    ){
                                        expence.checked = true;
                                    }
                                }
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
