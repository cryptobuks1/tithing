/**
 * AngularJS Tutorial 1
 * @author Nick Kaye <nick.c.kaye@gmail.com>
 */

/**
 * Main AngularJS Web Application
 */
var app = angular.module('ssd', ['ngRoute']);

/**
 * Configure the Routes
 */
app.config(['$routeProvider', function ($routeProvider) {

        $routeProvider


                // Home
                .when("/", {templateUrl: "urls/dashboard.php", controller: "PageCtrl"})
                .when("/dashboard", {templateUrl: "urls/dashboard.php", controller: "PageCtrl"})
                // Pages
                .when("/profile", {templateUrl: "urls/profile.php", controller: "PageCtrl"})
                .when("/reporting", {templateUrl: "urls/reporting.php", controller: "PageCtrl"})
                .when("/editor", {templateUrl: "urls/editor.php", controller: "PageCtrl"})
                .when("/newproject", {templateUrl: "urls/newproject.php", controller: "PageCtrl"})
                //.when("/profile", {templateUrl: "urls/profile.php", controller: "PageCtrl"})
                .when("/config", {templateUrl: "urls/config.php", controller: "PageCtrl"})
                
                // else 404
                .otherwise("/404", {templateUrl: "urls/error404.php", controller: "PageCtrl"});
    }]);

/**
 * Controls all other Pages
 */
app.controller('PageCtrl', function (/* $scope, $location, $http */) {
    console.log("Page Controller reporting for duty.");

});


