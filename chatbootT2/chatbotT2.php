<?php

/**
 * Plugin Name: chatbotT2
 * Version: 1.0.0
 * Author: TEAM 2
 */
$heure = date('h:i');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./wp-content/plugins/chatbootT2/style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="wrapper" id="myChatbot">
        <div class="title"><div id="bot"><img src='./wp-content/plugins/chatbootT2/dall_icon.png'></div> <p>Customer Service</p> <div id="close" onclick="hide()"><img src='./wp-content/plugins/chatbootT2/close.png'></div> </div>
        
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon">
                    <!--<i class="fas fa-user"></i>-->
                    <img src='./wp-content/plugins/chatbootT2/dall_icon.png'>
                </div>
                 
                <div class="msg-header">
                <?php echo $heure ?><br>
                    <p>
                    WELCOME 
                    </p>
                </div>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <input id="input-txt" type="text" placeholder="Type something here.." required>
                <button id="send-btn"><img src='./wp-content/plugins/chatbootT2/send.png'></button>
            </div>
        </div>
    </div>
    
<!--chatbot bubble debut -->
    <div class="chatbot_bubble" id="chatbot_bubble">
    <div class="gif"><img   src='./wp-content/plugins/chatbootT2/gif.gif' onclick="show()"></div>
    <div class="dall_icon"><img src='./wp-content/plugins/chatbootT2/dall_icon.png' onclick="show()"></div>
   
    </div>

    <script>
        var chatbot = document.getElementById("myChatbot");
        var bubble = document.getElementById("chatbot_bubble");

    function show() {
    chatbot.style.display = "block";
    bubble.style.display="none";
    }

    function hide() {
    chatbot.style.display = "none";
    bubble.style.display="block";
    }
</script>
    <script>
        $(document).ready(function(){
            $("#send-btn").on("click", function(){
                append_and_send();
            });
        
        window.addEventListener("keydown",function(key){
            if (key.keyCode == "13") {
                append_and_send();
            }
        });
        function append_and_send(){
            $value = $("#input-txt").val();
                $msg = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ $value +'</p></div></div>';
                $(".form").append($msg);
                $("#input-txt").val('');
                
                // start ajax code
                $.ajax({
                    url: './wp-content/plugins/chatbootT2/message.php',
                    type: 'POST',
                    data: 'text='+$value,
                    success: function(result){
                        $replay = '<div class="bot-inbox inbox"><div class="icon"><img src="./wp-content/plugins/chatbootT2/dall_icon.png"></div><div class="msg-header"><p>'+ result + '</p></div></div> ';
                        $(".form").append($replay);


                // quand le chat descend la barre de défilement vient automatiquement en bas
                        $(".form").scrollTop($(".form")[0].scrollHeight);
                    }
                });
        }});
    </script>
    
</body>
</html>