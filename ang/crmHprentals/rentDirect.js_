(function (angular, $, _) {
    // "rentDirect" is a basic skeletal directive.
    // Example usage: <div rent-direct="{foo: 1, bar: 2}"></div>
    let controllerName = 'Controller';
    let directiveName = 'rentDirect';
    // let moduleName = 'crmHprentals';
    let moduleName = 'crmHprentals';
    let templateUrl = '~/crmHprentals/rentDirect.html';

    angular.module(moduleName)
        .controller(controllerName, ['$scope', function($scope) {
            $scope.name = 'Tobias';
        }])
        .directive(directiveName, function() {
            return {
                restrict: 'E',
                transclude: true,
                scope: {},
                templateUrl: templateUrl
            };
        });

})(angular, CRM.$, CRM._);
