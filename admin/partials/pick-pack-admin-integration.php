<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://pick-pack.ca/
 * @since      1.0.0
 *
 * @package    Pick_pack_package
 * @subpackage Pick_pack_package/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="pick_pack_container container">
    <div class="row pt-5">
        <div class="col-sm-12 ">
            <h1 class="pick_pack_main_title">Pick Pack Integration</h1>
        </div>
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-10 ml-auto col-xl-12 mr-auto">
                <!-- Nav tabs -->
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs " role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                    <i class="fa-solid fa-address-card pr-1"></i> Pick Pack Model
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                    Price
                                </a>
                            </li> -->
                            <!-- <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                                    <i class="now-ui-icons shopping_shop"></i> Messages
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                                    <i class="now-ui-icons ui-2_settings-90"></i> Settings
                                </a>
                            </li> -->
                        </ul>
                    </div>
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content ">

                            <!-- Model changes -->
                            <div class="tab-pane active" id="home" role="tabpanel">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-sm-2 my-auto">
                                            <h5 class="pick_pack_title">Product Image</h5>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="hidden" name="pick_pack_product_image_upload" value="<?php if(isset($product_image) && !empty($product_image)){ echo $product_image; } ?>">
                                            <button id="pick_pack_product_image_upload" class="pick_pack_buttons" type="button" class="btn btn-primary" name="pick_pack_product_image_upload" >Upload</button>
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-lg-2 col-sm-12 my-auto">
                                            <h5 class="pick_pack_title">Product Title</h5>
                                        </div>
                                        <div class="col-lg-5 col-sm-12">
                                            <input type="text" name="pick_pack_product_title" class="pick_pack_input"  value="<?php if(isset($product_title) && !empty($product_title)){ echo $product_title; } ?>" placeholder="Product Title" >
                                        </div>
                                    </div>
                                    <div class="row pt-4">
                                        <div class="col-lg-2 col-sm-12">
                                            <h5 class="pick_pack_title">Product Text</h5>
                                        </div>
                                        <div class="col-lg-5 col-sm-12">
                                            <textarea name="pick_pack_product_text" class="pick_pack_input" id="" cols="30" rows="10"><?php if(isset($product_description) && !empty($product_description)){ echo $product_description; } ?></textarea>
                                        </div>
                                    </div>
                                    
                                    <?php foreach ($category_array as $category) { ?>
                                    <div class="row pt-4">
                                        <div class="col-lg-2 col-sm-12">
                                            <h5 class="pick_pack_title">Number of <?php echo $category['category_name'] ?> per Eco bag</h5>
                                        </div>
                                        <div class="col-lg-5 col-sm-12">
                                            <input type="number" name="product_per_bag_<?php echo $category['category_id'] ?>" class="pick_pack_input"  value="<?php  echo $category['category_value'] ?>" placeholder="Products per bag" >
                                        </div>
                                    </div>
                                <?php } ?>
                                    <div class="row pt-4">
                                        <div class="col-lg-2 col-sm-12"></div>
                                        <div class="col-lg-5 col-sm-12">
                                            <button type="submit" class="pick_pack_buttons_hover">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="profile" role="tabpanel">
                                    
                            </div>
                            <div class="tab-pane" id="messages" role="tabpanel">
                                <p>I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at. So when you get something that has the name Kanye West on it, it’s supposed to be pushing the furthest possibilities. I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus.</p>
                            </div>
                            <div class="tab-pane" id="settings" role="tabpanel">
                                <p>
                                    "I will be the leader of a company that ends up being worth billions of dollars, because I got the answers. I understand culture. I am the nucleus. I think that’s a responsibility that I have, to push possibilities, to show people, this is the level that things could be at."
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content ">

                            <!-- Model changes -->

                            <h2>Number of Eco Bags sold</h2>
                            
                            <?php echo $eco_bags_sold . '<br>'; ?>
                            
                            
                            <h2>Large Products: </h2>
                            <?php foreach ($products as $product) {
                                echo $product->name . '<br>';
                            }?>
                        


                            <h2>Fragile Products: </h2>
                            <?php foreach ($products_2 as $product) {
                                echo $product->name . '<br>';
                            }?>

                            
                            <?php file_put_contents(get_template_directory() . '/somefilename.txt', print_r($eco_bag_token, true), FILE_APPEND);if ($eco_bag_token != false){
                                if ($eco_bag_token === true){
                                    ?>
                                    <h2>Set up Payment Details</h2>
                                    <a href="<?php echo esc_url( plugins_url( 'pick-pack/payment_method.php') )?>">Register Payment Method</a>
                            <?php }
                            }?>   
                            
                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>