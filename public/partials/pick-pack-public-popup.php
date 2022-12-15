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
            <!-- <div class="logo-container"> -->
                <!-- <img class="pick-pack-logo" src="https://phpstack-851887-2938889.cloudwaysapps.com/wp-plugin-server/assets/Logo_Officiel_PickPack_2-removebg-preview.png" alt=""> -->
            <h3><?php esc_attr_e("Do you want a PickPack reusable package?","pick-pack") ?></h3>

            <h4><?php esc_attr_e("As a thank you for using the PickPack service, the online store where you are shopping will offer you 10% off your next order*.","pick-pack") ?></h4>
            <!-- </div> -->
           
            <div class="popup_flex">

                <img class="pick-pack-logo" src="https://phpstack-851887-2938889.cloudwaysapps.com/wp-plugin-server/assets/WhatsApp Image 2022-12-14 at 21.19.54.jpeg" alt="">
                

                <div class="popup_col">
                    <div class="cnt">
                        
                        <p><?php esc_attr_e("The PickPack option allows you to reduce your environmental footprint by returning your packaging after receiving your package.","pick-pack") ?>
                        </p>
                        <p><?php esc_attr_e("TRULY be part of the environmental solution for only $3.99.","pick-pack") ?>
                        </p>
                        <p><?php esc_attr_e("The return is FREE and SIMPLE.","pick-pack") ?>
                        </p>
                        <p><?php esc_attr_e("See the return procedure (Collaboration with Canada Post)", "pick-pack") ?>
                        </p>
                        
                    </div>
                    
                </div>

                <label class="form-control-checkbox">
                  <input type="checkbox" name="checkbox-pickpack"  id="checkbox-pickpack"/>
                  <?php esc_attr_e("My order will be delivered in Canada", "pick-pack") ?>
                </label>

                <div class="popup-footer">
                    <div class="footer-flex">
                        <button class=" pick_pack_button pick_pack_add" data-target="pick_pack_popup"><?php esc_attr_e("Yes, I want to make a difference.", "pick-pack") ?></button>

                        <button type='button' class="toggle cancel-button"><?php esc_attr_e("No thank you", "pick-pack") ?></button>
                    </div>
                    <div class="centered">
                        <p class="inline-text"><?php esc_attr_e("Initiative powered by PickPack", "pick-pack") ?> </p>
                        <img class="inline-image" src="https://phpstack-851887-2938889.cloudwaysapps.com/wp-plugin-server/assets/Logo_Officiel_PickPack_2-removebg-preview.png" alt="">
                    </div>
                    
                    <p class="conditions"><?php 
                        $span_text = '<span>CONDITIONS : </span>';
                        $string = esc_attr__("CONDITIONS : You must choose the PickPack option AND return your packaging as agreed.", "pick-pack");
                        $string = str_replace("CONDITIONS : ", "",$string);
                        echo $span_text . $string;
                     ?>
                    </p>
                    <!-- <h5>Prix : $3 <span><button data-target="pick_pack_popup" class="close toggle pick_pack_button pick_pack_add" data-target="pick_pack_popup">Ajouter au panier</button></span></h5> -->

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