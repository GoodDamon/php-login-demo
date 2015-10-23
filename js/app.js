var myApp = {

	secure : function() {
		console.log("In secure");
		$.ajax({
			type : 'GET',
			url : 'php/app.php',
			dataType :'json',
			success : function(data) {
				$('#notice').append(data['message']);
				$('#login-page').hide();
				$('#logged-in-page').show();
				$('#logout-button').show();
				$('#login-status').empty().append("Logged in as <strong>" + data['user_name'] + "</strong> at <strong>" + data['last_login']);
			},
			error : function(data) {
				console.log(data);
			}
		});
	},

	login: function() {
		console.log("In login");
		var user_name = $('#input-user-name').val();
		var user_pass = $('#input-user-pass').val();
		$.ajax({
			type : 'POST',
			url : 'php/login.php',
			data : {
				"user_name" : user_name,
				"user_pass" : user_pass
			},
			dataType : 'json',
			success : function(data) {
				if(data['message'] === 'SESSION CREATED') {
					// Authentication succeeded. Get data from the file app.php.
					myApp.secure();
				} else {
					// Authentication failed. Display error.
					alert(data['message']);
				}
			},
			error : function() {
				alert("Unknown error logging in");
			}
		});
	},

	logout: function() {
		console.log("In logout");
		$.ajax({
			type : 'GET',
			url : 'php/logout.php',
			dataType : 'json',
			success : function(data) {
				if(data['message'] === 'LOGGED OUT') {
					// Authentication succeeded. Get data from the file app.php.
					myApp.init();
				} else {
					// Authentication failed. Display error.
					alert(data['message']);
				}
			},
			error : function() {
				alert("Unknown error logging out");
			}
		});
	},

	init: function() {
		console.log("In init");
		$.ajax({
			type : 'GET',
			url : 'php/login.php?mode=status',
			dataType : 'json',
			success : function(data) {
				if(data['message'] === "SESSION ACTIVE") {
					myApp.secure();
				} else {
					// Session is not active, no reason to try to load secure().
					$('#login-page').show();
					$('#logged-in-page').hide();
					$('#logout-button').hide();
					$('#login-status').empty().append("Not logged in");
				}
			}
		});
	}
}

$('#login-button').click(function() {
	myApp.login();
});

$('#logout-button').click(function() {
	myApp.logout();
});

$(document).ready(function() {
	myApp.init();
})