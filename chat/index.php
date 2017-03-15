<?php include("../_checkuser.php"); ?>

<!--
TOPO Chat

User anklickbar
User für Admin sperrbar
Nachrichtenspam vermeiden

-->

<!DOCTYPE html>
<html>
    <head>
        <meta charset='UTF-8' />
        <style type="text/css">
            <!--
            .chat_wrapper {
                width: 533px; 
                margin-right: auto;
                margin-left: auto;
                background: #CCCCCC;
                border: 1px solid #999999;
                padding: 10px;
                font: 12px 'lucida grande',tahoma,verdana,arial,sans-serif;
            }
            .chat_wrapper .message_box {
                background: #FFFFFF;
                height: 245px;
                overflow: auto;
                padding: 10px;
                border: 1px solid #999999;
            }
            .chat_wrapper .panel input{
                padding: 2px 2px 2px 5px;
            }
            .panel {
                margin-top: 7px;
            }
            .system_msg{color: #BDBDBD;font-style: italic;}
            .user_name{font-weight:bold;}
            .user_message{color: #88B6E0;}
            -->
        </style>
        <script src="https://js.pusher.com/3.2/pusher.min.js"></script>
    </head>
    <body>	
        <?php
        $colours = array('007AFF', 'FF7000', 'FF7000', '15E25F', 'CFC700', 'CFC700', 'CF1100', 'CF00BE', 'F00');
        $user_colour = array_rand($colours);
        
        $chat = loadFromDB(30);
        $preload = "";
        foreach ($chat as $line) {
            $preload .= "<div><span class=\"user_name\">" . $line["user"] . "</span>: <span class=\"user_message\">" . $line["msg"] . "</span></div>\n";
        } $preload .= "<hr/>"
        ?>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

        <script language="javascript" type="text/javascript">

            function htmlEntities(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
            $(document).ready(function () {
                
                var objDiv = document.getElementById("message_box");
                

                function sendMessage() {
                    var mymessage = $('#message').val(); //get message text

                    if (mymessage === "") { //emtpy message?
                        return;
                    }

                    //prepare json data
                    var msg = {
                        type: "user",
                        message: mymessage
                    };
                    //send data to server
                    //$.post("send.php", JSON.stringify(msg));

                    $.ajax({
                        type: "POST",
                        url: "send.php",
                        data: msg,
                        success: function (data) {
                            console.log(data);
                        }
                    });
                    //$('#message_box').append("<div><span class=\"user_name\" style=\"color:darkblue\">Ich</span>: <span class=\"user_message\">" + htmlEntities(mymessage) + "</span></div>");
                    $('#message').val("");
                    objDiv.scrollTop = objDiv.scrollHeight;
                }


                //Get messages from PUSHER
                // Enable pusher logging - don't include this in production
                Pusher.logToConsole = false;

                var pusher = new Pusher('b41c8eb316335d2af468', {
                    cluster: 'eu',
                    encrypted: true
                });

                var channel = pusher.subscribe('main-chat');
                channel.bind('new-msg', function (data) {
                    var msg = JSON.parse(data); //PHP sends Json data
                    console.log(msg);
                    var type = msg.type; //message type
                    var umsg = msg.message; //message text
                    var uname = msg.sender; //user name
                    var ucolor = msg.color; //color

                    if (type === 'system')
                    {
                        $('#message_box').append("<div class=\"system_msg\">" + umsg + "</div>");
                    } else
                    {
                        $('#message_box').append("<div><span class=\"user_name\" style=\"color:#" + ucolor + "\">" + uname + "</span>: <span class=\"user_message\">" + umsg + "</span></div>");
                    }

                    $('#message').val(''); //reset text
                });

                //Send Data to server

                $($('#message')).keypress(function (e) {
                    if (e.which === 13) {
                        sendMessage();
                    }
                });

                $('#send-btn').click(function () { //use clicks message send button
                    sendMessage();

                });

            });
        </script>
        <div class="chat_wrapper">
            <div class="message_box" id="message_box">
                <?php echo $preload ?>
            </div>
            <div class="panel">
                <input type="text" name="message" id="message" placeholder="Message" maxlength="200" style="width:60%" />
                <button id="send-btn">Send</button>
            </div>
        </div>

    </body>
</html>