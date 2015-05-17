'use strict';

/**
 * @ngdoc function
 * @name genCodePhpApp.controller:CodesCtrl
 * @description
 * # CodesCtrl
 * Controller of the genCodePhpApp
 */
angular.module('genCodePhpApp')
  .controller('CodesCtrl', function ($scope, $http) {

    function createUnknownError(status) {
      return {
        status: status,
        statusText: 'Internal Server Error',
        description: 'No details available'
      };
    }

    function getAvailibleKeys () {
      $http({method: 'GET', url: '/api/available'}).
        success(function (data) {
          $scope.loading = true;
          $scope.availabledKeys = data;
        }).
        error(function (data, status) {
          $scope.loading = false;
          $scope.error = data && data.description ? data : createUnknownError(status);
        });
    }

    function getSendedKeys () {
      $http({method: 'GET', url: '/api/sended'}).
        success(function (data) {
          $scope.sendedKeys = data;
        }).
        error(function (data, status) {
          $scope.error = data && data.description ? data : createUnknownError(status);
        });
    }

    function getActivatedKeys () {
      $http({method: 'GET', url: '/api/activated'}).
        success(function (data) {
          $scope.activatedKeys = data;
        }).
        error(function (data, status) {
          $scope.error = data && data.description ? data : createUnknownError(status);
        });
    }

    function generateKeys() {
      $http({method: 'GET', url: '/api/generatekeys'}).
        success(function (data) {
          getAvailibleKeys();
        }).
        error(function (data, status) {
          $scope.error = data && data.description ? data : createUnknownError(status);
        });
    }

    $scope.$on('eventRefresh', function(){
      getAvailibleKeys();
      getSendedKeys();
      getSendedKeys();
    });

    $scope.availabledKeys = [];
    $scope.sendedKeys = [];
    $scope.activatedKeys = [];
    $scope.email = '';

    $scope.loading = true;

    getAvailibleKeys();
    getSendedKeys();
    getActivatedKeys();

  });
