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
                        style="color: #af253f"
                    >
                        Tayuman
                    </h1>
                    <br />
                    <p class="card-text-content">Hi, <b>{{$first_name}}</b></p>

                    <h2>Order Receipt</h2>
                    <p>Thank you for your order! Here are the details:</p>

                    <table style="border-collapse: collapse; width: 100%">
                        <thead>
                            <tr>
                                <th
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    Product
                                </th>
                                 <th
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    Size
                                </th>
                                <th
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    Quantity
                                </th>
                                <th
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    Price
                                </th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    {{ $item->name }}
                                </td>
                                  <td
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    {{ $item->size}}
                                </td>
                                <td
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    {{ $item->order_quantity}}
                                </td>
                                <td
                                    style="
                                        border: 1px solid black;
                                        padding: 8px;
                                    "
                                >
                                    {{ $item->price }}
                                </td>
                                
                            </tr>

                            @endforeach

                            <!-- Add more rows for additional products -->
                        </tbody>
                    </table>

                    <p>Total: <b>{{$total}}</b></p>

                    <p>
                        Please let us know if you have any questions or concerns
                        regarding your order. Thank you for shopping with us!
                    </p>

                    <p>
                        Best regards,<br />
                        <b> Tayuman online store</b>
                    </p>
                </div>
                <div
                    class="card-footer-name"
                    align="center"
                    style="background: #af253f; color: #ffff; padding: 13px"
                >
                    <b>© 2023 Tayuman™ All Rights Reserved.</b>
                </div>
            </div>
        </div>
    </body>
</html>
