<?php
require_once '\librairie-res.php'; 
require_once 'res.php'; 
function retourne_auteur()
{
	global $auteur;
	$valeurs = explode ('/', $_SERVER['REQUEST_URI']);
	$tab = $valeurs[1];
	$nom = explode ('.', $tab);
	$auteur = ucfirst("$nom[0]")." ".strtoupper("$nom[1]");
	return $auteur;						
}
function ajout_post_msg()
{
	global $auteur;
	global $nbPost;
	$nbPost = prendre_un_post();
	file_put_contents(APP.'/DATA/' . $nbPost . "-msg.txt", "message\n", LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-msg.txt", $auteur . "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-msg.txt", date("Y-m-d H:i:s") . "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-msg.txt", 0 . "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-msg.txt", "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-msg.txt", $_POST['post_msg'], FILE_APPEND|LOCK_EX);

}
function ajout_post_img()
{
	global $auteur;
	global $nbPost;
	$nbPost = prendre_un_post();
	$fichier=$nbPost . "-" . $_FILES['post_img']['name'];
	if (verifie_image($fichier) == True) 
	{
		file_put_contents(APP.'/DATA/' . $nbPost . "-img.txt", "image\n", LOCK_EX);
		file_put_contents(APP.'/DATA/' . $nbPost . "-img.txt", $auteur . "\n", FILE_APPEND|LOCK_EX);
		file_put_contents(APP.'/DATA/' . $nbPost . "-img.txt", date("Y-m-d H:i:s") . "\n", FILE_APPEND|LOCK_EX);
		file_put_contents(APP.'/DATA/' . $nbPost . "-img.txt", 0 . "\n", FILE_APPEND|LOCK_EX);
		file_put_contents(APP.'/DATA/' . $nbPost . "-img.txt", "\n", FILE_APPEND|LOCK_EX);
		file_put_contents(APP.'/DATA/' . $nbPost . "-img.txt", "./IMG/" . $nbPost . "-" . $_FILES['post_img']['name'] . "\n", FILE_APPEND|LOCK_EX);
		file_put_contents(APP.'/DATA/' . $nbPost . "-img.txt", $_POST['post_msg'] . "\n", FILE_APPEND|LOCK_EX);
	}else echo "Erreur, l'image n'a pas été accepté par le serveur !";
}
function affiche_post($fichier)
{
	global $auteur;
	global $nbPost;
	$tab = file('../../DATA/' . $fichier);
	$pos = explode ('.', $fichier);
	$post = $pos[0];
	$no = explode('.', $tab[1]); 
	if (count($no) > 1)
	{
		$auteu = ucfirst("$no[0]")." ".strtoupper("$no[1]");
	}else {$auteu = $tab[1];}
	
	switch($tab[0])
	{
		case "message\n":
			echo "<div style='font-size: 12pt;'>" . "<span class='a'; style='color : #385898; '>" . $auteu . "</span>" . "<br/>" . "</div>";
			echo "<div style='font-size: 8pt; color: #6f7a86;'>" . $tab[2] . "<br/>" . "<br/>" . "</div>";
			echo "<div style='font-size: 11.2pt; overflow-wrap: break-word;'>"; for($i = 5; $i < sizeof($tab); $i++) {echo $tab[$i] . "<br/>";} echo "</div>" . "</br>";	
			echo "👍" . "  " . $tab[3] . "</br>";?>
			<hr>
			<form name="form" method="post" action="res.php">
			<button type="submit" name="action" value= 'Aimer_<?php echo $post; ?>' class='b'; style= "width: 150px; height: 30px; color: #6f676b; font-size: 10pt; font-weight:bold; border:none; hover:background-color: #e4e6eb; "> 👍 J'aime </button>
			<button type="submit" name="action" value= 'Commenter_<?php echo $post; ?>' class='b'; style= "width: 150px; position:relative; left: 175px; height: 30px; color: #6f676b; font-size: 10pt; font-weight:bold; border:none; hover:background-color: #e4e6eb;"> 🗨️ Commenter</button>
			<textarea name='post_com_<?php echo $post; ?>' style= "border-radius : 10px; position: relative; bottom:-7px;" placeholder=" Votre commentaire..." cols = "65"></textarea>
			<br/>
			<br/>
			</form>
			<?php
		break; 
		case "image\n":
			echo "<div style='font-size: 12pt;'>" . "<span class='a'; style='color : #385898; '>" . $auteu . "</span>" . "<br/>" . "</div>";
			echo "<div style='font-size: 8pt; color: #6f7a86;'>" . $tab[2] . "<br/>" . "<br/>" . "</div>";
			if (!empty($tab[6])) {echo "<div style='font-size: 11.2pt; overflow-wrap: break-word;'>"; for($i = 6; $i < sizeof($tab); $i++) {echo $tab[$i] . "<br/>";} echo "</div>";}
			echo "<br/>";
			$bi = explode('.', $tab[5]);
			echo '<img src="' . '../../DATA' . $bi[1] . "." . $bi[2] . '">' . "</br>" . "</br>";
			echo "👍" . "  " . $tab[3] . "</br>";?>
			<hr>
			<form name="form" method="get" action="res.php">
			<button type="submit" name="action" value= 'Aimer_<?php echo $post; ?>' class='b'; style= "width: 150px; height: 30px; color: #6f676b; font-size: 10pt; font-weight:bold; border:none; hover:background-color: #e4e6eb; "> 👍 J'aime </button>
			<button type="submit" name="action" value= 'Commenter_<?php echo $post; ?>' class='b'; style= "width: 150px; position:relative; left: 175px; height: 30px; color: #6f676b; font-size: 10pt; font-weight:bold; border:none; hover:background-color: #e4e6eb;"> 🗨️ Commenter</button>
			<textarea name='post_com_<?php echo $post; ?>' value='post_com_<?php echo $post; ?>' style= "border-radius : 10px; position: relative; bottom:-7px;" placeholder=" Votre commentaire..." cols = "65"></textarea>
			<br/>
			<br/>
			</form>
<?php
		break;
		case "commentaire\n":
			if(isset($_POST['choix_com']) && $_POST['choix_com'] == "Avec commentaires"){
			echo "<div style='font-size: 12pt;'>" . "<span class='a'; style='color : #385898; '>" . $auteu . "</span>" . "<br/>" . "</div>";
			echo "<div style='font-size: 8pt; color: #6f7a86;'>" . $tab[2] . "<br/>" . "<br/>" . "</div>";
			if (!empty($tab[6])) {echo "<div style='font-size: 11.2pt; overflow-wrap: break-word;'>"; for($i = 6; $i < sizeof($tab); $i++) {echo $tab[$i] . "<br/>";} echo "</div>";}
			echo "<br/>";
			$bi = explode('.', $tab[5]);
			echo "👍" . "  " . $tab[3] . "</br>";?>
			<hr>
			<form name="form" method="post" action="res.php">
			<button type="submit" name="action" value= 'Aimer_<?php echo $post; ?>' class='b'; style= "width: 150px; height: 30px; color: #6f676b; font-size: 10pt; font-weight:bold; border:none; hover:background-color: #e4e6eb; "> 👍 J'aime </button>
			</form>
<?php	 }
		break;
			
		}
			
	
}
function ajout_post_com($nompost)
{
	global $auteur;
	$nbPost = prendre_un_post();
	file_put_contents(APP.'/DATA/' . $nbPost . "-com.txt", "commentaire\n", LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-com.txt", $auteur . "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-com.txt", date("Y-m-d H:i:s") . "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-com.txt", 0 . "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-com.txt", "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-com.txt", "./" . $nompost . ".txt" . "\n", FILE_APPEND|LOCK_EX);
	file_put_contents(APP.'/DATA/' . $nbPost . "-com.txt", $_POST['post_com_' . $nompost] . "\n", FILE_APPEND|LOCK_EX);
} 	
	
?>

