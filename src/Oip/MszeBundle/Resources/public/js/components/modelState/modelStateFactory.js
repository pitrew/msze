/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

angular.module('msze')

.factory('modelState', function(backendClient, diacritics, $filter) {
    
    var internals = {
        
        model: {
            ctrl: {
                cities: {
                    step: 30,
                    shown: 0,
                    listFiltered: [],
                    list: []
                }
            },
            view: {
                cities: [],
                city: {
                    name: ''
                },
                churches: []
            }
        },
        
        city: {
            getMore: function getMore () {
                var ctrl = internals.model.ctrl.cities;
                
                ctrl.shown = ctrl.shown + ctrl.step;
                if (ctrl.shown > ctrl.listFiltered.length) {
                    ctrl.shown = ctrl.listFiltered.length;
                }
                internals.model.view.cities =
                    internals.model.view.cities.concat(
                        ctrl.listFiltered.slice(internals.model.view.cities.length,
                            ctrl.shown)
                        );
            },
            filter: function filter (text) {
                
                var ctrl = internals.model.ctrl.cities;
                
                if (!text) {
                    ctrl.listFiltered = angular.copy(ctrl.list);
                } else {
                    ctrl.listFiltered = ctrl.list.filter(
                            function (value) {

                                return diacritics.remove(value.name).toUpperCase()
                                    .indexOf(diacritics.remove(text).toUpperCase()) !== -1;
                            });
                }
                internals.model.view.cities = [];
                ctrl.shown = 0;
                internals.city.getMore();
            }
        },
        church: {
            filter: function filter (text) {
                
            }
        }
    };
    
    var api = {
        
        
        init: function init () {
          
            backendClient.getCities().then(function success(res) {
                
                internals.model.ctrl.cities.list = res.data;
                internals.model.ctrl.cities.listFiltered = angular.copy(res.data);
                internals.city.getMore();
            }, function error(err) {
                
                console.error(err);
            });
        },
        
        getModel: function getModel() {
            
            return internals.model;
        },
        
        city: {
            getMore: internals.city.getMore,
            filter: internals.city.filter
        },
        
        church: {
            filter: internals.church.filter
        }
    };
    
    return api;
});