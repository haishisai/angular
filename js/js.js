var app=angular.module('myApp');

//登陆控制器
app.controller('loginCtrl', ['$scope','$state','$http',function ($scope,$state,$http) {
    //$scope.user_id="pd";
    $scope.login = function(){
    	var post_data = {user_id:$scope.user_id,user_pwd:$scope.user_pwd};
    	if($scope.loginForm.$valid){
            $http.post('login.php', post_data).success(function(data) {               
                if(data=='go'){
                    $state.go('show.Page1');  //跳转到main                    
                }else{
                    alert('用户名或密码不正确'); //%
                }
            }).error(function(data) {  
                alert("系统错误，请联系管理员1。")   // %
            });  
        }else{
            alert("表单验证失败") // %
        }    	
    };
    //跳转注册页面
    $scope.link_new_user = function(){
    	$state.go('new_user')
    }
    //日期配置
    $scope.datepickerConfig = {
        allowFuture: false,
        dateFormat: 'YYYY-MM-DD'
    };
}])

//注册控制器
app.controller('newuserCtrl', ['$scope','$state','$http',function ($scope,$state,$http) {
    // 为空 就不能提交
    $scope.form_check = "1"  //初始状态
    $scope.new_user_id = $scope.user_pwd = ""
    $scope.change = function(){
        console.log($scope.newuserForm.$invalid)
    }
    $scope.new_user = function(){
    	var post_data = {new_user_id:$scope.new_user_id,user_pwd:$scope.user_pwd,user_mail:$scope.user_mail,user_phone:$scope.user_phone};
    	if($scope.newuserForm.$valid){
    		//POST 传值
            $http.post('login.php', post_data).success(function(data) {
                if(data=='ok'){
                    alert('注册成功');  
                }else{
                    alert('注册失败'); //%
                }
            }).error(function(data) {
                alert("系统错误，请联系管理员。")   // %
            });
            // end POST 传值
        }else{
            alert("表单验证失败") // %
        }    
    }  
}])

//登录 展示控制器
app.controller('showCtrl', ['$scope','$state','$http',function ($scope,$state,$http) {
    $scope.user_id="test";
    var post_data = {login_show:1};
    $http.post('login.php', post_data).success(function(data) {	        
	        if(data){
	            $scope.user_id = data  ;  //跳转到main
                //console.log(data);
	        }else{
	           	$state.go('login'); //%
	        }
	    }).error(function(data) {  
	        alert("系统错误，请联系管理员show。")   // %
    });
    
    //退出
    $scope.logout = function(){
		var post_data = {logout:1};
	    $http.post('login.php', post_data).success(function(data){        
		        if(data){
		           $state.go('login');
		        }
		    }).error(function(data){
		        alert("系统错误，请联系管理员 out");
	    });		
   	}  
}])


//图片展示
app.controller('pic_show',['$scope','$state','$http',function ($scope,$state,$http){
    var post_data = {"pic_show":"1"};
    $http.post('file1.php', post_data).success(function(data){
           //console.log(typeof data);
           if(data==""){
                $scope.pic_0 ="没有上传过图片"
           }else{
                // var data = decodeURIComponent(data);
                console.log(typeof data);
                console.log(data);
                $scope.pic_data = data;

           }           
        }).error(function(data){
            alert("系统错误，请联系管理员 pic_show");
    });         
}])


//图片上传
app.controller('uploads',['$scope','$http', function ($scope,$http) {
    //test
    $scope.input_show_txt = true;
    $scope.up_show_txt = "已选0";
    // 自制预览按钮绑定
    $scope.up_bind = function(){
        document.querySelector('#upload_input').click();
    }
    
    $scope.up_number=function(){

        var len = document.querySelector('input[type=file]').files.length;

        $scope.up_show_txt = "已选"+len;
        console.log($scope.up_show_txt)
        // repeat click
        document.querySelector('#r_click').click();
    }
    //上传
    $scope.save= function(){
        var len = document.querySelector('input[type=file]').files.length;
        //循环给php上传
        for(i=0;i<len;i++){
            var file = document.querySelector('input[type=file]').files[i];
            //新建form数据
            var fd = new FormData();
            fd.append('file', file);
            $http({
                  method:'POST',
                  url:"uploads_pic1.php",
                  data: fd,
                  headers: {'Content-Type':undefined},
                  transformRequest: angular.identity 
            }).success(function (data){
                   //上传成功的操作
                   console.log(data);
                   $scope.up_show_txt = "上传成功";
            }); 
        }
    }  
}]);



//弹窗组件
app.controller('ModalDemoCtrl', function ($uibModal, $log, $document) {
  var $ctrl = this;
  $ctrl.items = ['item1', 'item2', 'item3'];

  $ctrl.animationsEnabled = true;

  $ctrl.open = function (size, parentSelector) {
    var parentElem = parentSelector ? 
      angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
    var modalInstance = $uibModal.open({
      animation: $ctrl.animationsEnabled,
      ariaLabelledBy: 'modal-title',
      ariaDescribedBy: 'modal-body',
      templateUrl: 'myModalContent.html',
      controller: 'ModalInstanceCtrl',
      controllerAs: '$ctrl',
      size: size,
      appendTo: parentElem,
      resolve: {
        items: function () {
          return $ctrl.items;
        }
      }
    });

    modalInstance.result.then(function (selectedItem) {
      $ctrl.selected = selectedItem;
    }, function () {
      $log.info('Modal dismissed at: ' + new Date());
    });
  };

  $ctrl.openComponentModal = function () {
    var modalInstance = $uibModal.open({
      animation: $ctrl.animationsEnabled,
      component: 'modalComponent',
      resolve: {
        items: function () {
          return $ctrl.items;
        }
      }
    });

    modalInstance.result.then(function (selectedItem) {
      $ctrl.selected = selectedItem;
    }, function () {
      $log.info('modal-component dismissed at: ' + new Date());
    });
  };

  $ctrl.openMultipleModals = function () {
    $uibModal.open({
      animation: $ctrl.animationsEnabled,
      ariaLabelledBy: 'modal-title-bottom',
      ariaDescribedBy: 'modal-body-bottom',
      templateUrl: 'stackedModal.html',
      size: 'sm',
      controller: function($scope) {
        $scope.name = 'bottom';  
      }
    });

    $uibModal.open({
      animation: $ctrl.animationsEnabled,
      ariaLabelledBy: 'modal-title-top',
      ariaDescribedBy: 'modal-body-top',
      templateUrl: 'stackedModal.html',
      size: 'sm',
      controller: function($scope) {
        $scope.name = 'top';  
      }
    });
  };

  $ctrl.toggleAnimation = function () {
    $ctrl.animationsEnabled = !$ctrl.animationsEnabled;
  };
});

// Please note that $uibModalInstance represents a modal window (instance) dependency.
// It is not the same as the $uibModal service used above.

app.controller('ModalInstanceCtrl', function ($uibModalInstance, items) {
  var $ctrl = this;
  $ctrl.items = items;
  $ctrl.selected = {
    item: $ctrl.items[0]
  };

  $ctrl.ok = function () {
    $uibModalInstance.close($ctrl.selected.item);
  };

  $ctrl.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
});

// Please note that the close and dismiss bindings are from $uibModalInstance.
app.component('modalComponent', {
  templateUrl: 'myModalContent.html',
  bindings: {
    resolve: '<',
    close: '&',
    dismiss: '&'
  },
  controller: function () {
    var $ctrl = this;
    $ctrl.$onInit = function () {
      $ctrl.items = $ctrl.resolve.items;
      $ctrl.selected = {
        item: $ctrl.items[0]
      };
    };
    $ctrl.ok = function () {
      $ctrl.close({$value: $ctrl.selected.item});
    };
    $ctrl.cancel = function () {
      $ctrl.dismiss({$value: 'cancel'});
    };
  }
});   