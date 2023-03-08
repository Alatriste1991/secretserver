<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Secret Server</title>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    </head>
    <body>
        <div style="display:flex;height: 100%; vertical-align:middle;">
            <div class="col-4">
                <form >
                    <div class="form-group">
                        <label for="secret">Secret</label>
                        <input type="input" class="form-control" id="secret" placeholder="Secret">
                    </div>
                    <div class="form-group">
                        <label for="expireAfterViews">expireAfterViews</label>
                        <input type="input" class="form-control" id="expireAfterViews" aria-describedby="expireAfterViews" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="expireAfter">expireAfter</label>
                        <input type="input" class="form-control" id="expireAfter" placeholder="expireAfter ">
                    </div>
                    <button id="send" type="button" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <script>
            $('#send').on('click',function(){
                $.ajax({  
                type: 'POST', 
                //contentType: "application/json", 
                url: '/secret',
                data: {
                    'secret': $('#secret').val(),
                    'expireAfterViews': $('#expireAfterViews').val(),
                    'expireAfter': $('#expireAfter').val(),
                },
                dataType: 'json',
                success: function(data) {
                
                },
                error: function(error){
                    //console.log(error);
                }         
                });
            })
        </script>
    </body>
</html>