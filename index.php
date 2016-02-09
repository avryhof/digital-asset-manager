<?
	require_once("config.inc.php");
	
	$terms = $_REQUEST['q'];
	
	$table = "photos";
	$columns = array('name','file','description','tags');
	
	if (!empty($terms)) {
		setcookie("pl_terms",$terms,strtotime("+1 day"));
		// $photos = $db->query("SELECT *, MATCH(`name`,`file`,`photographer`,`tags`,`desc`) AGAINST('$terms') AS `relevance` FROM `photos` WHERE `relevance` > 0 ORDER BY `relevance` DESC");
		$wheres = array();
		$terms = str_replace(' ','%',$terms);
		foreach($columns as $column) {
			$wheres[] = "`$column` LIKE '$terms%' OR `$column` LIKE '%$terms' OR `$column` LIKE '%$terms%'";
		}
		$photos = $db->query("SELECT * FROM `$table` WHERE ".implode(" OR ", $wheres));
	} elseif (!empty($_GET['tag'])) {
		$tag = trim($_GET['tag']);
		$photos = $db->query("SELECT * FROM `$table` WHERE `tags` LIKE '$tag,%' OR `tags` LIKE '%$tag,%' OR `tags` LIKE '%,$tag'");
	} elseif (!empty($_GET['category'])) {
		$cat = trim($_GET['tag']);
		$photos = $db->query("SELECT * FROM `$table` WHERE `categories` LIKE '$cat,%' OR `categories` LIKE '%$cat,%'  OR `categories` LIKE '%,$cat'");
	} else {
		$qty = 21;
		$photos = $db->query("SELECT * FROM `$table` ORDER BY `id` LIMIT $qty");
	}
?>
<!DOCTYPE html>
<html lang="en"><!-- InstanceBegin template="/Templates/art-pages.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>DairyBusiness Stock Artwork</title>
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
  <link href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" rel="stylesheet">
  <style>
  	.tile {
		width: 175px;
		min-height:275px;
		vertical-align: top;
		display:inline-block;
		margin:5px;
		overflow:hidden;
		position:relative;
		top:0;
		left:0;
	}
	.desc {
		font-weight:bold;
	}
	.footer {
		position:absolute;
		bottom:0;
		left:0;
		width:100%;
		text-align:center;
	}
	.tile img {
		width: 100%;
		height:auto;
		border:1px solid #000000;
	}
  </style>
  <script>
  	var lastPhoto = 0;
  </script>
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
            
            <? 
				if ($db->errno > 0) {
					echo "<pre>" . $db->error . "</pre>"; 
				}
			?>
            <? if (!empty($terms)) { ?>
            <div class="row">
            	<div class="col-xs-12">
                	<ul class="breadcrumb">
                    	<li><a href="index.php">Home</a></li>
                        <li><span><?= $terms; ?></span></li>
                    	<li class="active"><span><?= $photos->num_rows; ?> results</span></li>
                    </ul>
                </div>
            </div>
            <? } elseif (!empty($cat)) { ?>
            <div class="row">
            	<div class="col-xs-12">
                	<ul class="breadcrumb">
                    	<li><a href="index.php">Home</a></li>
                        <li><span><?= $cat; ?></span></li>
                    	<li class="active"><span><?= $photos->num_rows; ?> results</span></li>
                    </ul>
                </div>
            </div>
            <? } elseif (!empty($tag)) { ?>
            <div class="row">
            	<div class="col-xs-12">
                	<ul class="breadcrumb">
                    	<li><a href="index.php">Home</a></li>
                        <li><span><?= $tag; ?></span></li>
                    	<li class="active"><span><?= $photos->num_rows; ?> results</span></li>
                    </ul>
                </div>
            </div>
            <? } else { ?>
            <div class="row">
            	<div class="col-xs-12">
                	<ul class="breadcrumb">
                    	<li class="active"><span>No search terms, browsing photos. Scroll down to load more.</span></li>
                    </ul>
                </div>
            </div>
            <? } ?>
            <div class="row">
                <div class="col-xs-12" id="main">
                <? 
					while($photo = $photos->fetch_assoc()) {
				?>
                	<script>
						lastPhoto = <?= $photo['id']; ?>;
					</script>
                	<div class="panel panel-default tile" id="photo_<?= $photo['id']; ?>">
                    	<div class="panel-heading">
                        	<h3 class="panel-title"><a href="image.php?id=<?= $photo['id']; ?>" title="<?= $photo['name']; ?>"><?= $photo['name']; ?></a></h3>
                        </div>
                        <div class="panel-body">
                    		<div class="image">
                    			<a href="image.php?id=<?= $photo['id']; ?>"><img src="asset.php?id=<?= $photo['id']; ?>&action=web&width=300" alt="<?= $photo['name']; ?>"></a>
                        	</div>
                        </div>
                        <div class="panel-footer">
                        	<div class="row">
                            	<div class="col-xs-4">
                                	<a href="asset.php?action=download&id=<?= $photo['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i></a>
                                </div>
                                <div class="col-xs-8">
                                	<span class="badge"><?= strtoupper($photo['type']); ?></span><br><span class="badge"><?= filesize_human($photo['path'] . DIRECTORY_SEPARATOR . $photo['file']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <? 
					} 
				?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-infinitescroll/2.1.0/jquery.infinitescroll.min.js"></script>
    <script>
    $(window).scroll(function() {
		<? if (empty($terms)) { ?>
    	if($(window).scrollTop() == $(document).height() - $(window).height()) {
           loadMore();
    	}
		<? } ?>
	});
	
	function loadMore() {
		// var photoid = $(".tile").last().attr("id").replace("photo_","");
		$.getJSON("load-more.php", {
			lastId: lastPhoto
		}, function( morephotos ) {
			for ( var i in morephotos ) {
				  var newElement = '<div class="panel panel-default tile" id="photo_' + morephotos[i].id + '"><div class="panel-heading"><h3 class="panel-title"><a href="image.php?id=' + morephotos[i].id + '">' + morephotos[i].name + '</a></h3></div><div class="panel-body"><div class="image"><a href="image.php?id=' + morephotos[i].id + '"><img src="asset.php?id=' + morephotos[i].id + '&action=web&width=300" alt="' + morephotos[i].name + '"></a></div></div><div class="panel-footer"><div class="row"><div class="col-xs-4"><a href="asset.php?action=download&id=' + morephotos[i].id + '" class="btn btn-primary btn-sm"><i class="fa fa-download"></i></a></div><div class="col-xs-8"><span class="badge">' + morephotos[i].type + '</span><br><span class="badge">' + morephotos[i].filesize + '</span></div></div></div></div>';
				  $("#main").append(newElement);
				  lastPhoto = morephotos[i].id;
			}
		});
	}
    </script>
    <!-- InstanceEndEditable -->
    
  </body>
<!-- InstanceEnd --></html>