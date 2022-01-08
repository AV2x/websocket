<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div>
        @if($id == 1)
            <h3>Комната 1</h3>
        @elseif($id == 2)
            <h3>Комната 2</h3>
        @else
            <h3>Комната 3</h3>
        @endif
    </div>
    <div class="row">
        <div class="col-3">
            Пользователи онлайн
            <ul id="users">

            </ul>
        </div>
        <div class="col-5">
            Сообщения
            <div id="messages">

            </div>
        </div>
        <div class="col-3">
            <input type="text" id="text">
            <button id="send" onclick="send()">Отправить</button>
        </div>
    </div>
</div>



<script>
    var socket = new WebSocket("wss://websocket.dmitry-povyshev.ru/wss");
    socket.onopen = function() {
        socket.send('{"message": "new room", "value": "{{$room_name}}", "user": "{{$name}}"}');
        console.log('Соединение установлено')
    };

    socket.onclose = function(event) {
        // if (event.wasClean) {
        //     alert('Соединение закрыто чисто');
        // } else {
        //     alert('Обрыв соединения'); // например, "убит" процесс сервера
        // }
        // alert('Код: ' + event.code + ' причина: ' + event.reason);
    };

    socket.onmessage = function(event) {
         var json = JSON.parse(event.data);
         console.log(json);

         if(json.message == 'connect'){
             const deleteElement = document.querySelector("#users");
             deleteElement.innerHTML = '';
             json.users.map(function(item) {
                 var users = document.getElementById('users');
                 let liFirst = document.createElement('li');
                 liFirst.innerHTML = "<li><span>"+item+"</span></li>";
                 users.prepend(liFirst);
             });
         }
         else if(json.message == 'message'){
                 var messages = document.getElementById('messages');
                 let liFirst = document.createElement('p');
                 liFirst.innerHTML = "<b>"+json.user+"</b>: "+json.value;
                    messages.prepend(liFirst);
        }
        // console.log(json);
        // var orders = document.getElementById('orders');
        // var order = '' +
        //     '<div class="order">' +
        //     '<p>'+json.name+'</p>' +
        //     '<p>'+json.product+'</p>' +
        //     '</div>' +
        //     '';
        // orders.insertAdjacentHTML('beforeend', order);

        //alert("Получены данные " + event.data);
    };

    socket.onerror = function(error) {
        alert("Ошибка " + error.message);
    };
    function send() {
        var text = document.getElementById('text').value;
        fetch('https://websocket.dmitry-povyshev.ru/send_message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify({text: text})
        });
        socket.send('{"message": "new message", "value": "'+text+'"}');
    }
</script>

<style>
    li {
        color: green;
    }
    li span {
        color: black;
    }
</style>

</body>
</html>
