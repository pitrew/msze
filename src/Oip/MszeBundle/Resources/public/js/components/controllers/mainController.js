/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


angular.module('msze')

.controller('mainController', function ($scope, $state, modelState) {
    
    var internals = {
        
        init: function init () {
            
            modelState.init();
            
            $scope.model = modelState.getModel();
            $scope.showDetails = false;
            $scope.api = api;
        }
    };
    
    var api = {
        
        city: {
            showMore: modelState.city.getMore,
            filter: modelState.city.filter,
            open: function open (city) {
                
                $scope.model.view.city.name = city.name;
                $state.go('base.city', {id: city.id, name: city.name});
            }
        },
        church: {
            filter: modelState.church.filter
        },
        showDetails: function showDetails () {
            $scope.showDetails = !$scope.showDetails;
        }
    };
    
    internals.init();
});