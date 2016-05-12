<?php
/**
 * User: boshurik
 * Date: 06.05.16
 * Time: 13:01
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WebSocket</title>
</head>
<body>
    <div id="content"></div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="http://autobahn.s3.amazonaws.com/js/autobahn.min.jgz"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var $content = $('#content');

            var connection = new ab.connect('ws://127.0.0.1:8888/ws',
                function (session) {
                    console.warn('WebSocket connection established', session);

                    session.subscribe('category', function(topic, data) {
                        console.log('Received data: ', data);

                        $content.append('<h1>' + data +'</h1>');
                    });
                },
                function (code, reason, detail) {
                    console.warn('WebSocket connection closed', code, reason, detail);
                },
                {
                    skipSubprotocolCheck: true
                }
            );
        });
    </script>
</body>
</html>