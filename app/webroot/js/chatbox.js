// AngularJS code
var app = angular.module('myApp', []);

app.controller('myCtrl', function($scope) {
    // Access the variable passed from CakePHP
    $scope.message = "'<?php echo json_encode($data['message']); ?>'";
});
