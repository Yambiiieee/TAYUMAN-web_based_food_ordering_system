<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
    </head>
    <body>
        <div class="fluid-container" style="padding: 5% 20% 10px">
            <div
                class="card-box"
                style="
                    display: block;
                    justify-content: center;
                    border: 1px solid #f5f5f5f5;
                "
            >
                <div
                    class="img-card"
                    align="center"
                    style="width: 100%; margin-top: 30px"
                >
                    <img
                        src="https://tayuman-store.netlify.app/assets/icon-c3189f37.png"
                        alt="emailSend"
                        style="height: 120px"
                    />
                </div>
                <div
                    class="card-body-container"
                    style="width: 84%; margin: 0% 0% 0% 8%"
                >
                    <h1
                        class="card-title-name"
                        align="center"
                        style="color:#af253f"
                    >
                        Tayuman
                    </h1>
                    <br />
                    <p class="card-text-content">Hi, <b>{{$first_name}}</b></p>
                    <p>
                        Please enter the OTP verification code provided below.
                        The Code will be expired in 5 minutes. Please do not
                        share the code to anyone.
                    </p>
                    <h2>
                        Verification Code: <span style="color:#af253f">{{$token}}</span>
                    </h2>

                    <p align="center" style="margin-top: 5rem">
                        If you did not initiate this verification, please ignore this
                        email.
                    </p>
                </div>
                <div
                    class="card-footer-name"
                    align="center"
                    style="background:#af253f; color: #ffff; padding: 13px"
                >
                    <b>© 2023 Tayuman™ All Rights Reserved.</b>
                </div>
            </div>
        </div>
    </body>
</html>
