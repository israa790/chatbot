<?php
$heure = date('h:i');
$added_on = date('Y-m-d h:i:s');

// connection m3a l BD
$conn = mysqli_connect("localhost", "root", "", "bot") or die("Database Error");

// ajax ye5edh message mtaa luser

$getMesg2 = mysqli_real_escape_string($conn, $_POST['text']);
$getMesg=  strtolower($getMesg2);
if ($getMesg == "categories") {
    $check_data = "SELECT wp_terms.name FROM wp_terms 
    LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
    WHERE wp_term_taxonomy.taxonomy = 'product_cat';";

}

else if ($getMesg == "new products") {
    $check_data = "SELECT distinct wp_posts.post_title FROM wp_posts 
    LEFT JOIN wp_postmeta ON wp_posts.id = wp_postmeta.post_id
    where wp_posts.post_type ='product'
    and wp_posts.post_status='publish'
     order by  wp_posts.post_date desc limit 5;";

}
   //  $check_data = "SELECT post_title FROM wp_posts WHERE post_type='product' And post_status='publish';";
//desc limit 5
//wp_posts.post_parent > 0

else if ($getMesg == "image") {
    $check_data="SELECT meta_value FROM wp_postmeta, wp_terms, wp_termmeta , wp_term_taxonomy 
     
    WHERE wp_postmeta.meta_id = wp_termmeta.meta_id 
    AND wp_terms.term_id= wp_termmeta.term_id 
    
    AND  wp_postmeta.meta_key ='_wp_attached_file'
    AND wp_term_taxonomy.taxonomy = 'product_cat';"; 

    
    
    
    /*"SELECT concat((select option_value from wp_options where option_name ='siteurl'  limit 1),'/wp-content/uploads/',childmeta.meta_value)
    FROM wp_postmeta childmeta 
    INNER JOIN wp_postmeta parentmeta ON (childmeta.post_id=parentmeta.meta_value)
    WHERE parentmeta.meta_key='_thumbnail_id' and childmeta.meta_key = '_wp_attached_file'
    AND parentmeta.post_id = POST_ID ;";*/
    
    
    
    /*"SELECT distinct post_name FROM wp_posts 
 LEFT JOIN wp_postmeta ON wp_posts.id = wp_postmeta.post_id
    where post_type ='attachment'
    and post_status='publish'
    and post_mime_type ='image/jpeg';";*/
   //  $check_data = "SELECT post_title FROM wp_posts WHERE post_type='product' And post_status='publish';";
//desc limit 5
//wp_posts.post_parent > 0
}

else if ($getMesg == "all products") {
  
     $check_data = "SELECT post_title FROM wp_posts WHERE post_type='product' And post_status='publish';";

}


else {
    $check_data = "SELECT replies FROM chatbot WHERE queries LIKE '%$getMesg%'";
}



//requette eli temchy lel base bech tlawej 3al kelma eli fil message 
//$check_data = "SELECT replies FROM chatbot WHERE queries LIKE '%$getMesg%'";
$run_query = mysqli_query($conn, $check_data) or die("Error");


//si la requête de l'utilisateur correspond à la requête de la base de données,
//nous afficherons la réponse, sinon elle passera à une autre instruction
if (mysqli_num_rows($run_query) > 0) {
    //fetching replay mn DB
 
    if ($getMesg == "categories") {
        echo "<p> please choose one of our categories where you can find all of her products :) !<br>";
        while ($row = mysqli_fetch_assoc($run_query)) {
            $categorie_name=$row['name'];
            $replay= "<a href='http://localhost/WSwordpress/product-category/$categorie_name/'>".$categorie_name ."</a>". "</br>";
            echo $replay."<br>";
        }
    } 

   else if ($getMesg == "new products") {
        while ($row = mysqli_fetch_assoc($run_query)) {
            $produits_name=$row['post_title'];
            $replay= "<a href='http://localhost/WSwordpress/product/$produits_name/'>". $produits_name."</a>". "</br>";
            echo $replay."<br>";
        }
    } 

    else if ($getMesg == "all products") {
        while ($row = mysqli_fetch_assoc($run_query)) {
            $produits_name=$row['post_title'];
            $replay= "<a href='http://localhost/WSwordpress/product/$produits_name/'>". $produits_name."</a>". "</br>";
            echo $replay."<br>";
        }
    }
    else if ($getMesg == "image") {
        while ($row = mysqli_fetch_assoc($run_query)) {
            $produits_name=$row['meta_value'];
            $replay= "<img src='http://localhost/WSwordpress/wp-content/uploads/2021/08/$produits_name/' />". "</br>";
            echo $replay."<br>";
        }
    }

   
    else {
        $fetch_data = mysqli_fetch_assoc($run_query);
        $replay = $fetch_data['replies'];
        //stockage de la réponse à une variable que nous enverrons à ajax
        echo $replay;
    }
} 

else {
    $replay = "Sorry can't be able to understand you!";
    echo $replay;
}


//user storage 
mysqli_query($conn, "insert into message(message,added_on,type) values('$getMesg','$added_on','user')");
mysqli_query($conn, "insert into message(message,added_on,type) values('$replay','$added_on','bot')");
?>