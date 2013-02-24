/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
angular.module('msze', ['ui.router', 'ngMaterial', 'ngMdIcons'])

    .config(['$interpolateProvider', function($interpolateProvider){
        $interpolateProvider.startSymbol('[[').endSymbol(']]');
    }])

    .config(['$stateProvider', '$urlRouterProvider', '$httpProvider',
        function($stateProvider, $urlRouterProvider, $httpProvider) {
            $httpProvider.interceptors.push(function ($q) {
                return {
                    request: function (request) {
                        if (request.url.indexOf('partials') !== -1) {
                            request.url = "bundles/oipmsze/" + request.url;
                        }
                        return request || $q.when(request);
                    }
                }
            });
            
            $urlRouterProvider.otherwise("/msze");
      
            $stateProvider
            .state('base', {
                abstract: true,
                templateUrl: 'partials/start.html',
                controller: 'mainController'
            })
            .state('base.msze', {
                url: "/msze",
                views: {
                    'toolbar': {
                        templateUrl: 'partials/toolbar.html'
                    },
                    'navigation': {
                        templateUrl: 'partials/navigation.html'
                    },
                    'content': {
                        templateUrl: 'partials/test.html'
                    }
                }
            })
            .state('base.city', {
                url: "/msze/:cid/:city",
                views: {
                    'toolbar': {
                        templateUrl: 'partials/toolbar.html'
                    },
                    'navigation': {
                        templateUrl: 'partials/city.html'
                    },
                    'content': {
                        templateUrl: 'partials/test.html'
                    }
                }
            });
        }])
    
    .directive('scrolly', function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var raw = element[0];

                element.bind('scroll', function () {

                    if (raw.scrollTop + raw.offsetHeight + 1 >= raw.scrollHeight) {
                    
                        scope.$apply(attrs.scrolly);
                        raw.scrollTop -= 1;
                    }
                });
            }
        };
    });