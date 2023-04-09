(function(angular, $, _) {
  // "hpSelectYearsAndMonths" is a basic skeletal directive.
  // Example usage: <div hp-select-years-and-months="{foo: 1, bar: 2}"></div>
  angular.module('crmHprentals').directive('hpSelectYearsAndMonths', function() {
    return {
      restrict: 'AE',
      templateUrl: '~/crmHprentals/hpSelectYearsAndMonths.html',
      scope: {
        hpSelectYearsAndMonths: '='
      },
      link: function($scope, $el, $attr) {
        var ts = $scope.ts = CRM.ts('com.octopus8.hprentals');
        $scope.$watch('hpSelectYearsAndMonths', function(newValue){
          $scope.myOptions = newValue;
        });
      }
    };
  });
})(angular, CRM.$, CRM._);
