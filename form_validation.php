<?php
add_filter( 'wpcf7_validate_text*', 'text_validation_special_char', 20, 2 );
function text_validation_special_char( $result, $tag ) {
    $tag = new WPCF7_Shortcode($tag);
    if ( 'your-name' == $tag->name ) {
        // matches any utf words with the first not starting with a number
        $value = isset($_POST[$tag->name]) ? trim($_POST[$tag->name]) : '';

        $without_space = preg_replace('/\s+/', '', $value); 
        if(strlen($without_space) == 0){
            $result->invalidate($tag, "Please enter valid name."); 
        }

        if ($value !== '' && strlen($without_space) > 0) {
            // Regular expression to match special characters
            $pattern = '/[!@#$%^&*(),.?":{}|<>]/';
    
            // Check if the value contains any special characters
            if (preg_match($pattern, $value)) {
                $result->invalidate($tag, "Please remove special characters from your input.");
            }
            if ( !preg_match('/^[a-zA-ZÀ-ÿ ]+$/', $value) ) {
                $result->invalidate( $tag, "Please remove the numeric value from your input." );
            }
        }
    }
    return $result;
}

add_filter('wpcf7_validate_tel', 'custom_tel_validation', 10, 2);

function custom_tel_validation($result, $tag) {
    $tag = new WPCF7_Shortcode($tag);

    // Get the posted value of the field
    $value = isset($_POST[$tag->name]) ? trim($_POST[$tag->name]) : '';

    if(!empty($value)){
        // Set minimum and maximum length
        $min_length = 5;
        $max_length = 15;

        // Check if the length is within the specified range
        $length = strlen($value);
        if ($length < $min_length || $length > $max_length) {
            $result->invalidate($tag, "Phone number must be between $min_length and $max_length characters long.");
        }
    }
    return $result;
}


add_action('woocommerce_register_post', 'password_length_validation', 10, 3);

function password_length_validation($username, $email, $validation_errors) {
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Set minimum and maximum length
    $min_length = 6;
    $max_length = 20;

    // Check if password length is within the specified range
    $length = strlen($password);
    if ($length < $min_length || $length > $max_length) {
        $validation_errors->add('password_error', "Password must be between $min_length and $max_length characters long.");
    }

    return $validation_errors;
}


add_action( "wp_footer", "cf7_input_validat_space" );
function cf7_input_validat_space(){
    ?>
    <script>
        jQuery(document).ready(function(){
            jQuery(".wpcf7-form input").keydown(function(e){
                if( !$(this).val() ){
                    if(event.key === ' '){
                        return false;
                    } 
                }
                
                var withoutSpace = $(this).val().replace(/ /g,'').length;
                if(withoutSpace == 0){
                    if(event.key === ' '){
                        return false;
                    } 
                }
            });
            jQuery(".wpcf7-form textarea").keydown(function(e){
                if( !$(this).val() ){
                    if(event.key === ' '){
                        return false;
                    } 
                }

                var withoutSpace = $(this).val().replace(/ /g,'').length;
                if(withoutSpace == 0){
                    if(event.key === ' '){
                        return false;
                    } 
                }
            });
        });
    </script>
    <?php
}
