<?php
if(!$this->pick_pack_woo_in_cart($product_id) && count($fragile) == 0 && count($large) == 0){ ?>
    <button class="toggle-2 button" data-target="pick-pack-container " >Add Eco Bag</button>
<?php } else { ?> 
    <!-- <button class="toggle-2 button" data-target="pick-pack-container " style="display: none">Add Eco Bag</button> -->
<?php } ?>
<?php if (count($fragile) != 0){ 
    foreach ($fragile as $string) {?>
        <p><?php echo $string ?></p>
<?php }
}
?>

<?php if (count($large) != 0){ 
    foreach ($large as $string) {?>
        <p><?php echo $string ?></p>
<?php }
}
?>
<div class="container pick-pack-container " style="display: none">
      
      <div class="overlay_pick_pack ">
        
      </div>
      <div id="pick_pack_popup" class="popup ">
        <div class="popup-header">
            Pick Pack Package
            <span class="close toggle" data-target="pick_pack_popup">close</span>
        </div>
        <div class="popup-body">
            <div class="popup_flex">
                <?php
                    if(isset($product_image) && !empty($product_image)){ 
                        $img_url = wp_get_attachment_url($product_image);
                    }
                ?>
                <div class="popup_col img_col" style="background-image: url('<?php if(isset($img_url) && !empty($img_url)){ echo $img_url; }else{ echo "https://img.jakpost.net/c/2020/03/31/2020_03_31_91353_1585648670._large.jpg"; } ?>');">
                    
                    <!-- <img src="<?php if(isset($img_url) && !empty($img_url)){ echo $img_url; }else{ echo "https://img.jakpost.net/c/2020/03/31/2020_03_31_91353_1585648670._large.jpg"; } ?>" alt=""> -->
                </div>
                <div class="popup_col">
                    <div class="cnt">
                        <!-- PickPack, l'ère des nouveaux emballages -->
                        <h3><?php if(isset($product_title) && !empty($product_title)){ echo $product_title; } ?></h3>
                        <!-- Chez nous, nous pensons que le monde a besoin de meilleures solutions pour les emballages à usage unique. C'est pourquoi nous proposons désormais une alternative. Elle s'appelle PickPack - un emballage de livraison réutilisable et retournable. -->
                        
                        <!-- PickPack est une solution très simple au problème toujours croissant des déchets d'emballage. Il réduit tout simplement les déchets et permet d'économiser une grande quantité des émissions de CO2 par rapport aux emballages à usage unique. Ajoutez un PickPack à votre commande pour 3 $ et recevez vos marchandises dans un emballage réutilisable et écologique. Le retour est GRATUIT ! -->

                        <p><?php if(isset($product_description) && !empty($product_description)){ echo $product_description; } ?></p>
                        <div class="popup-footer">
                            <h5>Prix : $3 <span><button class="toggle pick_pack_button pick_pack_add" data-target="pick_pack_popup">Ajouter au panier</button></span></h5>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </div>
</div>



<script>
    jQuery( document ).ready(function() {
        /*$('body').on('updated_cart_totals',function() {
            
            location.reload();
        });*/

        <?php
            // if(!isset($_SESSION["pick_pack_product_added"]) && $_SESSION["pick_pack_product_added"] == "" && !woo_in_cart($product_id)){
            if(!$this->pick_pack_woo_in_cart($product_id) && count($fragile) == 0 && count($large) == 0){
                ?>
                    console.log('<?php echo $_SESSION["pick_pack_product_added"]; ?>');
                    /*jQuery(".toggle-2").show();*/
                    jQuery(".pick-pack-container").show();
                    /*jQuery('.overlay_pick_pack ').removeClass('hide');
                    jQuery('#pick_pack_popup').removeClass('hide');*/
                    /*jQuery('html, body').css({
                        overflow: 'hidden',
                        height: '100%'
                    });*/
                <?php
            }else{
                ?>
                console.log('<?php echo $_SESSION["pick_pack_product_added"]; ?>');
                    jQuery(".pick-pack-container").hide();
                    /*jQuery(".toggle-2").hide();*/
                <?php
            }
        ?>
    });
</script>