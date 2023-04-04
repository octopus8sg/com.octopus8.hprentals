(function (angular, $, _) {
    // "hpSelectTenants" is a basic skeletal directive.
    // Example usage: <div hp-select-tenants="{foo: 1, bar: 2}"></div>
    angular.module('crmHprentals').directive('hpSelectTenants', function () {
        return {
            restrict: 'AE',
            require: '?ngModel',
            scope: {
                ngModel: '=',
                hpSelectTenants: '='
            },
            link: function (scope, element, attrs, ngModel) {
                let options = scope.$parent.options;
                let routeParams = scope.$parent.routeParams;
                // console.log(options);
                // console.log(routeParams);
                if (options) {
                    if (options.contact_id) {
                        let contact = options.contact_id;
                        if (contact) {
                            let selectoptions = {
                                select: ["id"],
                                where: [["id", "=", contact]],
                                limit: 0
                            };
                        }
                    }
                }
                // In cases where UI initiates update, there may be an extra
                // call to refreshUI, but it doesn't create a cycle.
                let selectoptions = {
                    select: ["id"],
                    join: [["RentalsRental AS rentals_rental", "INNER", ["id", "=", "rentals_rental.tenant_id"]]],
                    limit: 0
                };
                CRM.api4('Contact', 'get', selectoptions).then(function (result) {
                    const ids = result.map(obj => obj.id);
                    const uniqueids = [...new Set(ids)];
                    //console.log (uniqueids); // Output: [205, 206]
                    renderTenants(uniqueids);
                    // scope.tenanters = result;
                    // scope.$apply();
                });
                var renderTenants = function (myTenants) {
                    var crmentityref = {
                        // entity: 'Contacts',
                        select: {allowClear: true},
                        api: {
                            params: {
                                'id': {'IN': myTenants}
                            }
                        }
                    };

                    ngModel.$render = function () {
                        $timeout(function () {
                            // ex: msg_template_id adds new item then selects it; use $timeout to ensure that
                            // new item is added before selection is made
                            var newVal = _.cloneDeep(ngModel.$modelValue);
                            // Fix possible data-type mismatch
                            if (typeof newVal === 'string' && element.select2('container').hasClass('select2-container-multi')) {
                                newVal = newVal.length ? newVal.split(',') : [];
                            }
                            element.select2('val', newVal);
                        });
                    };

                    function refreshModel() {
                        var oldValue = ngModel.$viewValue, newValue = element.select2('val');
                        if (oldValue != newValue) {
                            scope.$parent.$apply(function () {
                                ngModel.$setViewValue(newValue);
                            });
                        }
                    }

                    function init() {
                        // TODO can we infer "entity" from model?
                        element.crmEntityRef(crmentityref || scope.crmEntityref || {});
                        element.on('change', refreshModel);
                        $timeout(ngModel.$render);
                    }

                    init();
                }
            }
            // link: function(scope, element, attrs) {
            //   scope.crmentityref = JSON.stringify({
            //     entity: 'RentalsRental',
            //     select: { allowClear: true },
            //     api: {
            //       params: {
            //         'id': { 'IN': [348,349] }
            //       }
            //     }
            //   });
            //   // scope.birbirbir = JSON.stringify({
            //   //   entity: 'RentalsRental',
            //   //   select: { allowClear: true },
            //   //   api: {
            //   //     params: {
            //   //       'id': { 'IN': [348,349] }
            //   //     }
            //   //   }
            //   // });
            //   // scope.aaaa = "aaao";
            //   // var aaaa = "oooa";
            //
            //   // Modify the crmEntityRef object when needed
            //   // scope.crmentityref.api.params.id.IN = [348,349];
            // }
        }
    })
})(angular, CRM.$, CRM._);
