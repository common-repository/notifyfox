    <?php
        if ($nf_error_found == TRUE && isset($nf_errors[0]) == TRUE) {
            ?><div class="error fade">
                <p><strong>
                    <?php echo $nf_errors[0]; ?>
                </strong></p>
            </div><?php
        }
        if ($nf_error_found == FALSE && strlen($nf_success) > 0) {
            ?><div class="updated fade">
                <p><strong>
                    <?php echo $nf_success; ?>
                </strong></p>
            </div><?php
        }
     ?>   


    <div class="form-wrap nf-parent">
        <div id="icon-plugins" class="icon32"></div>
        <h2 class="notify-heading">Activate <?php echo __( 'Notifyfox', NF_TDOMAIN ); ?> Plugin</h2>
        
        <div class="tab">
            <button class="tablinks active" onclick="changeTab(event, 'setting_tab')">Settings</button>
            <button class="tablinks" onclick="changeTab(event, 'help_tab')">Help</button>
        </div>
            <div id="setting_tab" class="tabcontent">
                <div id="left_div">
                    <form name="nf_form" method="post" action="">
                            <div class="form-groups">
                                <div class="form-heading"><?php echo __( 'User Id', NF_TDOMAIN ); ?></div>
                                <div>
                                    <input name="nf_c_apiusername" type="text" id="nf_c_apiusername" value="<?php echo esc_html($nf_form->nf_c_apiusername); ?>" class="nf-textbox"  required="required" autocomplete="off"/>
                                </div>
                                <div>
                                    <small class="nf-option"><?php echo __( 'Enter Notifyfox Api User Id', NF_TDOMAIN ); ?></small>
                                </div>
                            </div>
                            
                            <div class="form-groups">
                                <div class="form-heading"><?php echo __( 'API Password', NF_TDOMAIN ); ?></div>
                                <div>
                                    <input name="nf_c_apipassword" type="password" id="nf_c_apipassword" value="<?php echo esc_html($nf_form->nf_c_apipassword); ?>"  class="nf-textbox" required="required" autocomplete="off"/>
                                </div>
                                <div>
                                    <small class="nf-option"><?php echo __( 'Enter Notifyfox API Password', NF_TDOMAIN ); ?></small>
                                </div>
                            </div>
                            <div class="form-groups">
                                <div class="form-heading"><?php echo __( 'Client ID', NF_TDOMAIN ); ?></div>
                                <div>
                                    <input type="text" name="nf_c_client_id"  id="nf_c_client_id" value="<?php echo esc_html($nf_form->nf_c_client_id); ?>" class="nf-textbox" required="required" autocomplete="off"/>
                                </div>
                                <div>
                                    <small class="nf-option"><?php echo __( 'Enter Notifyfox Client ID', NF_TDOMAIN ); ?></small>
                                </div>
                            </div>
                            <div class="form-groups">
                                <div class="form-heading"><?php echo __( 'Client Secret', NF_TDOMAIN ); ?></div>
                                <div>
                                    <input name="nf_c_client_secret" type="text" id="nf_c_client_secret" value="<?php echo esc_html($nf_form->nf_c_client_secret); ?>" class="nf-textbox" required="required" autocomplete="off"/>
                                </div>
                                <div>
                                    <small class="nf-option"><?php echo __( 'Enter Notifyfox Client SecretD', NF_TDOMAIN ); ?></small>
                                </div>
                            </div>
                            <input type="hidden" name="nf_form_submit" value="yes"/>
                            <input type="hidden" name="nf_c_id" id="nf_c_id" value=""/>
                            <p style="padding-top:10px;">
                                <input type="submit" name="publish" class="button add-new-h2" value="<?php echo __( 'Save Settings', NF_TDOMAIN ); ?>" />
                            </p>
                            <?php wp_nonce_field('nf_form_edit'); ?>
                </form>
            </div>
            <div id="right_div">
                <h2><a href="#" class="cstm-btn">Visit notifyfox.com to Activate.</a></h2>
                <div class="img-gif-wrap">
                    <img src="<?php echo NF_URL.'images/help.gif'; ?>">
                </div>  
            </div>
        </div>

        <div id="help_tab" class="tabcontent" style="display: none;">
            <div class="help-content">
                <form name="nf_form_help" action="" method="post">
                            <div class="form-groups">
                                <div class="form-heading"><?php echo __( 'Name', NF_TDOMAIN ); ?></div>
                                <div>
                                    <input type="text" name="nf_form_help_name" id="nf_form_help_name" class="nf-textbox" required="required" />
                                </div>
                                <div>
                                    <small class="nf-option"><?php echo __( 'Enter your name', NF_TDOMAIN ); ?></small>
                                </div>
                            </div>
                    
                            <div class="form-groups">
                                <div class="form-heading"><?php echo __( 'Email', NF_TDOMAIN ); ?></div>
                                <div>
                                    <input type="email" name="nf_form_help_email" id="nf_form_help_email" class="nf-textbox" required="required"/>
                                </div>
                                <div>
                                    <small class="nf-option"><?php echo __( 'Enter your email', NF_TDOMAIN ); ?></small>
                                </div>
                            </div>
                        
                            <div class="form-groups">
                                <div class="form-heading"><?php echo __( 'Message', NF_TDOMAIN ); ?></div>
                                <div>
                                    <textarea name="nf_form_help_message" id="nf_form_help_message" class="nf-textbox" > </textarea> 
                                </div>
                                <div>
                                    <small class="nf-option"><?php echo __( 'Enter your Message', NF_TDOMAIN ); ?></small>
                                </div>
                            </div>
                            
                    <p style="padding-top:10px;">
                        <input type="submit" value="Submit" name="helpSubmitButton" class="button add-new-h2"/>
                    </p>
                </form>
                <div class="mail-info">
                    <p>
                        In case of any queries or concerns, fill out the contact form.You can also reach out us at <a href="mailto:info@notifyfox.com">info@notifyfox.com</a> or give us a call-
                    </p>
                </div>  
                <div class="line-breaker">
                    <div class="tel-img"></div>
                </div>
                <div class="tel_num">
                    <p>US - <a href="tel:+1 860-200-0055">+1 860-200-0055</a></p>
                    <p>UK - <a href="tel:+44 1600 8000 55">+44 1600 8000 55</a></p>
                </div>
            </div> 
        </div>
    </div>
    <div style="height:10px;"></div>
</div>