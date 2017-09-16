// Bountee.service('UserServices', function(UsersFactory,$cookies,$sessionStorage){
//    this.login = function(request,callback) {
//    		var isValidated = true;
//    		// if(!username || !password){
//    		// 	isValidated = false;
//    		// }
   		
//    		// if(password.length < 8){
//    		// 	isValidated = false;	
//    		// }
   		
//       UsersFactory.login(request, function(err, data){
//       	if (err) {

//              return {'status':false,'error':err};
//             } else {
//               $sessionStorage.$default({'u_t': data.data.token});
//             	$cookies.put('u_t',data.data.token);
//               return {'status':true,'data':data};
//             }
//       });
//    }
// })