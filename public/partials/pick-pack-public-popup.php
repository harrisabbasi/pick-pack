<?php
if(!$this->pick_pack_woo_in_cart($product_id) && ($cart_count !== $fragile_count + $large_count ) && !$points_allocated_less){ ?>
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
            
            <span class="close toggle" data-target="pick_pack_popup">close</span>
        </div>
        <div class="popup-body">
            <div class="logo-container">
                <img class="pick-pack-logo" src="https://phpstack-851887-2938889.cloudwaysapps.com/wp-plugin-server/assets/Logo_Officiel_PickPack_2-removebg-preview.png" alt="">
                <h3>L'EMMALAGE REUTILISABLE, LA SOLUTION DU FUTUR.</h3>
            </div>
           
            <div class="popup_flex">
                            
                

                <div class="popup_col">
                    <div class="cnt">
                        
                        <p>
                            Chez nous, nous pensons que le monde a besoin de meilleures solutions pour les emballages à usage unique. C'est pourquoi nous proposons désormais une alternative. Elle se nomme PickPack - un emballage de livraison réutilisable pour les commandes en ligne. Lorsque tu recevras ta commande, tu n'auras qu'à plier ton emballage au format lettre et le renvoyer dans une boite postale Poste Canada, tout simplement!
                        </p>
                        <p>
                            PickPack est une solution très simple au problème toujours croissant des déchets d'emballage. PickPack réduit les déchets et permet d'économiser une grande quantité des émissions de CO2 par rapport aux emballages à usage unique. Ajoutez un PickPack à votre commande pour 3.99 $ et recevez vos marchandises dans un emballage réutilisable et écologique. Le retour est GRATUIT !
                        </p>
                        <p>
                            Une solution à faible coût, mais à grand impact!
                        </p>
                        <div class="popup-footer">
                            <div class="footer-flex">
                                <button class="toggle pick_pack_button pick_pack_add" data-target="pick_pack_popup">Ajouter au panier</button>

                                <button type='button' class="toggle cancel-button">Non Merci</button>
                            </div>
                            <!-- <h5>Prix : $3 <span><button data-target="pick_pack_popup" class="close toggle pick_pack_button pick_pack_add" data-target="pick_pack_popup">Ajouter au panier</button></span></h5> -->

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
        jQuery('input[name="cart[<?php echo $eco_bag_key ?>][qty]"]').prop('disabled', true);

        jQuery( document.body ).on( 'updated_cart_totals', function(){
            jQuery('input[name="cart[<?php echo $eco_bag_key ?>][qty]"]').prop('disabled', true);
            
        });

        <?php
            // if(!isset($_SESSION["pick_pack_product_added"]) && $_SESSION["pick_pack_product_added"] == "" && !woo_in_cart($product_id)){
            if(!$this->pick_pack_woo_in_cart($product_id) && ($cart_count !== $fragile_count + $large_count ) && !$points_allocated_less){
                ?>
                    
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