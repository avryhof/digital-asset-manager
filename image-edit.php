<?
	require_once("config.inc.php");
	
	$terms = $_COOKIE['pl_terms'];
	
	$id = intval($_GET['id']);
	
	if ($id > 0) {
		$is_edit = true;
		$action = "edit";
		$photo = $db->query("SELECT * FROM `photos` WHERE `id` = $id")->fetch_assoc();
	} else {
		$is_edit = false;
		$action = "add";
	}

	$db_tags = $db->query("SELECT `tags` FROM `photos`");
	$db_categories = $db->query("SELECT `categories` FROM `photos`");
	
	$ahead_tags = array();
	while($db_tag = $db_tags->fetch_assoc()) {
		if (!empty($db_tag['tags'])) {
			$dbts = explode(",",$db_tag['tags']);
			foreach($dbts as $dbt) {
				if (!in_array($dbt,$ahead_tags) && !is_numeric($dbt)) {
					$ahead_tags[] = $dbt;
				}
			}
		}
	}
	$ahead_cats = array();
	while($db_category = $db_categories->fetch_assoc()) {
		if (!empty($db_category['categories'])) {
			$dbcs = explode(",",$db_category['categories']);
			foreach($dbcs as $dbc) {
				if (!in_array($dbc,$ahead_cats)) {
					$ahead_cats[] = $dbc;
				}
			}
		}
	}
	
	sort($ahead_cats);
	sort($ahead_tags);
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/art-pages.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- InstanceBeginEditable name="doctitle" -->
    <title><?= $photo['name']; ?> | DairyBusiness Photos</title>
    <!-- InstanceEndEditable -->
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-k2/8zcNbxVIh5mnQ52A0r3a6jAgMGxFJFE2707UxGCk= sha512-ZV9KawG2Legkwp3nAlxLIVFudTauWuBpC10uEafMHYL0Sarrz5A7G79kXh5+5+woxQ5HM559XX2UZjMJ36Wplg==" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <!-- InstanceBeginEditable name="head" -->
  	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-bootstrap/0.5pre/css/custom-theme/jquery-ui-1.10.0.custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
    <style>
		.fileinput-preview img {
			max-width:400px !important;
			width: 400px;
			height:auto;
		}
	</style>
<!-- InstanceEndEditable -->
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
            <!-- InstanceBeginEditable name="content" -->
            
            <form action="image-save.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $action; ?>">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="row">
            	<div class="col-xs-12">
                	<ul class="breadcrumb">
                    	<li><a href="index.php">Photos</a></li>
                        <? if (!empty($terms)) { ?>
                        <li><a href="index.php?q=<?= $terms; ?>"><?= $terms; ?></a></li>
                        <? } ?>
                        <li class="active"><a href="image.php?id=<?= $photo['id']; ?>"><?= $photo['name']; ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12">
                	<div class="btn-group">
                    	<a href="index.php" class="btn btn-default"><i class="fa fa-chevron-left"></i> Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <? if ($is_edit) { ?>
                        	<a href="#" id="delete-button" class="btn btn-danger">Delete</a>
                        <? } ?>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12">
                	<div class="form-group">
                    	<label for="name">Title</label>
                        <input type="text" name="name" id="name" value="<?= $photo['name']; ?>" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12 col-sm-4">
                	<div class="fileinput fileinput-new" data-provides="fileinput" style="width:100%;">
                      <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                      	<img src="thumb.php?width=400&action=web&id=<?= $photo['id']; ?>" class="img-responsive" alt="<?= $photo['name']; ?>" style="width:100%;">
                      </div>
                      <div>
                        <span class="btn btn-default btn-file">
                        	<span class="fileinput-new">Select image</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="file" id="file">
                        </span>
                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-8">
                	<div class="form-group">
                    	<label for="description">Description</label>
                    	<textarea name="description" id="description" class="form-control"><?= $photo['description']; ?></textarea>
                    </div>
                    <div class="form-group">
                    	<label for="photographer">Photographer</label>
                        <input type="text" name="photographer" id="photographer" value="<?= $photo['photographer']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                    	<label for="copyright">Copyright</label>
                        <input type="text" name="copyright" id="copyright" value="<?= $photo['copyright']; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                    	<label for="categories">Categories</label>
                        <input type="text" class="form-control" name="categories" id="categories" value="<?= $photo['categories']; ?>">
                    </div>
                    <div class="form-group">
                    	<label for="tags">Tags</label>
                        <input type="text" class="form-control" name="tags" id="tags" value="<?= $photo['tags']; ?>">
                    </div>
                </div>
            </div>
            
            </form>
            <!-- InstanceEndEditable -->
            </div>
            <div class="col-xs-12 col-sm-3">
            	<div class="panel panel-default">
                	<div class="panel-heading">
                    	<h3 class="panel-title">Simple Search</h3>
                    </div>
                    <div class="panel-body">
                    	<form action="index.php" class="form">
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
	<!-- InstanceBeginEditable name="scripts" -->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.min.js"></script>
    <script>
	$(document).ready(function(e) {
        $('#tags').tokenfield({
  			autocomplete: {
    			source: ['<?= implode("','",$ahead_tags); ?>'],
    			delay: 100
  			},
  			showAutocompleteOnFocus: true
		});
		$('#categories').tokenfield({
  			autocomplete: {
    			source: ['<?= implode("','",$ahead_cats); ?>'],
    			delay: 100
  			},
  			showAutocompleteOnFocus: true
		});
    });
	</script>
    <!-- InstanceEndEditable -->
    
  </body>
<!-- InstanceEnd --></html>