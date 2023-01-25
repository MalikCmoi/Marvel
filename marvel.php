<?php
header('Content-type: application/json');
function chat_connexion_bdd()
{
	//                                       database  user       password
	$db=new PDO('mysql:host=localhost;dbname=arrc1617_Marvel', 'arrc1617_malik', 'Malik20012.!');
	// Si la table messages n'existe pas encore, appeler chat_initialiser_bdd()
	// Afficher les messages d'erreur
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	
	return $db;
}
$db = chat_connexion_bdd();

/**
 * Test
 */
// $requete =$db->prepare("SELECT * FROM mytable LIMIT 1");
// $requete->execute();
// $res = $requete->fetchAll(PDO::FETCH_ASSOC);
// var_dump($res);
// foreach ($res as $key => $value) {
// 	echo "$key : $value";
// }

function research_by_name($db,$name){
	$requete = $db->prepare("SELECT name,page_id FROM mytable WHERE name REGEXP'^($name)' LIMIT 5");
	$requete->execute();
	$res = $requete->fetchAll(PDO::FETCH_ASSOC);
	return $res;
}

function get_page_id($db,$id){
	$requete = $db->prepare("SELECT * FROM mytable WHERE page_id = :id");
	$requete->bindValue(":id",$id);
	$requete->execute();
	$res = $requete->fetchAll(PDO::FETCH_ASSOC)[0];
	$res["url"] = get_img_withurl($res["urlslug"]);
	return $res;
}


function get_img_withurl($url){
	$html = file_get_contents("https://marvel.fandom.com/wiki$url");
	$doc = new DOMDocument();
	@$doc->loadHTML($html);
	$xml=simplexml_import_dom($doc); // just to make xpath more simple
	$images=$xml->xpath('//img')[1];
	// var_dump($images);
	$image = $images['src'];
	return $image->__toString();
}

if(isset($_GET['sug'])){
	$tab = research_by_name($db,$_GET["sug"]);
	$tabJson["hero1"] =	$tab[0];
	$tabJson["hero2"] = $tab[1];
	$tabJson["hero3"] = $tab[2];
	$tabJson["hero4"] = $tab[3];
	$tabJson["hero5"] = $tab[4];
	echo json_encode($tabJson);
}else if(isset($_GET['page_id'])){
	// conversion json du retour de la fonction get_page_id
	echo json_encode(get_page_id($db,$_GET['page_id']));
}

?>