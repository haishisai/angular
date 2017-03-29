var myApp = angular.module('myApp', ['ui.router','ngFlatDatepicker','ui.bootstrap']);
//由于整个应用都会和路由打交道，所以这里把$state和$stateParams这两个对象放到$rootScope上，方便其它地方引用和注入。
myApp.run(function($rootScope, $state, $stateParams) {
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
});
//为post而设
myApp.config(function($httpProvider){
    $httpProvider.defaults.transformRequest=function(obj){
        var str=[];
        for(var p in obj){
            str.push(encodeURIComponent(p)+"="+encodeURIComponent(obj[p]));
        }
        return str.join("&");
    };
    $httpProvider.defaults.headers.post={'Content-Type':'application/x-www-form-urlencoded'}      
})


// 路由
myApp.config(function ($stateProvider,$urlRouterProvider) {
     $urlRouterProvider.when("", "/login");    //没有 转到这个页面
     $stateProvider
        .state("login", {
            url: "/login",
            templateUrl: 'login.html'
        })
        .state("show", {
            url:"/show",
            templateUrl: 'show.html'
        })
        .state("new_user", {
            url:"/new_user",
            templateUrl: 'new_user.html'
        })
        .state("show.Page1", {
            url:"/Page1",
            views:{
                'main@show':{
                    templateUrl: 'uploads_pic1.html'
                },
                'foot@show':{
                    template: 'uploads_pic1.html'
                }
            }
        })
        .state("show.Page2", {
            url:"/Page2",  
            views:{
                'main@show':{
                    templateUrl: 'part2.html'
                },
                'foot@show':{
                    template: 'part2.html'
                }
            }
        })
        .state("show.Page3", {
            url:"/Page3",
            views:{
                'main@show':{
                    templateUrl: 'plugs.html'
                },
                'foot@show':{
                    template: 'plugs.html'
                }
            }
        })
});





