(function(angular, $, _) {
  // "hmProrateCounter" is a basic skeletal directive.
  // Example usage: <div hm-prorate-counter="{foo: 1, bar: 2}"></div>
  angular.module('crmHprentals').directive('hmProrateCounter', function() {
    return {
      restrict: 'AE',
      templateUrl: '~/crmHprentals/hmProrateCounter.html',
      scope: {
        hmProrateCounter: '='
      },
      link: function($scope, $el, $attr) {
        var ts = $scope.ts = CRM.ts('com.octopus8.hprentals');
        $scope.$watch('hmProrateCounter', function(newValue){
          $scope.myOptions = newValue;
        });
      }
    };
  });
})(angular, CRM.$, CRM._);
/*
angular.module('myApp').directive('prorateCounter', function() {
  return {
    restrict: 'E',
    scope: {
      admission: '=', // The admission date, passed as a two-way binding
      monthlyFees: '=', // The monthly fees, passed as a two-way binding
      outputField: '=' // The output field, passed as a two-way binding
    },
    template: '<div>' +
                '<label>Admission date:</label>' +
                '<input type="date" ng-model="admission">' +
                '<label>Prorated fee:</label>' +
                '<input type="text" ng-model="proratedFee" ng-change="updateOutputField()">' +
              '</div>',
    controller: function($scope) {
      $scope.updateOutputField = function() {
        if ($scope.outputField) {
          $scope.outputField.value = $scope.proratedFee;
        }
      };

      $scope.$watch('admission', function() {
        if ($scope.admission && $scope.monthlyFees) {
          var date = new Date($scope.admission); // Convert the admission date to a Date object

          // Calculate the number of days left until the end of the month
          var daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
          var daysLeft = daysInMonth - date.getDate() + 1;

          // Calculate the prorated monthly fee for the remaining days
          var dailyFee = $scope.monthlyFees / daysInMonth;
          var proratedFee = dailyFee * daysLeft;

          // Set the scope variable for the prorated fee
          $scope.proratedFee = proratedFee.toFixed(2); // Round to two decimal places

          // Update the output field if it exists
          $scope.updateOutputField();
        }
      });
    }
  };
});
 */
