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
        <form action="/room">
            <div>
                <input type="text" name="name" placeholder="Имя">
            </div>
        <div class="col-3">
            <label>Комната 1</label>
            <input type="radio" value="1" name="id">
        </div>
        <div class="col-3">
            <label>Комната 2</label>
            <input type="radio" value="2" name="id">
        </div>
        <div class="col-3">
            <label>Комната 3</label>
            <input type="radio" value="3" name="id">
        </div>
            <div class="col-3">
                <button type="submit">Поехали</button>
            </div>
        </form>
    </div>

</body>
</html>
