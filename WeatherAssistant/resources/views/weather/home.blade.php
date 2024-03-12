<html>
<title>My cool weather assistant</title>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


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
                            <i class="fa-solid fa-person" style="height: 30px;"></i>
                            <div class="chat ml-2 p-3">I'm a helpful weather assistant, let me know which city you want
                                weather information on?</div>
                        </div>
                    </div>

                    <div class="d-flex flex-row p-3">
                        <div class="typing-indicator-box">
                            <i class="fa-solid fa-person" style="height: 30px;"></i>
                            <div class="typing-indicator">
                                <span class="dot">.</span><span class="dot">.</span><span class="dot">.</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row align-items-center form-group px-3">
                        <input type="text" class="form-control" placeholder="Type your message" />
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
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>
    
</body>

</html>