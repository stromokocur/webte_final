<?php
	$page = basename(__FILE__, '.php');

	if (isset($_POST['odoberanie'])) {		
		$query = "SELECT email FROM subscribers where email = '" . $_POST['mail'] . "'";
		echo "<br><br>";
		$m = mysql_query($query);
		if(mysql_num_rows($m) < 1){
			mysql_query("INSERT INTO subscribers (name,email,jazyk) VALUES ('" . $_POST['name'] . "','" . $_POST['mail'] . "','" . $_POST['jazyk'] . "')") ;
			echo "Odoberanie zaregistrovane<br>";
		    $msg = "<html><head><meta charset=" . "UTF-8" ."></head><body><p>Prosim potrvrdte odoberanie cez mail:</p> " . "<a href='http://147.175.98.168/final/?page=news&pmail=" . $_POST['mail'] . "'> Potvrdenie</a>";
			mail($_POST['mail'],"Odoberanie noviniek",$msg,"Content-type: text/html; charset=utf8");
		}
		else{
			echo "Odoberanie na tento mail je uz aktivne";
		}
	}

	if(isset($_GET['pmail'])){
		mysql_query("UPDATE subscribers SET verification = 1 where email = '" . $_GET['pmail'] . "'"); 
		echo "Odoberanie potvrdene";
	}

	if(isset($_GET['zmail'])){
		mysql_query("DELETE FROM subscribers where email = '" . $_GET['zmail'] . "'"); 
		echo "Odoberanie zrusene";
	}
?>

<h3><span class="red"><?php echo getContentText($page,'h3');?></span></h3>
<a href="rss.xml" target="_blank">
  <img src="./images/rss.png" alt="RSS" height="60" width="60">
</a>
<form id="odoberanie-box" method="post">
    <b><?php echo getContentText($page,'odoberania'); ?></b>
	<div class="flexBox">
	<label for="name"><?php echo getContentText($page,'meno'); ?></label>
  	<input type="text" name="name" id="name">
	<label for="mail"><?php echo getContentText($page,'email'); ?></label>
  	<input type="text" name="mail" id="mail">
	<label for="jazyk"><?php echo getContentText($page,'jazyk'); ?> </label>
  	<select name="jazyk" id="jazyk">
	    <option value="EN">EN</option>
	    <option value="SK">SK</option>
	</select>
 	<input type="submit" value=<?php echo getContentText($page,'potvrd'); ?> name="odoberanie" class="redButton" style="margin-left: 12px;">
	</div>
</form>
<br><br>
<div class='aktuality'>
<?php
	
	$query = "SELECT headline" . $lang . " headline, text" . $lang ." text, timestamp FROM news ORDER BY timestamp DESC";
	$result = @mysql_query($query);
	if(!$result){
	   echo('Error selecting news: ' . mysql_error());
	   exit();
	}
	if (mysql_num_rows($result) > 0){
    	while($row = mysql_fetch_object($result))
    	{
    	?>
    		
   			<font size="-1"><span style="line-height: 0.2;"><h2><?php echo  $row->headline . "</h2><p>" . getContentText($page,'cas') .  $row->timestamp ; ?></p></span>
   			<div class="aktuality-box">
   				<div class="aktuality-text">
   					<p><?php echo $row->text; ?></p>
   				</div>
   			</div>
   			</font>
   			<br>
    	<?php
    	}
	}
	else{	
		?>
			<font size="-2">No news in the database</font>
		<?php
	}
	mysql_close($link); 
?>
<br>
</div>