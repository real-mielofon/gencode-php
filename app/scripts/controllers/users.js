'use strict';

/**
 * @ngdoc function
 * @name genCodePhpApp.controller:UsersCtrl
 * @description
 * # UsersCtrl
 * Controller of the genCodePhpApp
 */
angular.module('genCodePhpApp')
	.controller('UsersCtrl', function ($scope, $http, $rootScope, $location) {

		$scope.user = {username: '', password: ''};
		$scope.errorMessage = '';

		$scope.login = function() {
			$http.post('/api/login', $scope.user).success(function(data) {
				console.log('login '+data.toString());
				if (data.state === 'success') {
					$rootScope.authenticated = true;
					$rootScope.currentUser = data.username;
					$location.path('/');
				}
				else {
					$scope.errorMessage = data.message;
				}
			});
		};

	});
