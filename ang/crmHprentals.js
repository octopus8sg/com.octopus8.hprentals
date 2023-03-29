(function (angular, $, _) {
    // Declare a list of dependencies.
    angular.module('crmHprentals', CRM.angRequires('crmHprentals'));
    angular.module('crmHprentals').controller('RentalExpense', function ($scope, crmApi) {
        $scope.deleteRecord = function () {
            var entityId = $scope.entity.id;
            console.log(entityId);
            crmApi('RentalsExpense', 'delete', {
                where: [["id", "=", entityId]]
            }).then(function (result) {
                // Do something after the record is deleted
            }, function (error) {
                // Handle any errors that occur
            });
        };
    })
})(angular, CRM.$, CRM._);
