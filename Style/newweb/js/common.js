
$(function(){
	$("#hd").click(function(){
        window.location.href="/index.php/Home/Hall/index";
    });
  $("#user").click(function(){
        window.location.href="/index.php/Home/Hall/user";
    });
	$("#kf").click(function(){
        $(".tc").show();
    });
    $(".tc").click(function(){
      $(".tc").hide();
   });
  
  $("#sw").click(function(){

  $(".swtc").show();
  });
  $("#tcsw").click(function(){

  $(".tcsw").show();
 });
     $(".cancel1").click(function(){
        $(".swtc").hide();
    }); 
    $(".cancel2").click(function(){
        $(".tcsw").hide();
    });     
  $("#app_download").click(function(){
      $(".app").show();
  });
  $("#close_app").click(function(){
      $(".app").hide();
  });
    $('#xiala_time_select').on('click' , function(){
        $("#popBox").show();
    });
    $('#game_list').on('click' , function(){
        $("#gameBox").show();
    });
    $('#log_typelist').on('click' , function(){
        $("#typeBox").show();
    });

  $(document).on('click' , '.blackBg' , function(){
      $("#popBox").hide();
      $("#gameBox").hide();
      $("#typeBox").hide();
  });
});
function closePopBox(){
    $("#popBox").hide();
    $("#gameBox").hide();
    $("#typeBox").hide();
}