<!-- WordPress Management Tools Admin Plugin Page -->

<div class="wrap">
	
    <h2><?php echo PAGE_TITLE; ?></h2>
    
    <p>The <a href="https://hardycode.com.au/tools/wordpress-management-tool" target="_blank">WordPress Management Tool (WPMT) by Hardy Code</a> is designed to assist those that are responsible for multiple installations of WordPress around the net.</p>
			        
    <p>All an administrator has to do is install the WPMT plugin, sign up for the <a href="https://hardycode.com.au/tools" target="_blank">Hardy Code Tools</a> and register the generated Site Key.</p>
    
    <table class="form-table">
        
        <tr>
            <th>Local Reporting Status</th>
            <td>
                <?php echo ucwords(API::status()); ?><br>
                <i>This reflects whether the plugin will respond to a call from the Hardy Code servers to provide updated site records.</i>
            </td>
        </tr>
        <!--  
        <tr>
            <th>Remote Reporting Status</th>
            <td>
                <?php echo ucwords(API::remote_status()); ?><br>
            </td>
        </tr>

        <tr>
            <th>Remote Reporting Options</th>
            <td>
                <?php
                foreach(API::remote_reporting_options() as $option) {
                    echo "<a href='".$option[1]."'>".$option[0]."</a>";
                }
                ?><br>
                <i>This setting, can also be adjusted via your WMPT Console over at <a href="https://hardycode.com.au/wpmt" target="_blank">hardycode.com.au/wpmt</a>.</i>
            </td>
        </tr>
        -->
        <tr>
            <th>Push Update</th>
            <td><a href="<?php echo "?page=".PAGE_MENU_SLUG."&push_update=now" ?>">Now!</a></td>
        </tr>
        
        <tr>
            <th>Site Key</th>
            <td><?php echo Key::get(); ?></td>
        </tr>
        
        <tr>
            <th>CURL Support</th>
            <td>
                <?php echo  Plugin::dependancy("curl", true); ?><br>
                CURL must be supported in order for the plugin to communicate with Hardy Code's servers.
            </td>
        </tr>
        
        <tr>
            <th>XML-RPC Support</th>
            <td>
                <?php echo  Plugin::dependancy("xmlrpc", true); ?>
            </td>
        </tr>
        
        <tr>
            <th>API URL</th>
            <td><?php echo  Info::apiURL(); ?></td>
        </tr>
    
    </table>
    
    <p>Want to see the code for yourself? Check it out over at <a href="https://github.com/aaronheath/WPMTPlugin" target="_blank">GitHub</a>.</p>
    
    <p>You're currently running version <?php echo PLUGIN_VERSION ?> of the plugin.</p>
    
</div>