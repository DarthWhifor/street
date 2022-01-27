<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Street Import CSV</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <link href="https://uploads-ssl.webflow.com/5e38423084bb96caf84a40ce/5e67a79165f004d1979c52dd_component_3__2__TYb_icon.ico" rel="shortcut icon" type="image/x-icon"/>
    <link href="https://uploads-ssl.webflow.com/5e38423084bb96caf84a40ce/5e67a794592da7d6b2fb4f28_component_3__2__GYW_icon.ico" rel="apple-touch-icon"/>
</head>

<body>
    <div class="container mt-5 text-center">
        @include('flash-message')
        <h2 class="mb-4">
            Street Import CSV Example
        </h2>
        @if(count($persons) < 1)
        <form action="{{ route('file-import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                <div class="custom-file text-left">
                    <input type="file" name="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customText" style="z-index: -100 ;">
                    <input type="text" name="text" class="custom-text-input" id="customText" placeholder="  Choose file"
                           style="height: 30px;margin-top: -2vh;margin-left: -1vh;width:88%;border: rgba(128, 128, 55, 0)"></label>
                </div>
            </div>
            <button class="btn btn-info">Import data</button>
        </form>
        @endif
        <table class="table" style="margin-top: 10vh">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">First name</th>
                    <th scope="col">Initial</th>
                    <th scope="col">Last name</th>
                </tr>
            </thead>
            <tbody>
            @if(count($persons) > 0)
            @foreach($persons as $p)
            <tr>
                <th scope="row">{{ $n++ }}</th>
                <td>{{ $p->title }}</td>
                <td>{{ $p->first_name }}</td>
                <td>{{ $p->initial }}</td>
                <td>{{ $p->last_name }}</td>
            </tr>
            @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $('.custom-file-input').change(function () {
                $('.custom-text-input').val(this.files[0].name);
            });
        });
    </script>
</body>
</html>
