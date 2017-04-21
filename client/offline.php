<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![ENDif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![ENDif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![ENDif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![ENDif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>bootstrap form validation</title>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="container">
<div class="col-lg-9">

  <form class="form-horizontal" action=" " method="post"  id="reg_form">
    <fieldset>
      
      <!-- Form Name -->
      <legend> უკუკავშირი</legend>
    
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">სახელი</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input  name="first_name" placeholder="სახელი" class="form-control"  type="text">
          </div>
        </div>
      </div>
      
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label" >გვარი</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input name="last_name" placeholder="გვარი" class="form-control"  type="text">
          </div>
        </div>
      </div>
      
    
      <!-- Text input-->
      
      <div class="form-group">
        <label class="col-md-4 control-label">ტელეფონი #</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            <input name="phone" placeholder="+995 55 523 400" class="form-control" type="text">
          </div>
        </div>
      </div>
      
	  <div class="form-group">
        <label class="col-md-4 control-label">ელ-ფოსტა</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input name="email" placeholder="E-Mail Address" class="form-control"  type="text">
          </div>
        </div>
      </div>
     
      
      <!-- Select Basic -->

      
  
      
        <!-- Text area -->
      
      <div class="form-group">
        <label class="col-md-4 control-label">შეტყობინება </label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
            <textarea class="form-control" name="comment" placeholder="შეტყობინება"></textarea>
          </div>
        </div>
      </div>
       
	   <div class="form-group">
        <label class="col-md-4 control-label">უსაფრთხოების კოდი</label>
        <div class="col-md-6  inputGroupContainer">
          <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-ok"></i></span>
            <input name="code" placeholder="უსაფრთხოების კოდი" class="form-control"  type="text">
			<img src="get_captcha.php" alt="" id="captcha" />
          </div>
        </div>
      </div>
     
       
    
       
  
      <!-- Button -->
      <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
          <button type="buttom" class="btn btn-success" id="Send" >გაგზავნა <span class="glyphicon glyphicon-send"></span></button>
		  <input type="reset" class="btn btn-warning" name="res" value="უარყოფა" />
        </div>
      </div>
    </fieldset>
  </form>
</div>




</div>


</div>
<!-- /.container --> 
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>


<script type="text/javascript">



$(document).ready(function() {
	   var str_validation = true; 
    $('#reg_form').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            first_name: {
				
                validators: {
                        stringLength: {
                        min: 2,
						max: 30,
						
						message: 'სახელის ველში დასაშვებია 2 დან 30 სიმბოლო'
                    },
                        notEmpty: {
                        message: 'სახელის ველი ცარიელია'
                    }
                }
            },
             last_name: {
				
                validators: {
                     stringLength: {
                        min: 2,
						max: 30,
						message: 'გვარის ველში დასაშვებია 2 დან 30 სიმბოლო'
                    },
                    notEmpty: {
                        message: 'გვარის ველი ცარიელია'
                    }
                }
            },
           
            phone: {
				
                validators: {
				 stringLength: {
                        min: 5,
						max: 15,
						message: 'ტელეფონის ნომრის ველში დასაშვებია 5 დან  15 სიმბოლო'
                    },
					integer: {
                        message: 'ტელეფონის ნომრის ველში დაშვებულია მხოლოდ ციფრები'
                    },
                    notEmpty: {
                        message: 'ტელეფონის ნომრის ველი ცარიელია'
                    }
					 
                }
            },
     
           
		comment: {
                validators: {                    
                    notEmpty: {
                        message: 'შეტყობინების ტექსტი'
                    }
                    }
                 },	
	 email: {
                validators: {
                    notEmpty: {
                        message: 'ელ-ფოსტის ველი ცარიელია'
                    },
                    emailAddress: {
                        message: 'ელ-ფოსტის ფორმატი არასწორია'
                    }
                }
            },
            
            }
        })
		
 	
        .on('success.form.bv', function(e) {
			 var $form     = $(e.target),
                validator = $form.data('bootstrapValidator');
				$form.find('.alert').html('Thanks for signing up. Now you can sign in as ' + validator.getFieldElements('first_name').val()).show();
        });
	
});


 
 </script>
</body>
</html>
