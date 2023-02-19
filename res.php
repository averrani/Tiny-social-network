	<?php 
global $auteur;  
require_once 'fonctions-res.php';
require_once 'librairie-res.php'; 
?> 

<!doctype html> 
<html> 
	<head> 
		<meta charset="utf-8" http-equiv="refresh" content="10000" />
		<link rel="icon" href="favicon.ico" />
		<title>Facemok</title>         
		<link rel="stylesheet" type="text/css" href="style-res.css">           
	</head> 
	<body> 
		
		<div class=menu style= "border-radius: 10px">	
			<FORM name="form" method="post" action="res.php">
			<p style="font-weight : bold;">Trier l'affichage</p> 
			<input type="checkbox" name="choix_img" <?php choix_courant("images");?> value="images" />  Images<br/> 
			<input type="checkbox" name="choix_msg" <?php choix_courant("messages");?> value="messages" />  Messages<br/>
			<input type="radio" name="choix_com" <?php choix_courant("avec commentaires");?> value="Avec commentaires" />  Avec commentaires<br/>
			<input type="radio" name="choix_com" <?php choix_courant("sans commentaires");?> value="Sans commentaires" />  Sans commentaires<br/><br/>
			Nombre de posts ? <br/>
			<SELECT name="choix_nbp" size="1" >
			<OPTION <?php choix_courant("1");?> >1
			<OPTION <?php choix_courant("5");?> >5
			<OPTION <?php choix_courant("10");?> >10
			<OPTION <?php choix_courant("25");?> >25
			<OPTION <?php choix_courant("50");?> >50
			</SELECT>
			<button name="action" value="Afficher" style="position:fixed; left: 110px; width: 75px; height: 40px; top: 210px;background-color:#4786d6; border-radius: 5px;" >Afficher</button> 
			</FORM>
			
		</div>
		
		<div class=contenu >
			<?php
			retourne_auteur();
			/*$auteur = "<span class='a'; style='color : #385898; '>" . $auteur . "</span>";*/
			echo "<div style='text-align: center; font-size: 16pt; font-weight=bold; font-family: Trebuchet MS;'>". 'Bonjour '.  "<span class='a'; style='color : #385898; '>" . $auteur . "</span>" . ' !' . "</div>";
			?><br/>
			<?php $name1 = explode(' ', $auteur); ?>
			<div class=nouveau_post style= "border-radius: 10px"><br/>
				<form enctype="multipart/form-data" name="form" method="post" action="res.php">
					<textarea name="post_msg" placeholder="Que voulez-vous dire, <?php echo $name1[0]; ?> ?" cols="75" rows="5" style="border-radius: 10px;" ></textarea><br/>
					Ajouter une image :
					<input type="file" name="post_img" value="Parcourir" accept="image/png, image/jpeg" /><br/>
					<input type="hidden" name="MAX_FILE_SIZE" value=500000 /><br/>
					<input type="submit" name="action" value="Poster" style="background-color:#4786d6; border-radius: 5px; width: 70px; height: 35px;"/>
					 
				</form>
			</div>
			<br/>

				<?php 
					if (isset ($_POST['action']) && $_POST['action'] == "Poster" ) 
					{
						if (!empty($_POST['post_msg']) && empty($_FILES['post_img']['name'])) 
						{
							ajout_post_msg();
						
						} else if( empty($_POST['post_msg']))
						{
							
							if (isset($_FILES['post_img']['tmp_name'])) 
							{
								$retour = copy($_FILES['post_img']['tmp_name'], $_FILES['post_img']['name']);
								move_uploaded_file ( $_FILES['post_img']['name'] , APP.'/DATA/IMG' );
								ajout_post_img();
							}
							}else 
							{
							if (isset($_FILES['post_img']['tmp_name'])) 
							{
								$retour = copy($_FILES['post_img']['tmp_name'], $_FILES['post_img']['name']);
								move_uploaded_file ( $_FILES['post_img']['name'] , APP.'/DATA/IMG' );
								ajout_post_img();
							}
							}
							
						
					}else 
					if(isset($_POST['action'])) 
					{
						global $post;
						$gg = explode('_', $_POST['action']);
						if ($gg[0] == "Aimer" )
						{aimer ($gg[1]);}
						else if ($gg[0] == "Commenter")
						{ajout_post_com($gg[1]);} 
					}
					
						
				?>
				
				<?php 
				if(isset($_POST['choix_msg']) && isset($_POST['choix_img']) && $_POST['choix_msg'] == "messages" && $_POST['choix_img'] == "images" )
				{
					$tab = retourne_tab_50derniersposts(2);
				}
				if(isset($_POST['choix_img']) && $_POST['choix_img'] == "images")
				{	
					$tab = retourne_tab_50derniersposts(1);
				}
				if(isset($_POST['choix_msg']) && $_POST['choix_msg'] == "messages")
				{
					$tab = retourne_tab_50derniersposts(0);
				}
				else {$tab = [];}
				if(isset($_POST['choix_msg']) && sizeof($tab) > $_POST['choix_nbp'])
				{
					  $jjj= $_POST['choix_nbp'];
				}else {$jjj=sizeof($tab);} 
				for($i = 0; $i < $jjj; $i++) 
				{
					if (!empty ($tab[$i]))
					{
						echo "<div class=affiche_post style= 'border-radius : 10px;'>";
						affiche_post($tab[$i]);
						if(isset($_POST['choix_com'])){
						$com = retourne_tab_coms($tab[$i]);
						if ( sizeof($com) != 0 && $_POST['choix_com'] == "Avec commentaires")
						{
							echo "<a href='#def1' style='text-decoration:none; color: #6f676b;'>Voir les commentaires</a>";
							echo "<div id='def1' class='affichecom'>";
							
							  for($j = 0; $j < sizeof($com); $j++)
							{
								affiche_post($com[$j]);
							}
							echo "<a href='#titre' class='masquom'>Masquer les commentaires</a>";
							echo "</div>";	
							
					}}echo "</div>";
					}
				
				}
				?>
				
			</div>
				
	</body>
</html>  

