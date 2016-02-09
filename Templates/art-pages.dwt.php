<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- TemplateBeginEditable name="doctitle" -->
    <title>DairyBusiness Stock Artwork</title>
    <!-- TemplateEndEditable -->
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-k2/8zcNbxVIh5mnQ52A0r3a6jAgMGxFJFE2707UxGCk= sha512-ZV9KawG2Legkwp3nAlxLIVFudTauWuBpC10uEafMHYL0Sarrz5A7G79kXh5+5+woxQ5HM559XX2UZjMJ36Wplg==" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
  <body>
    
    <div class="container-fluid">
    	
        <div class="row">
        	<div class="col-xs-12">
            	<h1>Stock Art</h1>
            </div>
        </div>
        
        <nav class="navbar navbar-default">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
              <ul class="nav navbar-nav pull-right">
                <li<?= ($pagename == "index.php" ? ' class="active"' : ''); ?>><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li<?= ($pagename == "image-edit.php" ? ' class="active"' : ''); ?>><a href="image-edit.php"><i class="fa fa-upload"></i> Add</a></li>
                <!--
                <li><a href="../art/indexer.php">ReIndex</a></li>
                -->
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </nav>
        
        <div class="row">
        	<div class="col-xs-12 col-sm-9">
            <!-- TemplateBeginEditable name="content" -->
            
            <!-- TemplateEndEditable -->
            </div>
            <div class="col-xs-12 col-sm-3">
            	<div class="panel panel-default">
                	<div class="panel-heading">
                    	<h3 class="panel-title">Simple Search</h3>
                    </div>
                    <div class="panel-body">
                    	<form action="../art/index.php" class="form">
                        	<div class="form-group">
                            	<label for="q">Search Terms</label>
                                <input type="text" name="q" id="q" class="form-control" value="<?= $terms; ?>">
                            </div>
                            <button type="submit" class="btn btn-default">Search</button>

                        </form>
                        <hr>
                        <div class="form-group">
                        	<label for="search">Search For more</label>
							<script>
                              (function() {
                                var cx = '004663660334660142910:rmajmblfotw';
                                var gcse = document.createElement('script');
                                gcse.type = 'text/javascript';
                                gcse.async = true;
                                gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
                                    '//cse.google.com/cse.js?cx=' + cx;
                                var s = document.getElementsByTagName('script')[0];
                                s.parentNode.insertBefore(gcse, s);
                              })();
                            </script>
                            <gcse:searchbox></gcse:searchbox>
                            <p class="help-block">Search on free stock photography websites.</p>
                            <hr id="google-search-results">
                			<gcse:searchresults></gcse:searchresults>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
	<!-- TemplateBeginEditable name="scripts" -->
    <!-- TemplateEndEditable -->
    
  </body>
</html>