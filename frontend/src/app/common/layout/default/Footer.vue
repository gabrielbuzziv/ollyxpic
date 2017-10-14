<template>
    <div id="footer">
        <div class="bar">
            <div class="container">
                <router-link :to="{ name: 'pages.contact' }">
                    Found a bug? Report it here! (:
                </router-link>
            </div>
        </div>

        <div class="rights">
            <div class="container">
                <p class="ollyxpic">
				
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="J8VPUH8PS9ANS">
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form><br>
		
				<a href="http://ollyxpic.com">OllyxPic.com</a> &copy; 2017. All rights reserved.</p>
                <p class="tibia">
                    Please note that the only official website is <a href="http://www.tibia.com" target="_blank">www.tibia.com</a>
                    and from there part of statistical information as well as creature and item graphic info is gathered.
                    Tibia is copyrighted by <a href="https://www.cipsoft.com/" target="_blank">CipSoft GmbH</a>.
                </p>
            </div>
        </div>
    </div>
</template>