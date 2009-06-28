
function registration_confirm_password(password,confirm_password)
{
	var ErrorLiId = "registration-confirm-password-message";
	$("#flash-messages").hide();
	var passwordValue = $("#password").val();
	var confirm_passwordValue = $("#confirm_password").val();
	
	//alert(passwordValue);
	//alert(confirm_passwordValue);
	
	if(passwordValue != confirm_passwordValue)
	{
		
		if( $("#"+ErrorLiId).length > 0 )
		{
			$("#"+ErrorLiId).text("Confirm password does not match");
		}else
		{
			$("#flash-messages-ul").append("<li id='"+ErrorLiId+"'>Confirm password does not match.</li>");
		}
		$("#flash-messages").css("position","fixed");
		$("#flash-messages").fadeIn(1000);

	}
}