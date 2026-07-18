<?php
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$host = '127.0.0.1';
$port = 8080;

if (!socket_bind($socket, $host, $port)) { /* ... error handling ... */ }
if (!socket_listen($socket, 5)) { /* ... error handling ... */ }

while (true) {
    $client = socket_accept($socket);
    if ($client === false) { /* ... error handling ... */ }

    $message = "Welcome!\n";
    socket_write($client, $message, strlen($message));

    while ($buffer = socket_read($client, 1024)) {
        echo "Received: " . $buffer;
        socket_write($client, "You sent: " . $buffer, strlen($buffer));
    }

    socket_close($client);
}

socket_close($socket);
?>