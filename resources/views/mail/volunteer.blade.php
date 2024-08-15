<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Volunteer Form Submission Confirmation</h1>
    <p>Dear {{ $title }},</p>
    <p>Thank you for your commitment to making a positive impact through volunteerism. We have successfully received your volunteer form submission.</p>
    <p>Your dedication to contributing your time and skills to our cause is truly appreciated. Our team is currently reviewing your application, and we will reach out to you shortly with further details and next steps.</p>
    <p>If you have any immediate questions or concerns, feel free to contact us at Phone: 780-267-1548 and Email: baituloffice@gmail.com.</p>
    <p>Once again, thank you for choosing to be a part of our volunteer community. Together, we can make a difference.</p>
    <br>
    <p>Best regards,
        <br>Baitul Aman Islamic Society Edmonton
        <br>Phone: 780-267-1548
        <br>Email: baituloffice@gmail.com
        <br>Facebook: Baitual Aman Islamic Society
    </p>
</div>
</body>
</html>