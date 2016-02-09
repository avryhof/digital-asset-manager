<?
	require_once("config.inc.php");
	
	$terms = $_COOKIE['pl_terms'];
	
	$id = intval($_GET['id']);
	$photo = $db->query("SELECT * FROM `photos` WHERE `id` = $id")->fetch_assoc();
	
	$exifdata = imgetimagesize($photo['fullpath']);
	
	$a_filetypes = $db->query("SELECT DISTINCT `type` FROM `photos` ORDER BY `type`");
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/art-pages.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- InstanceBeginEditable name="doctitle" -->
    <title><?= $photo['name']; ?> | DairyBusiness Stock Artwork</title>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />
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
                	<h1><?= $photo['name']; ?> <a href="image-edit.php?id=<?= $photo['id']; ?>" class="btn btn-default"><i class="fa fa-pencil"></i> Edit</a></h1>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12 col-sm-4">
                	<a href="asset.php?width=850&action=web&id=<?= $photo['id']; ?>" class="fancybox" rel="group"><img src="asset.php?width=400&action=web&id=<?= $photo['id']; ?>" class="img-responsive" alt="<?= $photo['name']; ?>"></a>
                    <p><small><em>Click for a larger preview.</em></small></p>
                </div>
                <div class="col-xs-12 col-sm-8">
                	<h3>Description</h3>
                    <p><?= $photo['description']; ?></p>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <p>Categories: 
                            <? foreach(explode(",",$photo['categories']) as $category) { ?>
                                <a href="index.php?category=<?= $category; ?>" class="label label-default"><?= $category; ?></a>
                            <? } ?>
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <p>Tags: 
                            <? foreach(explode(",",$photo['tags']) as $tag) { ?>
                                <a href="index.php?tag=<?= $tag; ?>" class="label label-default"><?= $tag; ?></a>
                            <? } ?>
                            </p>
                        </div>
                    </div>
                    <hr>
                	<table class="table">
                    <thead>
                    <tr>
                    	<th>File information</th>
                        <th>Options</th>
                        <td>&nbsp;</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                    	<td><strong>Original <?= strtoupper($photo['type']); ?> File</strong><br><?= $photo['width']; ?> &times; <?= $photo['height']; ?> pixels</td>
                        <td><a href="asset.php?action=download&id=<?= $photo['id']; ?>" class="btn btn-link"><i class="fa fa-download"></i> Download</a></td>
                        
                    </tr>
                    <? if (in_array($photo['type'],array("tif","tiff","eps","ai","pdf","svg"))) { ?>
                    <tr>
                    	<td><strong>JPG of Original <?= strtoupper($photo['type']); ?> File</strong><br><?= $photo['width']; ?> &times; <?= $photo['height']; ?> pixels</td>
                        <td><a href="asset.php?action=download&id=<?= $photo['id']; ?>&output=jpg" class="btn btn-link"><i class="fa fa-download"></i> Download</a></td>
                        
                    </tr>
                    <? } ?>
                    <? if (in_array($photo['type'],array("tif","tiff","eps","ai","pdf","svg"))) { ?>
                    <tr>
                    	<td><strong>PNG of Original <?= strtoupper($photo['type']); ?> File</strong><br><?= $photo['width']; ?> &times; <?= $photo['height']; ?> pixels</td>
                        <td><a href="asset.php?action=download&id=<?= $photo['id']; ?>&output=png" class="btn btn-link"><i class="fa fa-download"></i> Download</a></td>
                        
                    </tr>
                    <? } ?>
                    <tr>
                    	<td><strong>Low resolution print</strong><br>2000 &times; <?= imageHeight(2000, $photo['width'], $photo['height']); ?> pixels</td>
                        <td><a href="asset.php?action=download&id=<?= $photo['id']; ?>&width=2000" class="btn btn-link"><i class="fa fa-download"></i> Download</a></td>
                    </tr>
                    <tr>
                    	<td><strong>Screen</strong><br>850 &times; <?= imageHeight(850, $photo['width'], $photo['height']); ?> pixels</td>
                        <td><a href="asset.php?action=download&id=<?= $photo['id']; ?>&width=850&output=jpg" class="btn btn-link"><i class="fa fa-download"></i> Download</a></td>
                    </tr>
                    <tr>
                    	<td>
                            <div class="row">
                            	<div class="col-xs-12 col-sm-6">
                                	<div class="form-group">
                                        <select name="output" id="output" class="form-control">
                                        	<option value="jpg">Select a Filetype</option>
                                        <? while($a_filetype = $a_filetypes->fetch_assoc()) { ?>
                                            <option value="<?= trim(strtolower($a_filetype['type'])); ?>"><?= trim(strtoupper($a_filetype['type'])); ?></option>
                                        <? } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                	<div class="form-group">
                                        <input type="number" name="width" id="width" placeholder="Width (in pixels)" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                        	<a href="#" id="downloadButton" class="btn btn-default"><i class="fa fa-download"></i> Download</a>
                        </td>
                    </tr>
                    </form>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12">
                	<h2>Details</h2>
                    <hr>
                </div>
            </div>
            <div class="row">
            	<div class="col-xs-12 col-sm-3">
                	<p><strong>Resource ID</strong><br><?= $photo['id']; ?></p>
                </div>
                <div class="col-xs-12 col-sm-3">
                	<p><strong>Resolution</strong><br><?= $photo['xresolution']; ?> &times; <?=  $photo['yresolution']; ?> dpi</p>
                </div>
                <div class="col-xs-12 col-sm-3">
                	&nbsp;
                </div>                
                <div class="col-xs-12 col-sm-3">
                	<p>&nbsp;</p>
                </div>                
            </div>
            <div class="row">
            	<div class="col-xs-12 col-sm-3">
                	<p><strong>Date</strong><br><?= date("d M y @ H:i", strtotime($photo['time'])); ?></p>
                </div>
                <div class="col-xs-12 col-sm-3">
                	<p><strong>Copyright:</strong> <?= $photo['copyright']; ?><br><strong>Photographer:</strong> <?= $photo['photographer']; ?></p>
                </div>
                <div class="col-xs-12 col-sm-3">
                	<p>&nbsp;</p>
                </div>
                <div class="col-xs-12 col-sm-3">
                	<p>&nbsp;</p>
                </div>
            </div>

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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-media.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.js"></script>
    <script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox({
			'type' : 'image',
			beforeShow : function() {
				var alt = this.element.find('img').attr('alt');
				this.inner.find('img').attr('alt', alt);
				this.title = alt;
    		}
		});
		
		$("#downloadButton").click(function(e) {
            e.preventDefault();
			downloadFile(<?= $photo['id']; ?>, $("#output").val(), $("#width").val());
        });
		
	});
	
	function downloadFile(id, output, width) {
		var dl_url = '<?= $baseurl; ?>/asset.php?action=download&id='+id+'&output='+output+'&width='+width;
		console.log(dl_url);
		window.location = dl_url;
	}
	</script>
    
    <!-- InstanceEndEditable -->
    
  </body>
<!-- InstanceEnd --></html>