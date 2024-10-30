<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventory</title>
</head>

<body>
    @for ($i = 0; $i<1000; $i++)
    <div style="page-break-after: always">
        <img src="{{ asset('storage/app/upload/company/'.$logo) }}" alt="">
     </div>
    @endfor
    
</body>

</html>
