<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Голосование</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <h1 class ="page-header text-center">Голосование</h1>
 
    <div class="container">
      <div class="row">
        <div class="col-sm-offset-3 col-sm-6">
          <section class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">
                Голосование
              </h3>
            </div>
            <div class="panel-body">
              <div class="question"></div>
              <hr>
              <div id="vote-section">              
                <form id="vote" action="poll-vote.php" method="POST">
                  <div class="answers"></div>
                  <button type="submit" class="btn btn-default" disabled="disabled">Голосовать</button>
                </form>
              </div>            
            </div>
          </section>
        </div>
      </div>    
    </div>
      
    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $(function(){
      
        const pathToPolls = 'polls.php';
   
        let vote = {};
        $.get(pathToPolls, function(data) {
          
          var data = JSON.parse(data);
          vote['id'] = data['id'];
          vote['question'] = data['question'];
          vote['answers'] = data['answers'];

          processPoll();
        });    

        const processPoll = function() {        
          let _id = vote['id'];
          let _answers = vote['answers'];
          let form = $('#vote');
          form.parent().parent().find('.question').text(vote['question']);
          form.prepend('<input type="hidden" name="count" value="'+vote['answers'].length+'">');
          form.prepend('<input type="hidden" name="id" value="'+vote['id']+'">');
          let answers = form.find('.answers');
          for (let i=0; i<=vote['answers'].length-1;i++) {
            answers.append('<div class="radio">'+
                '<label>'+
                  '<input type="radio" name="poll" value="'+(i+1)+'">'+
                  vote['answers'][i]+
                '</label>'+
              '</div>');
          }
          if (form.find('button[type="submit"]:disabled')) { 
            form.find('input[type="radio"]').click(function(){
              form.find('button[type="submit"]').prop('disabled',false);
              form.find('input[type="radio"]').off('click');  
            });          
          };
          
          form.submit(function(e) {
            e.preventDefault();
            $.post(form.attr('action'), form.serializeArray(), function(data) {
              if (data) {
                var data = JSON.parse(data);
                let output = '';
                let result = data[_id];
                let totalvotes = 0;
                for (let i=0; i <= result.length-1; i++) {
                  totalvotes += result[i];
                }
                for (let i=0; i <= result.length-1; i++) {
                  output += '<p style="margin:0px;">'+_answers[i]+'</p>'+
                    '<p class="text-right" style="margin:0px;"><b>'+((result[i]/totalvotes)*100).toFixed(1)+'%</b> (Голосов: '+result[i]+')</p>'+
                    '<div class="progress">'+
                      '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="'+(result[i]/totalvotes)*100+'" aria-valuemin="0" aria-valuemax="100" style="width: '+(result[i]/totalvotes)*100+'%">'+
                        '<span class="sr-only">'+(result[i]/totalvotes)*100+'%</span>'+
                      '</div>'+
                    '</div>';
                }
                $('#vote-section').html(output);
              }
            }); 
          });
        };

      });
    </script>
  </body>
</html>