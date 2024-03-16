<html>
<title>My cool weather assistant</title>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <style>
        .chat .card {
            width: 500px;
            border: none;
            border-radius: 15px;
        }

        .adiv {
            border-radius: 15px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            font-size: 19px;
            height: 56px;
        }

        .chat {
            border: none;
            background: #3fa0ff;
            color: white;
            font-size: 18px;
            border-radius: 20px;
        }

        .chat-card img {
            border-radius: 20px;
        }

        .chat-card .dot {
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 5px;
            display: inline-block;
        }

        .chat-card .form-control:focus {
            box-shadow: none;
        }

        .chat-card .form-control::placeholder {
            font-size: 18px;
            color: #C4C4C4;
        }

        .chat-card .send-btn {
            background-color: white;
            color: #1089ff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-left: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 5px;
            cursor: pointer;
        }

        .chat-card .send-btn:hover {
            background-color: #e6e6e6;
        }

        .chat-card .form-control,
        .chat-card .send-btn {
            align-self: stretch;
        }

        .chat-card .send-btn svg {
            fill: #1089ff;
        }

        .chat-card .send-btn i {
            font-size: 16px;
        }

        .chat,
        img,
        .chat-card .form-control,
        .chat-card .send-btn {
            border-radius: 15px;
        }

        .chat-messages {
            overflow-y: auto;
            height: 450px;
            max-height: 450px;
        }
        .typing-indicator {
            display: inline-block;
            margin-left: 5px;
        }

        .typing-indicator span {
            display: inline-block;
            opacity: 0;
            animation: dot 1.5s infinite;
        }

        .typing-indicator span:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.25s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.5s;
        }

        @keyframes dot {
            0% {
                opacity: 0;
            }

            50% {
                opacity: 1
            }

            100% {
                opacity: 0
            }
        }
        @media(max-width: 576px) {
            .chat-card {
                width: 100%;
            }
        }

        .weather-card {
            display: flex;
            justify-content: space-between;
            align-items:center;
            width: 100%;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 8px rgba(0, 0, 0, 0, 0.1);
            margin: 10px 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .weather-card-header {
            font-size: 20px;
            font-weight: bold;
            color: #333;

        }

        .weather-card-body {
            font-size: 16px;
            color: #666;
        }

        .city-name {
            display: block;
            margin-bottom: 10px;
        }

        .temperature {
            margin: 0;
        }

        .weather-description {
            margin: 0;
        }
        .weather-content {
            flex-grow: 1;
        }
        .weather-icon {
            flex-shrink: 0;
            padding-left: 20px;
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card chat-card">
                    <div class="d-flex flex-row justify-content-between p-3 adiv text-white bg-primary">
                        <i class="fas fa-chevron-left"></i>
                        <span class="pb-3">Live chat: Weather Assistant</span>
                        <i class=""></i>
                    </div>

                    <div class="chat-messages">
                        <div class="d-flex flex-row p-3">
                            <img src="{{ asset('images/chat/icons/circled-user-female.png') }}" height="30" />
                            <div class="chat ml-2 p-3">I'm a helpful weather assistant, let me know which city you want
                                weather information on?</div>
                        </div>
                    </div>

                    <div class="d-flex flex-row p-3">
                        <div class="typing-indicator-box">
                            <img src="{{ asset('images/chat/icons/circled-user-female.png') }}" height="30" />
                            <div class="typing-indicator">
                                <span class="dot">.</span><span class="dot">.</span><span class="dot">.</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-center form-group px-3">
                        <input type="text" class="form-control message-input" placeholder="Type your message" />
                        <button class="btn send-btn"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                    <div class="pt-1"></div>
                </div>

            </div>

            <div class="col-md-6 weather-data">

            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <p class="text-muted" style="text-align: center">License: blah blah blah</p>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var save_data_string = null;

            $('.typing-indicator-box').hide();

            function applyWeatherCard(response) {
                var allWeatherData = response.function_calls.filter(call => call.function_name == "handle_weather");
                allWeatherData.forEach(weatherData => {
                    var city = weatherData.function_arguments.location;
                    var temperature = weatherData.function_arguments.celcius;
                    var weatherDescription = weatherData.function_arguments.additional_comments;
                    var iconUrl = weatherData.response.icon_url;

                    var weatherCardHtml = '<div class="weather-card">' +
                        '<div class="weather-content">' +
                        '<div class="weather-card-header">' +
                        '<span class="city-name">' + city + '</span>' +
                        '</div>' +
                        '<div class="weather-card-body">' +
                        '<p class="temperature">Temperature: ' + temperature + 'C</p>' +
                        '<p class="weather-description">' + weatherDescription + '</p>' +
                        '</div>' +
                        '</div>' +
                        '<div class="weather-icon">' +
                        '<img src="' + iconUrl + '" alt="Weather icon" />' +
                        '</div>' +
                        '</div>';
                    $('.weather-data').append(weatherCardHtml);
                });

            }

            function scrollToBottom() {
                var chatMessages = $('.chat-messages');
                chatMessages.scrollTop(chatMessages.prop("scrollHeight"));
            }

            function sendMessage() {
                var userInput = $('.message-input').val().trim();
                if (userInput === '') {
                    alert('Please type your message');
                    return;
                }

                var userMessageHtml = '<div class="d-flex flex-row p-3">' +
                    '<div class="bg-white mr-2 p-3"><span>' + userInput + '</span></div>' +
                    '<img src="{{ asset('images/chat/icons/circled-user-male.png') }}" height="30" />' +
                    '</div>';

                $('.chat-card .chat-messages').append(userMessageHtml);
                scrollToBottom();
                $('.typing-indicator-box').show();

                var requestData = {
                    message: userInput,
                    save_data_string: save_data_string
                }

                $.ajax({
                    url: "{{ route('api.assistant.send_message', 'weather') }}",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(requestData),
                    success: function(response) {
                        console.log(response);
                        var replyText = response.response;
                        var replyHtml = '<div class="d-flex flex-row p-3">' +
                            '<img src="{{ asset('images/chat/icons/circled-user-female.png') }}" height="30" />' +
                            '<div class="chat ml-2 p-3">' + replyText + '</div>' +
                            '</div>';
                        $('.chat-card .chat-messages').append(replyHtml);
                        $('.typing-indicator-box').hide();

                        applyWeatherCard(response);

                        scrollToBottom();
                        save_data_string = response.save_data_string;

                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed: " + error);
                        $('.typing-indicator-box').hide();
                    }
                });

                $('.message-input').val('');
            }

            $('.send-btn').click(function() {
                sendMessage();
            });

            $('.message-input').keypress(function(e) {
                if (e.which == 13) {
                    sendMessage();
                    e.preventDefault();
                }
            });
        });
    </script>


</body>

</html>
