/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


angular.module('msze')
.factory('mapFactory', function () {
    
    var map;
    return {
        getMap: function () {
            return map;
        },
        setMap: function (_map_) {
            map = _map_;
        }
    };
})
.controller('mapController', function ($scope, mapFactory, modelState) {
    
    var internals = {
        
        mapId: 'full-map',
        mapLocal: undefined,
        
        mapInit: function mapInit () {
            
//            if (mapFactory.getMap()) {
//                return;
//            }
            try {
                var mapOptions = {
                  center: new google.maps.LatLng(52.245461,21.007233),
                  zoom: 8,
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                internals.mapLocal = new google.maps.Map(
                    document.getElementById(internals.mapId), mapOptions);
                    
//                mapFactory.setMap(internals.mapLocal)
                //google.maps.event.trigger(internals.mapLocal,'resize')

            } catch(e) {
            
                $scope.error = {
                    msg: 'Blad inicializacji mapy',
                    details: e.message
                };
            }
        },
        
        init: function init () {
            
            $scope.model = modelState.getModel();
            $scope.api = api;
            internals.mapInit();
        }
    };
    
    var api = {
    };
    
    internals.init();
});