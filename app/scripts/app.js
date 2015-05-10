'use strict';

/**
 * @ngdoc overview
 * @name genCodePhpApp
 * @description
 * # genCodePhpApp
 *
 * Main module of the application.
 */
angular
  .module('genCodePhpApp', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch'
  ])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .when('/about', {
        templateUrl: 'views/about.html',
        controller: 'AboutCtrl'
      })
      .when('/available', {
        templateUrl: 'views/available.html',
        controller: 'CodesCtrl'
      })
      .when('/sended', {
        templateUrl: 'views/sended.html',
        controller: 'CodesCtrl'
      })
      .when('/activated', {
        templateUrl: 'views/activated.html',
        controller: 'CodesCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
