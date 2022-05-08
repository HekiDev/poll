<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
            .container{
                display: flex;
                /* background: red; */
                justify-content: center;
            }
            .container .poll-survey{
                max-width: 400px;
                min-width: 400px;
                background-color: white;
            }
            .container .poll-survey .questions{
                background: rgb(133, 179, 135);
                color: #ffffff;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="poll-survey">
                <div class="questions" id = "questions">
                    
                </div>
                <div class="choices" id = "choices">
                    
                </div>
                <div class="actions">

                </div>
            </div>
        </div>
        <script>
        $(document).ready(function(){

            //Default value of Question id to call
            var NextQuestion = 1;

            //Call the Questions function 
            getQuestions(NextQuestion);

            //Get the choices
            $.ajax({
                type: "get",
                url: "/choices/",
                // data: data,
                dataType: "json",
                success: function (response){
                    console.log(response);
                    var choices = "";
                    
                    //displays the choices dynamically
                    $.each(response.choices, function(key, choice){
                        choices += `<div class="form-check">
                                        <input class="form-check-input clicked_choice" type="radio" name="choice" choice_id = "${choice.id}" id="radiobutton_${choice.id}">
                                        <label class="form-check-label" for="radiobutton_${choice.id}">
                                        `+choice.choice+`
                                        </label>
                                    </div>`;
                    });

                    //append the choices in the HTMl element with an ID of #choices
                    $("#choices").append(choices);
                    
                }
                
            });

            //getQuestions function
            function getQuestions(NextQuestion) {
               
                $.ajax({
                    type: "get",
                    url: "/questions/"+NextQuestion,
                    // data: data,
                    dataType: "json",
                    success: function (response){
                        console.log(response.question);

                        $('#questions').html(response.question.question);
                        
                    }
                    
                });
            }

            //when one choie is clicked 
            $(document).on('click','.clicked_choice', function(e){
                
                //ajax setup for laravel ajax request
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                //get the choice
                var choice =  $(this).attr('choice_id');

                //submitting answer per question
                var data = {
                    'choice_id': choice,
                    'question_id': NextQuestion
                }

                $.ajax({
                    type: "post",
                    url: "/submit_answer/",
                    data: data,
                    dataType: "json",
                    success: function (response){

                        //when success, NextQusetion will increment
                        NextQuestion += 1;

                        //call the getQuestion function to display the next question
                        //with the NextQuestion new value
                        getQuestions(NextQuestion);
                        // console.log(response.choice_id);

                        //uncheck the radio button
                        $(".clicked_choice").prop('checked', false);

                    }
                    
                });

                // console.log(choice)
            });
        });
        </script>
    </body>
</html>
