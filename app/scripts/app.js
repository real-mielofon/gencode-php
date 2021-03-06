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
	.run(function($rootScope, $http) {
		$rootScope.authenticated = false;
		$rootScope.currentUser = '';

		$rootScope.logout = function(){
			$http.get('/api/logout');
			$rootScope.authenticated = false;
			$rootScope.currentUser = '';
		};
	})
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
			.when('/login', {
				templateUrl: 'views/login.html',
				controller: 'UsersCtrl'
			})
			.otherwise({
				redirectTo: '/'
			});
	});
