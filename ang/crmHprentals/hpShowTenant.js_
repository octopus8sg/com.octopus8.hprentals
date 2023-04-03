(function(angular, $, _) {
  // "hpShowTenant" is a basic skeletal directive.
  // Example usage: <div hp-show-tenant="{foo: 1, bar: 2}"></div>
  angular.module('crmHprentals').directive('hpShowTenant', function() {
    return {
      restrict: 'AE',
      templateUrl: '~/crmHprentals/hpShowTenant.html',
      scope: {
        hpShowTenant: '='
      },
      link: function($scope, $el, $attr) {
        var ts = $scope.ts = CRM.ts('com.octopus8.hprentals');
        $scope.$watch('hpShowTenant', function(newValue){


          console.log($scope.routeParams);
        });
      }
    };
  });
})(angular, CRM.$, CRM._);
