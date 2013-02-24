/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


angular.module('msze')

.factory('backendClient', function($http) {
    
    var api = {
        
        test: function test() {
            alert('test');
        },
        
        getCities: function getCities (pattern) {
            
            return $http.get(Routing.generate(('show_cities'), { _format: 'json' }),
                { s: pattern, small: true });
       },

       getChurches: function getChurches () {
           
            return $http.get(Routing.generate(('fast_churches'), {}), { });
       },

       getCity: function getCity (id) {
           
           return $http.get(Routing.generate(('show_city'), 
            { _format: 'json', id: id }), { });
       },

       getChurchesInDistrict: function getChurchesInDistrict (id) {
           
           return $http.get(Routing.generate(('fast_district'), { id: id }),
                { });
       },
       
       getChurchInCityAndMass: function getChurchInCityAndMass (cid, day, hour) {
           
           return $http.get(Routing.generate(('fast_church_in_city_and_mass'), 
                { city_id: cid, day: day, hour: hour }),
                { });
       }

    };
    
    return api;
});